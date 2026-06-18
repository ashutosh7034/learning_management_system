<?php
/**
 * Password hashing with transparent migration of legacy plaintext credentials.
 *
 * The legacy lms_login table stored passwords in plaintext. We keep those accounts
 * working: on a successful login with a plaintext-matching password we rehash and
 * persist a secure bcrypt hash, so credentials self-migrate on next sign-in.
 */

if (!function_exists('lms_hash_password')) {
    function lms_hash_password($plain)
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }
}

if (!function_exists('lms_is_hashed')) {
    /** True if the stored value already looks like a PHP password_hash() output. */
    function lms_is_hashed($stored)
    {
        $info = password_get_info((string) $stored);
        return isset($info['algo']) && $info['algo'] !== null && $info['algo'] !== 0;
    }
}

if (!function_exists('lms_verify_password')) {
    /**
     * Verify a password against the stored value (hashed or legacy plaintext).
     *
     * @param bool $needsRehash  set to true when the stored value should be
     *                           upgraded to a secure hash by the caller.
     */
    function lms_verify_password($plain, $stored, &$needsRehash = false)
    {
        $needsRehash = false;
        $stored = (string) $stored;

        if (lms_is_hashed($stored)) {
            if (password_verify($plain, $stored)) {
                $needsRehash = password_needs_rehash($stored, PASSWORD_DEFAULT);
                return true;
            }
            return false;
        }

        // Legacy plaintext path — match then flag for upgrade.
        if (hash_equals($stored, (string) $plain)) {
            $needsRehash = true;
            return true;
        }
        return false;
    }
}
?>

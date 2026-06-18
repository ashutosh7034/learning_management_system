<?php
class DBController
{
    /*public $host = "localhost";
    public $user = "lms";
    public $password = "Tcet@1378";
    public $database = " lms";*/

    public $host = "localhost";
    public $user = "root";
    public $password = "";
    public $database = "lms"; 
      
   public $conn;
  public $last_error = '';

    function __construct()
    {
        $this->conn = $this->connectDB();
    }

    function query($query)
    {
    $result = mysqli_query($this->conn,$query);
    return $result;
    }

    function connectDB()
    {
      mysqli_report(MYSQLI_REPORT_OFF);
      $conn = @mysqli_connect($this->host,$this->user,$this->password,$this->database);
       if (!$conn) {
        $this->last_error = 'Unable to connect with database';
        return false;
      }


      return $conn;
    }

    public function runQuery($query) {
    $result = mysqli_query($this->conn, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($this->conn));
    }

    $resultset = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $resultset[] = $row;
    }
    return $resultset;
}


    function numRows($query)
    {
        $result = mysqli_query($this->conn,$query);
        $rowcount = mysqli_num_rows($result);
        return $rowcount;
    }

    function executeUpdate($query)
    {
        $result = mysqli_query($this->conn,$query);
        return $result;
    }

  function readData($query)
    {
          $result = mysqli_query($this->conn,$query);
         while($row=mysqli_fetch_assoc($result))
       {
            $resultset[] = $row;
           }
          if(!empty($resultset))
            return $resultset;
    }

  function executeInsert($query)
    {
      $result = mysqli_query($this->conn,$query);
      $insert_id = mysqli_insert_id($this->conn);
        return $insert_id;
    }

    function cleanData($data)
    {
          $data = mysqli_real_escape_string($this->conn,strip_tags($data));
          return $data;
    }

  private function auditLogColumnExists($columnName)
  {
    $safeColumn = mysqli_real_escape_string($this->conn, $columnName);
    $result = mysqli_query($this->conn, "SHOW COLUMNS FROM lms_audit_log LIKE '$safeColumn'");
    $exists = ($result && mysqli_num_rows($result) > 0);

    if ($result) {
      mysqli_free_result($result);
    }

    return $exists;
  }

  public function ensureAuditLogTable()
  {
    if (!($this->conn instanceof mysqli)) {
      return false;
    }

    $createSql = "CREATE TABLE IF NOT EXISTS lms_audit_log (
      audit_id int(11) NOT NULL AUTO_INCREMENT,
      user_id int(11) NOT NULL DEFAULT 0,
      action_type varchar(100) NOT NULL,
      affected_table varchar(100) DEFAULT NULL,
      affected_record int(11) DEFAULT NULL,
      description text DEFAULT NULL,
      username varchar(200) DEFAULT NULL,
      ip_address varchar(45) DEFAULT NULL,
      browser_user_agent text DEFAULT NULL,
      session_duration_seconds int(11) DEFAULT NULL,
      logout_at timestamp NULL DEFAULT NULL,
      performed_at timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (audit_id),
      KEY action_type (action_type),
      KEY user_id (user_id),
      KEY performed_at (performed_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

    if (!mysqli_query($this->conn, $createSql)) {
      return false;
    }

    $columns = array(
      'username' => "ALTER TABLE lms_audit_log ADD COLUMN username varchar(200) DEFAULT NULL AFTER description",
      'ip_address' => "ALTER TABLE lms_audit_log ADD COLUMN ip_address varchar(45) DEFAULT NULL AFTER username",
      'browser_user_agent' => "ALTER TABLE lms_audit_log ADD COLUMN browser_user_agent text DEFAULT NULL AFTER ip_address",
      'session_duration_seconds' => "ALTER TABLE lms_audit_log ADD COLUMN session_duration_seconds int(11) DEFAULT NULL AFTER browser_user_agent",
      'logout_at' => "ALTER TABLE lms_audit_log ADD COLUMN logout_at timestamp NULL DEFAULT NULL AFTER session_duration_seconds"
    );

    foreach ($columns as $column => $alterSql) {
      if (!$this->auditLogColumnExists($column)) {
        mysqli_query($this->conn, $alterSql);
      }
    }

    return true;
  }

 public function writeAuditLog($userId, $actionType, $affectedTable = null, $affectedRecord = null, $description = null, $username = null, $ipAddress = null, $userAgent = null, $sessionDurationSeconds = null)
  {
    if (!($this->conn instanceof mysqli)) {
      return false;
    }

    if (!$this->ensureAuditLogTable()) {
      return false;
    }

    $sql = "INSERT INTO lms_audit_log (user_id, action_type, affected_table, affected_record, description, username, ip_address, browser_user_agent, session_duration_seconds) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($this->conn, $sql);

    if (!$stmt) {
      return false;
    }

    $safeUserId = intval($userId);
    $safeActionType = (string) $actionType;
    $safeAffectedTable = $affectedTable !== null ? (string) $affectedTable : null;
    $safeAffectedRecord = $affectedRecord !== null ? intval($affectedRecord) : null;
    $safeDescription = $description !== null ? (string) $description : null;
    $safeUsername = $username !== null ? (string) $username : null;
    $safeIpAddress = $ipAddress !== null ? (string) $ipAddress : null;
    $safeUserAgent = $userAgent !== null ? (string) $userAgent : null;
    $safeSessionDuration = $sessionDurationSeconds !== null ? intval($sessionDurationSeconds) : null;

    mysqli_stmt_bind_param($stmt, "ississssi", $safeUserId, $safeActionType, $safeAffectedTable, $safeAffectedRecord, $safeDescription, $safeUsername, $safeIpAddress, $safeUserAgent, $safeSessionDuration);
    $result = mysqli_stmt_execute($stmt);
    $insertId = $result ? mysqli_insert_id($this->conn) : false;
    mysqli_stmt_close($stmt);

    return $insertId;
  }

  public function completeAuditSession($auditId, $userId, $description = null, $sessionDurationSeconds = null)
  {
    if (!($this->conn instanceof mysqli)) {
      return false;
    }

    if (!$this->ensureAuditLogTable()) {
      return false;
    }

    $sql = "UPDATE lms_audit_log
            SET logout_at = NOW(),
                session_duration_seconds = ?,
                description = CONCAT(COALESCE(description, ''), ?)
            WHERE audit_id = ?
              AND user_id = ?
              AND action_type = 'LOGIN_SUCCESS'
            LIMIT 1";
    $stmt = mysqli_prepare($this->conn, $sql);

    if (!$stmt) {
      return false;
    }

    $safeDuration = $sessionDurationSeconds !== null ? intval($sessionDurationSeconds) : null;
    $safeDescription = $description !== null ? "\n" . (string) $description : '';
    $safeAuditId = intval($auditId);
    $safeUserId = intval($userId);

    mysqli_stmt_bind_param($stmt, "isii", $safeDuration, $safeDescription, $safeAuditId, $safeUserId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
  }

  public function writeLogoutLog($userId, $loginId = null, $username = null, $ipAddress = null, $userAgent = null, $sessionDurationSeconds = null)
  {
    if (!($this->conn instanceof mysqli)) {
      return false;
    }

    $sql = "INSERT INTO lms_user_logout_log (user_id, login_id, username, ip_address, browser_user_agent, session_duration_seconds) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($this->conn, $sql);

    if (!$stmt) {
      return false;
    }

    $safeUserId = intval($userId);
    $safeLoginId = $loginId !== null ? intval($loginId) : null;
    $safeUsername = $username !== null ? (string) $username : null;
    $safeIpAddress = $ipAddress !== null ? (string) $ipAddress : null;
    $safeUserAgent = $userAgent !== null ? (string) $userAgent : null;
    $safeSessionDuration = $sessionDurationSeconds !== null ? intval($sessionDurationSeconds) : null;

    mysqli_stmt_bind_param($stmt, "iisssi", $safeUserId, $safeLoginId, $safeUsername, $safeIpAddress, $safeUserAgent, $safeSessionDuration);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
  }

 public function loginPage($myusername, $mypassword)
  {
    $mypassword = md5($mypassword);

    $sql = "SELECT * FROM (SELECT * FROM user_master_activate UNION SELECT * FROM user_master ) AS U WHERE U.user_name = ? and U.password = ? AND U.flag = '1' AND U.status = '1'";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $myusername, $mypassword);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      return 1;
    } else {
      return 0;
    }

  } 

}
?>

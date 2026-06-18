<?php
require_once __DIR__ . '/../../includes/config.php';

/**
 * Resolve a sidebar route to an absolute href so links work from any role area
 * (/admin, /teacher, /student) regardless of the current page's location.
 *   - "#" / empty / external URLs        -> returned unchanged
 *   - app-root-relative LMS routes ("student/catalog.php", contain "/")
 *                                         -> LMS_BASE_URL/<route>
 *   - legacy bare filenames ("profile.php", no "/") live in /admin
 *                                         -> LMS_BASE_URL/admin/<route>
 */
function sidebar_resolve_route($route)
{
    $route = (string) $route;
    if ($route === '' || $route === '#') {
        return '#';
    }
    if (preg_match('#^(https?:)?//#', $route) || strpos($route, '#') === 0) {
        return $route;
    }
    if (strpos($route, '/') !== false) {
        return LMS_BASE_URL . '/' . ltrim($route, '/');
    }
    return LMS_BASE_URL . '/admin/' . $route;
}

function sidebar_has_column($conn, $table, $column)
{
	$escapedColumn = mysqli_real_escape_string($conn, $column);
	$result = mysqli_query($conn, "SHOW COLUMNS FROM $table LIKE '$escapedColumn'");
	$exists = ($result && mysqli_num_rows($result) > 0);

	if ($result) {
		mysqli_free_result($result);
	}

	return $exists;
}

$menuHasIconColumn = sidebar_has_column($db_handle->conn, 'lms_menu_master', 'menu_icon');
$subMenuHasIconColumn = sidebar_has_column($db_handle->conn, 'lms_sub_menu_master', 'sub_menu_icon');
$subMenuHasRouteColumn = sidebar_has_column($db_handle->conn, 'lms_sub_menu_master', 'sub_menu_route');

$menuTree = array();
$studentAdmissionRoute = 'student_admission.php';

if ((int) ($usertype ?? 0) === 5 && !empty($userid)) {
	$studentRouteSql = "SELECT student_id FROM lms_user_master WHERE user_id = ? AND student_id > 0 LIMIT 1";
	$studentRouteStmt = mysqli_prepare($db_handle->conn, $studentRouteSql);
	if ($studentRouteStmt) {
		mysqli_stmt_bind_param($studentRouteStmt, 'i', $userid);
		mysqli_stmt_execute($studentRouteStmt);
		$studentRouteResult = mysqli_stmt_get_result($studentRouteStmt);
		if ($studentRouteResult && ($studentRouteRow = mysqli_fetch_assoc($studentRouteResult))) {
			$studentAdmissionRoute = 'student_admission_view.php?id=' . intval($studentRouteRow['student_id']);
		}
		mysqli_stmt_close($studentRouteStmt);
	}
}

$menuIconSelect = $menuHasIconColumn
	? "COALESCE(NULLIF(TRIM(m.menu_icon), ''), 'fa fa-folder') AS menu_icon"
	: "'fa fa-folder' AS menu_icon";
$subMenuRouteSelect = $subMenuHasRouteColumn
	? "COALESCE(NULLIF(TRIM(sm.sub_menu_route), ''), '#') AS sub_menu_route"
	: "'#' AS sub_menu_route";
$subMenuIconSelect = $subMenuHasIconColumn
	? "COALESCE(NULLIF(TRIM(sm.sub_menu_icon), ''), 'fa fa-angle-double-right') AS sub_menu_icon"
	: "'fa fa-angle-double-right' AS sub_menu_icon";

function sidebar_seed_super_admin_settings($db_handle)
{
	// 1. Seed personal Settings (menu_id = 5)
	$settingsMenuId = 5; 
	$settingsItems = array(
		array('Profile', 'fa fa-user', 'profile.php'),
		array('Update Password', 'fa fa-lock', 'change_password.php')
	);

	foreach ($settingsItems as $item) {
		$name = mysqli_real_escape_string($db_handle->conn, $item[0]);
		$icon = mysqli_real_escape_string($db_handle->conn, $item[1]);
		$route = mysqli_real_escape_string($db_handle->conn, $item[2]);

		$subSql = "SELECT sub_menu_id FROM lms_sub_menu_master WHERE menu_id = {$settingsMenuId} AND (sub_menu_route = '$route' OR sub_menu_name = '$name') LIMIT 1";
		$subResult = mysqli_query($db_handle->conn, $subSql);
		if (!$subResult || mysqli_num_rows($subResult) === 0) {
			$nextOrder = 1;
			$orderSql = "SELECT COALESCE(MAX(sort_order), 0) + 1 AS next_order FROM lms_sub_menu_master WHERE menu_id = {$settingsMenuId}";
			$orderResult = mysqli_query($db_handle->conn, $orderSql);
			if ($orderResult && mysqli_num_rows($orderResult) > 0) {
				$orderRow = mysqli_fetch_assoc($orderResult);
				$nextOrder = (int) ($orderRow['next_order'] ?? 1);
			}
			$insertSql = "INSERT INTO lms_sub_menu_master (menu_id, sort_order, sub_menu_name, sub_menu_icon, sub_menu_route) VALUES ({$settingsMenuId}, {$nextOrder}, '$name', '$icon', '$route')";
			mysqli_query($db_handle->conn, $insertSql);
		}
	}

	// 2. Seed Admin configurations under Administration (menu_id = 9)
	$adminMenuId = 9; 
	$adminItems = array(
		array('Masters', 'fa fa-cog', 'class_crud_new.php#section-list'),
		array('Side Menu Allocation', 'fa fa-check-square-o', 'allocation_master.php'),
		array('Audit Log', 'fa fa-history', 'audit_log.php')
	);

	foreach ($adminItems as $item) {
		$name = mysqli_real_escape_string($db_handle->conn, $item[0]);
		$icon = mysqli_real_escape_string($db_handle->conn, $item[1]);
		$route = mysqli_real_escape_string($db_handle->conn, $item[2]);

		$subSql = "SELECT sub_menu_id FROM lms_sub_menu_master WHERE menu_id = {$adminMenuId} AND (sub_menu_route = '$route' OR sub_menu_name = '$name') LIMIT 1";
		$subResult = mysqli_query($db_handle->conn, $subSql);
		if (!$subResult || mysqli_num_rows($subResult) === 0) {
			$nextOrder = 1;
			$orderSql = "SELECT COALESCE(MAX(sort_order), 0) + 1 AS next_order FROM lms_sub_menu_master WHERE menu_id = {$adminMenuId}";
			$orderResult = mysqli_query($db_handle->conn, $orderSql);
			if ($orderResult && mysqli_num_rows($orderResult) > 0) {
				$orderRow = mysqli_fetch_assoc($orderResult);
				$nextOrder = (int) ($orderRow['next_order'] ?? 1);
			}
			$insertSql = "INSERT INTO lms_sub_menu_master (menu_id, sort_order, sub_menu_name, sub_menu_icon, sub_menu_route) VALUES ({$adminMenuId}, {$nextOrder}, '$name', '$icon', '$route')";
			mysqli_query($db_handle->conn, $insertSql);
		}
	}

	// 3. Ensure role_id = 1 has parent menu allocations
	foreach (array($settingsMenuId, $adminMenuId) as $mId) {
		$parentSql = "SELECT 1 FROM lms_menu_allocation_master WHERE user_id = 0 AND role_id = 1 AND menu_id = {$mId} AND sub_menu_id IS NULL LIMIT 1";
		$parentResult = mysqli_query($db_handle->conn, $parentSql);
		if (!$parentResult || mysqli_num_rows($parentResult) === 0) {
			mysqli_query($db_handle->conn, "INSERT INTO lms_menu_allocation_master (user_id, role_id, menu_id, sub_menu_id) VALUES (0, 1, {$mId}, NULL)");
		}
	}

	// 4. Ensure all submenus under these menus are allocated to Super Admin (role_id = 1)
	$settingsRows = mysqli_query($db_handle->conn, "SELECT sub_menu_id, menu_id FROM lms_sub_menu_master WHERE menu_id IN ({$settingsMenuId}, {$adminMenuId})");
	if ($settingsRows) {
		while ($settingRow = mysqli_fetch_assoc($settingsRows)) {
			$subMenuId = (int) ($settingRow['sub_menu_id'] ?? 0);
			$mId = (int) ($settingRow['menu_id'] ?? 0);
			if ($subMenuId > 0) {
				$allocSql = "SELECT 1 FROM lms_menu_allocation_master WHERE user_id = 0 AND role_id = 1 AND menu_id = {$mId} AND sub_menu_id = {$subMenuId} LIMIT 1";
				$allocResult = mysqli_query($db_handle->conn, $allocSql);
				if (!$allocResult || mysqli_num_rows($allocResult) === 0) {
					mysqli_query($db_handle->conn, "INSERT INTO lms_menu_allocation_master (user_id, role_id, menu_id, sub_menu_id) VALUES (0, 1, {$mId}, {$subMenuId})");
				}
			}
		}
	}
}

if ((int) $usertype === 1) {
	sidebar_seed_super_admin_settings($db_handle);
}

$menuSql = "SELECT m.menu_id, m.menu_name, $menuIconSelect
			FROM lms_menu_master m
			WHERE (
				EXISTS (
					SELECT 1
					FROM lms_menu_allocation_master mar
					WHERE mar.menu_id = m.menu_id
					  AND mar.role_id = ?
					  AND mar.sub_menu_id IS NULL
				)
				OR EXISTS (
					SELECT 1
					FROM lms_menu_allocation_master maa
					WHERE maa.menu_id = m.menu_id
					  AND maa.role_id = ?
					  AND maa.sub_menu_id IS NOT NULL
				)
			)
			ORDER BY m.menu_id";
$menuStmt = mysqli_prepare($db_handle->conn, $menuSql);

if ($menuStmt) {
	mysqli_stmt_bind_param($menuStmt, 'ii', $usertype, $usertype);
	mysqli_stmt_execute($menuStmt);
	$menuResult = mysqli_stmt_get_result($menuStmt);

	while ($menuRow = mysqli_fetch_assoc($menuResult)) {
		$menuId = (int) $menuRow['menu_id'];
		$menuTree[$menuId] = array(
			'menu_name' => $menuRow['menu_name'],
			'menu_icon' => $menuRow['menu_icon'],
			'submenus' => array()
		);

		$subSql = "SELECT sm.sub_menu_id, sm.sub_menu_name, $subMenuRouteSelect, $subMenuIconSelect
				   FROM lms_sub_menu_master sm
				   WHERE sm.menu_id = ?
				   AND (
					   EXISTS (
						   SELECT 1
						   FROM lms_menu_allocation_master mar
						   WHERE mar.sub_menu_id = sm.sub_menu_id
							 AND mar.role_id = ?
					   )
				   )
				   ORDER BY sm.sort_order ASC, sm.sub_menu_id ASC";
		$subStmt = mysqli_prepare($db_handle->conn, $subSql);

		if ($subStmt) {
			mysqli_stmt_bind_param($subStmt, 'ii', $menuId, $usertype);
			mysqli_stmt_execute($subStmt);
			$subResult = mysqli_stmt_get_result($subStmt);

			while ($subRow = mysqli_fetch_assoc($subResult)) {
				$menuTree[$menuId]['submenus'][] = $subRow;
			}

			mysqli_stmt_close($subStmt);
		}
	}

	mysqli_stmt_close($menuStmt);
}
?>

<ul class="sidebar-menu" id="sidebar-dynamic-menu">
<?php
$sidebarHomeRoute = ((int) ($usertype ?? 0) === 5) ? 'student/dashboard.php' : 'index.php';
$sidebarHomeLabel = ((int) ($usertype ?? 0) === 5) ? 'DASHBOARD' : strtoupper((string) ($role_name ?? 'Dashboard'));
$sidebarHomeIcon = ((int) ($usertype ?? 0) === 5) ? 'fa fa-dashboard' : 'fa fa-user';
?>
<li class="active"><a href="<?php echo htmlspecialchars(sidebar_resolve_route($sidebarHomeRoute)); ?>"><i class="<?php echo htmlspecialchars($sidebarHomeIcon); ?>"></i><span><?php echo htmlspecialchars($sidebarHomeLabel); ?></span></a></li>

<?php foreach ($menuTree as $menuId => $menuData) {
	$menuName = trim((string) $menuData['menu_name']);
	$menuIcon = trim((string) $menuData['menu_icon']);
	if ($menuIcon === '') {
		$menuIcon = 'fa fa-folder';
	}
?>
<li class="treeview" data-menu-id="<?php echo $menuId; ?>" id="sidebar-menu-<?php echo $menuId; ?>">
<a href="#">
<i class="<?php echo htmlspecialchars($menuIcon); ?>" aria-hidden="true"></i> <span><?php echo strtoupper(htmlspecialchars($menuName)); ?></span>
<span class="pull-right-container">
<i class="fa fa-angle-right pull-right"></i>
</span>
</a>
<ul class="treeview-menu" id="sidebar-submenu-<?php echo $menuId; ?>">
<?php if (!empty($menuData['submenus'])) {
	foreach ($menuData['submenus'] as $subMenu) {
		$subId = intval($subMenu['sub_menu_id']);
		$subName = trim((string) $subMenu['sub_menu_name']);
		$subRoute = trim((string) ($subMenu['sub_menu_route'] ?? '#'));
		$subIcon = trim((string) ($subMenu['sub_menu_icon'] ?? 'fa fa-angle-double-right'));
		if ($subRoute === '') {
			$subRoute = '#';
		}
		if ((int) ($usertype ?? 0) === 5 && $subRoute === 'student_admission.php') {
			$subRoute = $studentAdmissionRoute;
		}
		if ($subIcon === '') {
			$subIcon = 'fa fa-angle-double-right';
		}
?>
<li data-sub-menu-id="<?php echo $subId; ?>" id="sidebar-submenu-item-<?php echo $subId; ?>"><a href="<?php echo htmlspecialchars(sidebar_resolve_route($subRoute)); ?>"><i class="<?php echo htmlspecialchars($subIcon); ?>"></i><?php echo strtoupper(htmlspecialchars($subName)); ?></a></li>
<?php }
} ?>
</ul>
</li>
<?php } ?>

</ul>

<?php if (empty($menuTree)) { ?>
<div class="text-muted" style="padding: 10px 15px;">No menu items are assigned to this role.</div>
<?php } ?>

</section>

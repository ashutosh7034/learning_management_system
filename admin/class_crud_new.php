<?php ob_start(); include "header/header.php"; ?>
<?php
$masters = array(
  'class' => array(
    'title' => 'Class',
    'table' => 'lms_class_master',
    'pk' => 'class_id',
    'name' => 'class_name'
  ),
  'section' => array(
    'title' => 'Section',
    'table' => 'lms_section_master',
    'pk' => 'id',
    'name' => 'sections'
  ),
  'department' => array(
    'title' => 'Department',
    'table' => 'lms_department_master',
    'pk' => 'department_id',
    'name' => 'department_name'
  ),
  'menu' => array(
    'title' => 'Menu',
    'table' => 'lms_menu_master',
    'pk' => 'menu_id',
    'name' => 'menu_name'
  )
);

$customMasters = array(
  'academic_year' => array(
    'title' => 'Academic Year',
    'table' => 'lms_session_master',
    'pk' => 'session_id',
    'formFields' => array(
      array(
        'name' => 'session_name',
        'label' => 'Academic Year',
        'type' => 'text',
        'required' => true,
        'unique' => true
      )
    ),
    'displayFields' => array('session_name')
  ),
  'graduating_year' => array(
    'title' => 'Graduating Year',
    'table' => 'lms_batch_master',
    'pk' => 'batch_id',
    'formFields' => array(
      array(
        'name' => 'batch_name',
        'label' => 'Graduating Year',
        'type' => 'text',
        'required' => true,
        'unique' => true
      )
    ),
    'displayFields' => array('batch_name')
  ),
  'specialization' => array(
    'title' => 'Specialization',
    'table' => 'lms_specialization_master',
    'pk' => 'specialization_id',
    'formFields' => array(
      array(
        'name' => 'specialization_name',
        'label' => 'Specialization Name',
        'type' => 'text',
        'required' => true,
        'unique' => true
      ),
      array(
        'name' => 'min_cgpa',
        'label' => 'Minimum CGPA',
        'type' => 'number',
        'step' => '0.01',
        'default' => '0.00'
      ),
      array(
        'name' => 'kt_allowed',
        'label' => 'KT Allowed',
        'type' => 'boolean_select',
        'default' => '0'
      ),
      array(
        'name' => 'sem_from',
        'label' => 'Semester From',
        'type' => 'number',
        'default' => ''
      ),
      array(
        'name' => 'sem_to',
        'label' => 'Semester To',
        'type' => 'number',
        'default' => ''
      ),
      array(
        'name' => 'is_exclusive',
        'label' => 'Is Exclusive',
        'type' => 'boolean_select',
        'default' => '0'
      )
    ),
    'displayFields' => array('specialization_name', 'min_cgpa', 'kt_allowed', 'sem_from', 'sem_to', 'is_exclusive')
  ),
  'specialization_subject' => array(
    'title' => 'Specialization Subject',
    'table' => 'lms_specialization_subject_master',
    'pk' => 'subject_id',
    'formFields' => array(
      array(
        'name' => 'subject_name',
        'label' => 'Subject Name',
        'type' => 'text',
        'required' => true,
        'unique' => true
      ),
      array(
        'name' => 'specialization_id',
        'label' => 'Specialization',
        'type' => 'select',
        'lookupTable' => 'lms_specialization_master',
        'lookupKey' => 'specialization_id',
        'lookupLabel' => 'specialization_name',
        'required' => true
      )
    ),
    'displayFields' => array('subject_name', 'specialization_id')
  ),
  'minor_course' => array(
    'title' => 'Minor Course',
    'table' => 'lms_minorcourse',
    'pk' => 'course_id',
    'formFields' => array(
      array(
        'name' => 'course_name',
        'label' => 'Course Name',
        'type' => 'text',
        'required' => true,
        'unique' => true
      ),
      array(
        'name' => 'course_type',
        'label' => 'Course Type',
        'type' => 'text',
        'required' => true
      ),
      array(
        'name' => 'coordinator',
        'label' => 'Coordinator',
        'type' => 'text'
      ),
      array(
        'name' => 'total_credits',
        'label' => 'Total Credits',
        'type' => 'number',
        'default' => '18'
      )
    ),
    'displayFields' => array('course_name', 'course_type', 'coordinator', 'total_credits')
  ),
  'minor_subject' => array(
    'title' => 'Minor Subject',
    'table' => 'lms_minorsubject',
    'pk' => 'subject_id',
    'formFields' => array(
      array(
        'name' => 'course_id',
        'label' => 'Minor Course',
        'type' => 'select',
        'lookupTable' => 'lms_minorcourse',
        'lookupKey' => 'course_id',
        'lookupLabel' => 'course_name',
        'required' => true
      ),
      array(
        'name' => 'semester_id',
        'label' => 'Semester',
        'type' => 'select',
        'lookupTable' => 'lms_semester_master',
        'lookupKey' => 'semester_id',
        'lookupLabel' => 'semester_name',
        'required' => true
      ),
      array(
        'name' => 'subject_name',
        'label' => 'Subject Name',
        'type' => 'text',
        'required' => true,
        'unique' => true
      ),
      array(
        'name' => 'duration',
        'label' => 'Duration',
        'type' => 'text',
        'default' => '12 weeks'
      ),
      array(
        'name' => 'detail',
        'label' => 'Detail',
        'type' => 'textarea'
      ),
      array(
        'name' => 'credits',
        'label' => 'Credits',
        'type' => 'number',
        'default' => '3'
      )
    ),
    'displayFields' => array('course_id', 'semester_id', 'subject_name', 'duration', 'credits')
  )
);

function clean_master_value($value)
{
  $value = trim((string) $value);
  $value = preg_replace('/\s+/', ' ', $value);
  return $value;
}

function clean_sort_order($value)
{
  $value = trim((string) $value);
  return ($value === '') ? 0 : max(0, intval($value));
}

function clean_route_value($value)
{
  $value = trim((string) $value);
  return ($value === '') ? '#' : $value;
}

function get_menu_icon_options()
{
  return array(
    'fa fa-folder',
    'fa fa-dashboard',
    'fa fa-cogs',
    'fa fa-cog',
    'fa fa-users',
    'fa fa-user',
    'fa fa-user-secret',
    'fa fa-graduation-cap',
    'fa fa-book',
    'fa fa-list-alt',
    'fa fa-info-circle',
    'fa fa-plus-circle',
    'fa fa-minus-circle',
    'fa fa-history',
    'fa fa-calendar',
    'fa fa-file-text',
    'fa fa-edit',
    'fa fa-wrench',
    'fa fa-briefcase',
    'fa fa-building',
    'fa fa-university',
    'fa fa-envelope',
    'fa fa-bell',
    'fa fa-sliders',
    'fa fa-check-circle',
    'fa fa-angle-double-right'
  );
}

function sanitize_icon_class($iconClass, $default)
{
  $iconClass = trim((string) $iconClass);
  if ($iconClass === '') {
    return $default;
  }

  if (!preg_match('/^[a-z0-9\- ]+$/i', $iconClass)) {
    return $default;
  }

  return $iconClass;
}

function get_default_menu_icon($menuName)
{
  $menuName = strtolower(trim((string) $menuName));
  $map = array(
    'students' => 'fa fa-graduation-cap',
    'admin' => 'fa fa-user-secret',
    'coordinator' => 'fa fa-users',
    'mentor' => 'fa fa-user',
    'settings' => 'fa fa-book'
  );

  return isset($map[$menuName]) ? $map[$menuName] : 'fa fa-folder';
}

function get_default_submenu_icon($subMenuName)
{
  $subMenuName = strtolower(trim((string) $subMenuName));
  $map = array(
    'register students' => 'fa fa-plus',
    'list of students' => 'fa fa-info-circle',
    'concise details' => 'fa fa-info-circle',
    'left students' => 'fa fa-minus-circle',
    'previous students' => 'fa fa-history',
    'register admin' => 'fa fa-plus',
    'admin info' => 'fa fa-info-circle',
    'register coordinator' => 'fa fa-plus',
    'coordinator info' => 'fa fa-info-circle',
    'register mentor' => 'fa fa-plus',
    'mentor info' => 'fa fa-info-circle',
    'manage class' => 'fa fa-cogs',
    'manage section' => 'fa fa-list-alt'
  );

  return isset($map[$subMenuName]) ? $map[$subMenuName] : 'fa fa-angle-double-right';
}

function get_default_submenu_route($subMenuName)
{
  $subMenuName = strtolower(trim((string) $subMenuName));
  $map = array(
    'register students' => 'student_admission.php',
    'list of students' => 'student-info.php',
    'concise details' => 'student_concise_details.php',
    'left students' => '#',
    'previous students' => '#',
    'register admin' => 'admin_register.php',
    'admin info' => 'admin_info.php',
    'register coordinator' => 'coordinator_register.php',
    'coordinator info' => 'coordinator_info.php',
    'register mentor' => 'mentor_register.php',
    'mentor info' => 'mentor_info.php',
    'manage class' => 'class_crud_new.php',
    'manage section' => 'class_crud_new.php#section-list'
  );

  return isset($map[$subMenuName]) ? $map[$subMenuName] : '#';
}

function get_lookup_map($conn, $table, $keyColumn, $labelColumn)
{
  $map = array();
  $query = "SELECT $keyColumn AS lookup_id, $labelColumn AS lookup_label FROM $table ORDER BY $labelColumn ASC";
  $result = mysqli_query($conn, $query);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $map[(string) $row['lookup_id']] = (string) $row['lookup_label'];
    }
    mysqli_free_result($result);
  }

  return $map;
}

function render_custom_field_control($prefix, $field, $value, $lookupMaps)
{
  $fieldName = $field['name'];
  $fieldId = $prefix . '_' . $fieldName;
  $fieldLabel = $field['label'];
  $fieldType = $field['type'];
  $requiredAttr = !empty($field['required']) ? ' required' : '';
  $currentValue = (string) $value;

  ob_start();
  if ($fieldType === 'textarea') {
    ?>
    <div class="form-group">
      <label for="<?php echo htmlspecialchars($fieldId); ?>" class="control-label"><?php echo htmlspecialchars($fieldLabel); ?></label>
      <textarea name="<?php echo htmlspecialchars($fieldName); ?>" id="<?php echo htmlspecialchars($fieldId); ?>" class="form-control"<?php echo $requiredAttr; ?>><?php echo htmlspecialchars($currentValue); ?></textarea>
    </div>
    <?php
  } elseif ($fieldType === 'select') {
    $lookupTable = $field['lookupTable'];
    $options = isset($lookupMaps[$lookupTable]) ? $lookupMaps[$lookupTable] : array();
    ?>
    <div class="form-group">
      <label for="<?php echo htmlspecialchars($fieldId); ?>" class="control-label"><?php echo htmlspecialchars($fieldLabel); ?></label>
      <select name="<?php echo htmlspecialchars($fieldName); ?>" id="<?php echo htmlspecialchars($fieldId); ?>" class="form-control"<?php echo $requiredAttr; ?>>
        <option value="">Select <?php echo htmlspecialchars($fieldLabel); ?></option>
        <?php foreach ($options as $optionValue => $optionLabel) { ?>
          <option value="<?php echo htmlspecialchars($optionValue); ?>" <?php echo ((string) $optionValue === $currentValue) ? 'selected' : ''; ?>><?php echo htmlspecialchars($optionLabel); ?></option>
        <?php } ?>
      </select>
    </div>
    <?php
  } elseif ($fieldType === 'boolean_select') {
    ?>
    <div class="form-group">
      <label for="<?php echo htmlspecialchars($fieldId); ?>" class="control-label"><?php echo htmlspecialchars($fieldLabel); ?></label>
      <select name="<?php echo htmlspecialchars($fieldName); ?>" id="<?php echo htmlspecialchars($fieldId); ?>" class="form-control"<?php echo $requiredAttr; ?>>
        <option value="0" <?php echo ($currentValue === '0' || $currentValue === '') ? 'selected' : ''; ?>>No</option>
        <option value="1" <?php echo ($currentValue === '1') ? 'selected' : ''; ?>>Yes</option>
      </select>
    </div>
    <?php
  } else {
    $stepAttr = isset($field['step']) ? ' step="' . htmlspecialchars((string) $field['step']) . '"' : '';
    $inputType = ($fieldType === 'number') ? 'number' : 'text';
    ?>
    <div class="form-group">
      <label for="<?php echo htmlspecialchars($fieldId); ?>" class="control-label"><?php echo htmlspecialchars($fieldLabel); ?></label>
      <input type="<?php echo $inputType; ?>" name="<?php echo htmlspecialchars($fieldName); ?>" id="<?php echo htmlspecialchars($fieldId); ?>" class="form-control" value="<?php echo htmlspecialchars($currentValue); ?>"<?php echo $requiredAttr . $stepAttr; ?>>
    </div>
    <?php
  }

  return ob_get_clean();
}

function prepare_custom_master_value($field, $rawValue)
{
  $fieldType = $field['type'];
  $rawValue = isset($rawValue) ? trim((string) $rawValue) : '';

  if ($fieldType === 'boolean_select') {
    return (int) ($rawValue === '1');
  }

  if ($rawValue === '') {
    if ($fieldType === 'number') {
      return null;
    }

    return '';
  }

  if ($fieldType === 'number') {
    return (strpos((string) ($field['name'] ?? ''), 'cgpa') !== false) ? (float) $rawValue : (int) $rawValue;
  }

  return clean_master_value($rawValue);
}

function custom_master_list_display($row, $field, $lookupMaps)
{
  $fieldName = $field['name'];
  $fieldType = $field['type'];
  $rawValue = isset($row[$fieldName]) ? $row[$fieldName] : '';

  if ($fieldType === 'select') {
    $lookupTable = $field['lookupTable'];
    return isset($lookupMaps[$lookupTable][(string) $rawValue]) ? $lookupMaps[$lookupTable][(string) $rawValue] : '';
  }

  if ($fieldType === 'boolean_select') {
    return ((string) $rawValue === '1') ? 'Yes' : 'No';
  }

  return (string) $rawValue;
}

function get_next_master_id($conn, $table, $pk)
{
  $nextId = 1;
  $query = "SELECT COALESCE(MAX($pk), 0) + 1 AS next_id FROM $table";
  $result = mysqli_query($conn, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row && isset($row['next_id'])) {
      $nextId = intval($row['next_id']);
    }
    mysqli_free_result($result);
  }

  return $nextId;
}

function bind_prepared_statement($stmt, $types, $params)
{
  $bindArgs = array($types);
  foreach ($params as $index => $value) {
    $bindArgs[] = &$params[$index];
  }

  array_unshift($bindArgs, $stmt);
  return call_user_func_array('mysqli_stmt_bind_param', $bindArgs);
}

function ensure_menu_metadata_columns($conn)
{
  $columnChecks = array(
    array(
      'table' => 'lms_menu_master',
      'column' => 'menu_icon',
      'alter' => "ALTER TABLE lms_menu_master ADD COLUMN menu_icon VARCHAR(100) NOT NULL DEFAULT 'fa fa-folder' AFTER menu_name"
    ),
    array(
      'table' => 'lms_sub_menu_master',
      'column' => 'sub_menu_icon',
      'alter' => "ALTER TABLE lms_sub_menu_master ADD COLUMN sub_menu_icon VARCHAR(100) NOT NULL DEFAULT 'fa fa-angle-double-right' AFTER sub_menu_name"
    ),
    array(
      'table' => 'lms_sub_menu_master',
      'column' => 'sub_menu_route',
      'alter' => "ALTER TABLE lms_sub_menu_master ADD COLUMN sub_menu_route VARCHAR(255) NOT NULL DEFAULT '#' AFTER sub_menu_icon"
    )
  );

  foreach ($columnChecks as $check) {
    $table = $check['table'];
    $column = $check['column'];
    $escapedColumn = mysqli_real_escape_string($conn, $column);
    $existsResult = mysqli_query($conn, "SHOW COLUMNS FROM $table LIKE '$escapedColumn'");
    $exists = ($existsResult && mysqli_num_rows($existsResult) > 0);

    if ($existsResult) {
      mysqli_free_result($existsResult);
    }

    if (!$exists) {
      mysqli_query($conn, $check['alter']);
    }
  }

  $menuResult = mysqli_query($conn, "SELECT menu_id, menu_name, menu_icon FROM lms_menu_master");
  if ($menuResult) {
    while ($menuRow = mysqli_fetch_assoc($menuResult)) {
      $menuId = intval($menuRow['menu_id']);
      $menuIcon = trim((string) ($menuRow['menu_icon'] ?? ''));
      if ($menuIcon === '') {
        $menuIcon = get_default_menu_icon($menuRow['menu_name'] ?? '');
        $updateStmt = mysqli_prepare($conn, "UPDATE lms_menu_master SET menu_icon = ? WHERE menu_id = ?");
        if ($updateStmt) {
          mysqli_stmt_bind_param($updateStmt, 'si', $menuIcon, $menuId);
          mysqli_stmt_execute($updateStmt);
          mysqli_stmt_close($updateStmt);
        }
      }
    }
    mysqli_free_result($menuResult);
  }

  $subMenuResult = mysqli_query($conn, "SELECT sub_menu_id, sub_menu_name, sub_menu_icon, sub_menu_route FROM lms_sub_menu_master");
  if ($subMenuResult) {
    while ($subMenuRow = mysqli_fetch_assoc($subMenuResult)) {
      $subMenuId = intval($subMenuRow['sub_menu_id']);
      $subMenuIcon = trim((string) ($subMenuRow['sub_menu_icon'] ?? ''));
      $subMenuRoute = trim((string) ($subMenuRow['sub_menu_route'] ?? ''));
      $needsUpdate = false;

      if ($subMenuIcon === '') {
        $subMenuIcon = get_default_submenu_icon($subMenuRow['sub_menu_name'] ?? '');
        $needsUpdate = true;
      }
      if ($subMenuRoute === '') {
        $subMenuRoute = get_default_submenu_route($subMenuRow['sub_menu_name'] ?? '');
        $needsUpdate = true;
      }

      if ($needsUpdate) {
        $updateStmt = mysqli_prepare($conn, "UPDATE lms_sub_menu_master SET sub_menu_icon = ?, sub_menu_route = ? WHERE sub_menu_id = ?");
        if ($updateStmt) {
          mysqli_stmt_bind_param($updateStmt, 'ssi', $subMenuIcon, $subMenuRoute, $subMenuId);
          mysqli_stmt_execute($updateStmt);
          mysqli_stmt_close($updateStmt);
        }
      }
    }
    mysqli_free_result($subMenuResult);
  }
}

function ensure_parent_menu_allocation_for_roles($conn, $menuId)
{
  $roleIds = array(1, 2, 3, 4);
  foreach ($roleIds as $roleId) {
    $checkSql = "SELECT 1 FROM lms_menu_allocation_master WHERE user_id = 0 AND role_id = ? AND menu_id = ? AND sub_menu_id IS NULL LIMIT 1";
    $checkStmt = mysqli_prepare($conn, $checkSql);

    if ($checkStmt) {
      mysqli_stmt_bind_param($checkStmt, 'ii', $roleId, $menuId);
      mysqli_stmt_execute($checkStmt);
      $checkResult = mysqli_stmt_get_result($checkStmt);
      $exists = ($checkResult && mysqli_num_rows($checkResult) > 0);
      mysqli_stmt_close($checkStmt);

      if (!$exists) {
        $insertSql = "INSERT INTO lms_menu_allocation_master (user_id, role_id, menu_id, sub_menu_id) VALUES (0, ?, ?, NULL)";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        if ($insertStmt) {
          mysqli_stmt_bind_param($insertStmt, 'ii', $roleId, $menuId);
          mysqli_stmt_execute($insertStmt);
          mysqli_stmt_close($insertStmt);
        }
      }
    }
  }
}

function ensure_sub_menu_allocation_for_roles($conn, $menuId, $subMenuId)
{
  $roleIds = array(1, 2, 3, 4);
  ensure_parent_menu_allocation_for_roles($conn, $menuId);

  foreach ($roleIds as $roleId) {
    $checkSql = "SELECT 1 FROM lms_menu_allocation_master WHERE user_id = 0 AND role_id = ? AND menu_id = ? AND sub_menu_id = ? LIMIT 1";
    $checkStmt = mysqli_prepare($conn, $checkSql);

    if ($checkStmt) {
      mysqli_stmt_bind_param($checkStmt, 'iii', $roleId, $menuId, $subMenuId);
      mysqli_stmt_execute($checkStmt);
      $checkResult = mysqli_stmt_get_result($checkStmt);
      $exists = ($checkResult && mysqli_num_rows($checkResult) > 0);
      mysqli_stmt_close($checkStmt);

      if (!$exists) {
        $insertSql = "INSERT INTO lms_menu_allocation_master (user_id, role_id, menu_id, sub_menu_id) VALUES (0, ?, ?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        if ($insertStmt) {
          mysqli_stmt_bind_param($insertStmt, 'iii', $roleId, $menuId, $subMenuId);
          mysqli_stmt_execute($insertStmt);
          mysqli_stmt_close($insertStmt);
        }
      }
    }
  }
}

function normalize_sub_menu_sequence_by_menu($conn)
{
  $menuIds = array();
  $menuResult = mysqli_query($conn, "SELECT menu_id FROM lms_menu_master ORDER BY menu_id ASC");
  if ($menuResult) {
    while ($menuRow = mysqli_fetch_assoc($menuResult)) {
      $menuId = (int) ($menuRow['menu_id'] ?? 0);
      if ($menuId > 0) {
        $menuIds[] = $menuId;
      }
    }
    mysqli_free_result($menuResult);
  }

  foreach ($menuIds as $menuId) {
    $subSql = "SELECT sub_menu_id, COALESCE(sort_order, 0) AS sort_order
               FROM lms_sub_menu_master
               WHERE menu_id = ?
               ORDER BY sort_order ASC, sub_menu_id ASC";
    $subStmt = mysqli_prepare($conn, $subSql);
    if (!$subStmt) {
      continue;
    }

    mysqli_stmt_bind_param($subStmt, 'i', $menuId);
    mysqli_stmt_execute($subStmt);
    $subResult = mysqli_stmt_get_result($subStmt);

    $expectedOrder = 1;
    while ($subResult && ($subRow = mysqli_fetch_assoc($subResult))) {
      $subMenuId = (int) ($subRow['sub_menu_id'] ?? 0);
      $currentOrder = (int) ($subRow['sort_order'] ?? 0);

      if ($subMenuId > 0 && $currentOrder !== $expectedOrder) {
        $updateSql = "UPDATE lms_sub_menu_master SET sort_order = ? WHERE sub_menu_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        if ($updateStmt) {
          mysqli_stmt_bind_param($updateStmt, 'ii', $expectedOrder, $subMenuId);
          mysqli_stmt_execute($updateStmt);
          mysqli_stmt_close($updateStmt);
        }
      }

      $expectedOrder++;
    }

    mysqli_stmt_close($subStmt);
  }
}

$activeTab = 'class-list';
$alertType = '';
$alertMessage = '';
$openAddModalType = '';
$openAddSubMenuModal = false;
$shouldSyncSidebar = false;
$ajaxResponse = null;
$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

ensure_menu_metadata_columns($db_handle->conn);

$availableMenuIcons = get_menu_icon_options();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['master_action'], $_POST['master_type'])) {
  $masterType = $_POST['master_type'];
  $action = $_POST['master_action'];

  if (isset($masters[$masterType])) {
    $meta = $masters[$masterType];
    $table = $meta['table'];
    $pk = $meta['pk'];
    $nameCol = $meta['name'];
    $title = $meta['title'];

    if ($action === 'add') {
      $name = clean_master_value($_POST['master_name'] ?? '');
      $menuIcon = sanitize_icon_class($_POST['menu_icon'] ?? '', get_default_menu_icon($name));
      $activeTab = $masterType . '-add';
      $openAddModalType = $masterType;

      if ($name === '') {
        $alertType = 'warning';
        $alertMessage = $title . ' name is required.';
      } else {
        $dupSql = "SELECT COUNT(*) AS cnt FROM $table WHERE LOWER(TRIM($nameCol)) = LOWER(TRIM(?))";
        $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);

        if ($dupStmt) {
          mysqli_stmt_bind_param($dupStmt, 's', $name);
          mysqli_stmt_execute($dupStmt);
          $dupResult = mysqli_stmt_get_result($dupStmt);
          $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
          mysqli_stmt_close($dupStmt);

          if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
            $alertType = 'warning';
            $alertMessage = $title . ' already exists.';
          } else {
            if ($masterType === 'menu') {
              $insertSql = "INSERT INTO $table ($nameCol, menu_icon) VALUES (?, ?)";
            } else {
              $insertSql = "INSERT INTO $table ($nameCol) VALUES (?)";
            }
            $insertStmt = mysqli_prepare($db_handle->conn, $insertSql);

            if ($insertStmt) {
              if ($masterType === 'menu') {
                mysqli_stmt_bind_param($insertStmt, 'ss', $name, $menuIcon);
              } else {
                mysqli_stmt_bind_param($insertStmt, 's', $name);
              }
              $ok = mysqli_stmt_execute($insertStmt);
              mysqli_stmt_close($insertStmt);

              if ($ok) {
                $alertType = 'success';
                $alertMessage = $title . ' added successfully.';
                $activeTab = $masterType . '-list';
                $openAddModalType = '';
                $shouldSyncSidebar = true;
                $insertedId = mysqli_insert_id($db_handle->conn);

                if ($masterType === 'menu') {
                  if ($insertedId > 0) {
                    ensure_parent_menu_allocation_for_roles($db_handle->conn, $insertedId);
                  }
                }

                if ($isAjaxRequest) {
                  $ajaxResponse = array(
                    'status' => 'success',
                    'message' => $alertMessage,
                    'master_type' => $masterType,
                    'master_id' => $insertedId,
                    'master_name' => $name,
                    'menu_icon' => $menuIcon
                  );
                }
              } else {
                $alertType = 'danger';
                $alertMessage = 'Unable to add ' . strtolower($title) . '.';
                if ($isAjaxRequest) {
                  $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
                }
              }
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to prepare add statement for ' . strtolower($title) . '.';
              if ($isAjaxRequest) {
                $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
              }
            }
          }
        } else {
          $alertType = 'danger';
          $alertMessage = 'Unable to validate duplicate ' . strtolower($title) . '.';
          if ($isAjaxRequest) {
            $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
          }
        }
      }
    } elseif ($action === 'update') {
      $id = intval($_POST['master_id'] ?? 0);
      $name = clean_master_value($_POST['master_name'] ?? '');
      $menuIcon = sanitize_icon_class($_POST['menu_icon'] ?? '', get_default_menu_icon($name));
      $activeTab = $masterType . '-list';

      if ($id <= 0 || $name === '') {
        $alertType = 'warning';
        $alertMessage = 'Valid ' . strtolower($title) . ' details are required for update.';
      } else {
        $dupSql = "SELECT COUNT(*) AS cnt FROM $table WHERE LOWER(TRIM($nameCol)) = LOWER(TRIM(?)) AND $pk <> ?";
        $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);

        if ($dupStmt) {
          mysqli_stmt_bind_param($dupStmt, 'si', $name, $id);
          mysqli_stmt_execute($dupStmt);
          $dupResult = mysqli_stmt_get_result($dupStmt);
          $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
          mysqli_stmt_close($dupStmt);

          if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
            $alertType = 'warning';
            $alertMessage = $title . ' already exists.';
          } else {
            if ($masterType === 'menu') {
              $updateSql = "UPDATE $table SET $nameCol = ?, menu_icon = ? WHERE $pk = ?";
            } else {
              $updateSql = "UPDATE $table SET $nameCol = ? WHERE $pk = ?";
            }
            $updateStmt = mysqli_prepare($db_handle->conn, $updateSql);

            if ($updateStmt) {
              if ($masterType === 'menu') {
                mysqli_stmt_bind_param($updateStmt, 'ssi', $name, $menuIcon, $id);
              } else {
                mysqli_stmt_bind_param($updateStmt, 'si', $name, $id);
              }
              $ok = mysqli_stmt_execute($updateStmt);
              mysqli_stmt_close($updateStmt);

              if ($ok) {
                $alertType = 'success';
                $alertMessage = $title . ' updated successfully.';
                $shouldSyncSidebar = true;
              } else {
                $alertType = 'danger';
                $alertMessage = 'Unable to update ' . strtolower($title) . '.';
              }
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to prepare update statement for ' . strtolower($title) . '.';
            }
          }
        } else {
          $alertType = 'danger';
          $alertMessage = 'Unable to validate duplicate ' . strtolower($title) . ' before update.';
        }
      }
    } elseif ($action === 'delete') {
      $id = intval($_POST['master_id'] ?? 0);
      $activeTab = $masterType . '-list';

      if ($id <= 0) {
        $alertType = 'warning';
        $alertMessage = 'Invalid ' . strtolower($title) . ' selected for delete.';
      } else {
        if ($masterType === 'menu') {
          mysqli_begin_transaction($db_handle->conn);

          $ok = true;

          $deleteAllocBySubSql = "DELETE ma
                                 FROM lms_menu_allocation_master ma
                                 INNER JOIN lms_sub_menu_master sm ON sm.sub_menu_id = ma.sub_menu_id
                                 WHERE sm.menu_id = ?";
          $deleteAllocBySubStmt = mysqli_prepare($db_handle->conn, $deleteAllocBySubSql);
          if ($deleteAllocBySubStmt) {
            mysqli_stmt_bind_param($deleteAllocBySubStmt, 'i', $id);
            $ok = $ok && mysqli_stmt_execute($deleteAllocBySubStmt);
            mysqli_stmt_close($deleteAllocBySubStmt);
          } else {
            $ok = false;
          }

          if ($ok) {
            $deleteAllocSql = "DELETE FROM lms_menu_allocation_master WHERE menu_id = ?";
            $deleteAllocStmt = mysqli_prepare($db_handle->conn, $deleteAllocSql);
            if ($deleteAllocStmt) {
              mysqli_stmt_bind_param($deleteAllocStmt, 'i', $id);
              $ok = $ok && mysqli_stmt_execute($deleteAllocStmt);
              mysqli_stmt_close($deleteAllocStmt);
            } else {
              $ok = false;
            }
          }

          if ($ok) {
            $deleteSubMenuSql = "DELETE FROM lms_sub_menu_master WHERE menu_id = ?";
            $deleteSubMenuStmt = mysqli_prepare($db_handle->conn, $deleteSubMenuSql);
            if ($deleteSubMenuStmt) {
              mysqli_stmt_bind_param($deleteSubMenuStmt, 'i', $id);
              $ok = $ok && mysqli_stmt_execute($deleteSubMenuStmt);
              mysqli_stmt_close($deleteSubMenuStmt);
            } else {
              $ok = false;
            }
          }

          if ($ok) {
            $deleteMenuSql = "DELETE FROM lms_menu_master WHERE menu_id = ?";
            $deleteMenuStmt = mysqli_prepare($db_handle->conn, $deleteMenuSql);
            if ($deleteMenuStmt) {
              mysqli_stmt_bind_param($deleteMenuStmt, 'i', $id);
              $ok = $ok && mysqli_stmt_execute($deleteMenuStmt);
              mysqli_stmt_close($deleteMenuStmt);
            } else {
              $ok = false;
            }
          }

          if ($ok) {
            mysqli_commit($db_handle->conn);
            $alertType = 'success';
            $alertMessage = $title . ' deleted successfully.';
            $shouldSyncSidebar = true;
            if ($isAjaxRequest) {
              $ajaxResponse = array('status' => 'success', 'message' => $alertMessage, 'master_type' => $masterType, 'master_id' => $id);
            }
          } else {
            mysqli_rollback($db_handle->conn);
            $alertType = 'danger';
            $alertMessage = 'Unable to delete ' . strtolower($title) . '. It may be in use.';
            if ($isAjaxRequest) {
              $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
            }
          }
        } else {
          $deleteSql = "DELETE FROM $table WHERE $pk = ?";
          $deleteStmt = mysqli_prepare($db_handle->conn, $deleteSql);

          if ($deleteStmt) {
            mysqli_stmt_bind_param($deleteStmt, 'i', $id);
            $ok = mysqli_stmt_execute($deleteStmt);
            mysqli_stmt_close($deleteStmt);

            if ($ok) {
              $alertType = 'success';
              $alertMessage = $title . ' deleted successfully.';
              $shouldSyncSidebar = true;
              if ($isAjaxRequest) {
                $ajaxResponse = array('status' => 'success', 'message' => $alertMessage, 'master_type' => $masterType, 'master_id' => $id);
              }
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to delete ' . strtolower($title) . '. It may be in use.';
              if ($isAjaxRequest) {
                $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
              }
            }
          } else {
            $alertType = 'danger';
            $alertMessage = 'Unable to prepare delete statement for ' . strtolower($title) . '.';
            if ($isAjaxRequest) {
              $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
            }
          }
        }
      }
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_master_action'], $_POST['custom_master_type'])) {
  $customMasterType = $_POST['custom_master_type'];
  $customMasterAction = $_POST['custom_master_action'];

  if (isset($customMasters[$customMasterType])) {
    $meta = $customMasters[$customMasterType];
    $table = $meta['table'];
    $pk = $meta['pk'];
    $formFields = $meta['formFields'];
    $activeTab = $customMasterType . '-list';

    $values = array();
    $types = '';
    $columns = array();
    $placeholders = array();
    $setClauses = array();
    $uniqueField = null;
    $uniqueValue = null;
    $validationFailed = false;

    foreach ($formFields as $field) {
      $fieldName = $field['name'];
      $rawValue = $_POST[$fieldName] ?? '';
      if ($rawValue === '' && isset($field['default'])) {
        $rawValue = $field['default'];
      }

      $value = prepare_custom_master_value($field, $rawValue);
      if ($customMasterAction !== 'delete' && !empty($field['required']) && ($value === '' || $value === null)) {
        $alertType = 'warning';
        $alertMessage = $meta['title'] . ' fields are required.';
        $validationFailed = true;
        break;
      }

      if (!empty($field['unique']) && $uniqueField === null) {
        $uniqueField = $fieldName;
        $uniqueValue = $value;
      }

      $values[] = $value;
      $columns[] = $fieldName;
      $placeholders[] = '?';
      $setClauses[] = $fieldName . ' = ?';

      if ($field['type'] === 'boolean_select') {
        $types .= 'i';
      } elseif ($field['type'] === 'number') {
        $types .= (strpos($fieldName, 'cgpa') !== false) ? 'd' : 'i';
      } else {
        $types .= 's';
      }
    }

    if (!$validationFailed && $customMasterAction === 'add') {
      if (!in_array($pk, $columns, true)) {
        $nextId = get_next_master_id($db_handle->conn, $table, $pk);
        array_unshift($columns, $pk);
        array_unshift($values, $nextId);
        array_unshift($placeholders, '?');
        $types = 'i' . $types;
      }

      if ($uniqueField !== null && $uniqueValue !== null && $uniqueValue !== '') {
        $dupSql = "SELECT COUNT(*) AS cnt FROM $table WHERE LOWER(TRIM($uniqueField)) = LOWER(TRIM(?))";
        $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);
        if ($dupStmt) {
          mysqli_stmt_bind_param($dupStmt, 's', $uniqueValue);
          mysqli_stmt_execute($dupStmt);
          $dupResult = mysqli_stmt_get_result($dupStmt);
          $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
          mysqli_stmt_close($dupStmt);

          if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
            $alertType = 'warning';
            $alertMessage = $meta['title'] . ' already exists.';
          } else {
            $insertSql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $insertStmt = mysqli_prepare($db_handle->conn, $insertSql);
            if ($insertStmt) {
              bind_prepared_statement($insertStmt, $types, $values);
              $ok = mysqli_stmt_execute($insertStmt);
              mysqli_stmt_close($insertStmt);

              if ($ok) {
                $alertType = 'success';
                $alertMessage = $meta['title'] . ' added successfully.';
              } else {
                $alertType = 'danger';
                $alertMessage = 'Unable to add ' . strtolower($meta['title']) . '.';
              }
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to prepare add statement for ' . strtolower($meta['title']) . '.';
            }
          }
        }
      } else {
        $insertSql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $insertStmt = mysqli_prepare($db_handle->conn, $insertSql);
        if ($insertStmt) {
          bind_prepared_statement($insertStmt, $types, $values);
          $ok = mysqli_stmt_execute($insertStmt);
          mysqli_stmt_close($insertStmt);

          if ($ok) {
            $alertType = 'success';
            $alertMessage = $meta['title'] . ' added successfully.';
          } else {
            $alertType = 'danger';
            $alertMessage = 'Unable to add ' . strtolower($meta['title']) . '.';
          }
        } else {
          $alertType = 'danger';
          $alertMessage = 'Unable to prepare add statement for ' . strtolower($meta['title']) . '.';
        }
      }
    } elseif (!$validationFailed && $customMasterAction === 'update') {
      $customMasterId = intval($_POST['custom_master_id'] ?? 0);
      if ($customMasterId <= 0) {
        $alertType = 'warning';
        $alertMessage = 'Invalid ' . strtolower($meta['title']) . ' selected for update.';
      } else {
        if ($uniqueField !== null && $uniqueValue !== null && $uniqueValue !== '') {
          $dupSql = "SELECT COUNT(*) AS cnt FROM $table WHERE LOWER(TRIM($uniqueField)) = LOWER(TRIM(?)) AND $pk <> ?";
          $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);
          if ($dupStmt) {
            mysqli_stmt_bind_param($dupStmt, 'si', $uniqueValue, $customMasterId);
            mysqli_stmt_execute($dupStmt);
            $dupResult = mysqli_stmt_get_result($dupStmt);
            $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
            mysqli_stmt_close($dupStmt);

            if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
              $alertType = 'warning';
              $alertMessage = $meta['title'] . ' already exists.';
            } else {
              $updateSql = "UPDATE $table SET " . implode(', ', $setClauses) . " WHERE $pk = ?";
              $updateStmt = mysqli_prepare($db_handle->conn, $updateSql);
              if ($updateStmt) {
                $updateValues = $values;
                $updateValues[] = $customMasterId;
                $updateTypes = $types . 'i';
                bind_prepared_statement($updateStmt, $updateTypes, $updateValues);
                $ok = mysqli_stmt_execute($updateStmt);
                mysqli_stmt_close($updateStmt);

                if ($ok) {
                  $alertType = 'success';
                  $alertMessage = $meta['title'] . ' updated successfully.';
                } else {
                  $alertType = 'danger';
                  $alertMessage = 'Unable to update ' . strtolower($meta['title']) . '.';
                }
              } else {
                $alertType = 'danger';
                $alertMessage = 'Unable to prepare update statement for ' . strtolower($meta['title']) . '.';
              }
            }
          }
        } else {
          $updateSql = "UPDATE $table SET " . implode(', ', $setClauses) . " WHERE $pk = ?";
          $updateStmt = mysqli_prepare($db_handle->conn, $updateSql);
          if ($updateStmt) {
            $updateValues = $values;
            $updateValues[] = $customMasterId;
            $updateTypes = $types . 'i';
            bind_prepared_statement($updateStmt, $updateTypes, $updateValues);
            $ok = mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            if ($ok) {
              $alertType = 'success';
              $alertMessage = $meta['title'] . ' updated successfully.';
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to update ' . strtolower($meta['title']) . '.';
            }
          } else {
            $alertType = 'danger';
            $alertMessage = 'Unable to prepare update statement for ' . strtolower($meta['title']) . '.';
          }
        }
      }
    } elseif (!$validationFailed && $customMasterAction === 'delete') {
      $customMasterId = intval($_POST['custom_master_id'] ?? 0);
      if ($customMasterId <= 0) {
        $alertType = 'warning';
        $alertMessage = 'Invalid ' . strtolower($meta['title']) . ' selected for delete.';
      } else {
        mysqli_begin_transaction($db_handle->conn);

        $deleteChildrenOk = true;
        $childDeleteError = '';

        if ($customMasterType === 'specialization') {
          $childSql = "DELETE FROM lms_specialization_subject_master WHERE specialization_id = ?";
          $childStmt = mysqli_prepare($db_handle->conn, $childSql);
          if ($childStmt) {
            mysqli_stmt_bind_param($childStmt, 'i', $customMasterId);
            $deleteChildrenOk = mysqli_stmt_execute($childStmt);
            if (!$deleteChildrenOk) {
              $childDeleteError = mysqli_stmt_error($childStmt);
            }
            mysqli_stmt_close($childStmt);
          } else {
            $deleteChildrenOk = false;
            $childDeleteError = mysqli_error($db_handle->conn);
          }
        } elseif ($customMasterType === 'minor_course') {
          $childSql = "DELETE FROM lms_minorsubject WHERE course_id = ?";
          $childStmt = mysqli_prepare($db_handle->conn, $childSql);
          if ($childStmt) {
            mysqli_stmt_bind_param($childStmt, 'i', $customMasterId);
            $deleteChildrenOk = mysqli_stmt_execute($childStmt);
            if (!$deleteChildrenOk) {
              $childDeleteError = mysqli_stmt_error($childStmt);
            }
            mysqli_stmt_close($childStmt);
          } else {
            $deleteChildrenOk = false;
            $childDeleteError = mysqli_error($db_handle->conn);
          }
        }

        if ($deleteChildrenOk) {
          $deleteSql = "DELETE FROM $table WHERE $pk = ?";
          $deleteStmt = mysqli_prepare($db_handle->conn, $deleteSql);
          if ($deleteStmt) {
            mysqli_stmt_bind_param($deleteStmt, 'i', $customMasterId);
            $ok = mysqli_stmt_execute($deleteStmt);
            $affectedRows = mysqli_stmt_affected_rows($deleteStmt);
            mysqli_stmt_close($deleteStmt);

            if ($ok && $affectedRows > 0) {
              mysqli_commit($db_handle->conn);
              $alertType = 'success';
              $alertMessage = $meta['title'] . ' deleted successfully.';
            } else {
              mysqli_rollback($db_handle->conn);
              $alertType = 'danger';
              $alertMessage = 'Unable to delete ' . strtolower($meta['title']) . '. It may still be used by student records.';
            }
          } else {
            mysqli_rollback($db_handle->conn);
            $alertType = 'danger';
            $alertMessage = 'Unable to prepare delete statement for ' . strtolower($meta['title']) . '.';
          }
        } else {
          mysqli_rollback($db_handle->conn);
          $alertType = 'danger';
          $alertMessage = 'Unable to delete ' . strtolower($meta['title']) . '. ' . ($childDeleteError !== '' ? $childDeleteError : 'Related rows could not be removed.');
        }
      }
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sub_menu_action'])) {
  $subMenuAction = $_POST['sub_menu_action'];
  $activeTab = 'sub-menu-list';

  if ($subMenuAction === 'add') {
    $menuId = intval($_POST['menu_id'] ?? 0);
    $subMenuName = clean_master_value($_POST['sub_menu_name'] ?? '');
    $sortOrder = clean_sort_order($_POST['sort_order'] ?? 0);
    $subMenuRoute = clean_route_value($_POST['sub_menu_route'] ?? '#');
    $subMenuIcon = sanitize_icon_class($_POST['sub_menu_icon'] ?? '', get_default_submenu_icon($subMenuName));
    $openAddSubMenuModal = true;

    if ($menuId <= 0 || $subMenuName === '') {
      $alertType = 'warning';
      $alertMessage = 'Menu and sub menu name are required.';
    } else {
      $dupSql = "SELECT COUNT(*) AS cnt FROM lms_sub_menu_master WHERE menu_id = ? AND LOWER(TRIM(sub_menu_name)) = LOWER(TRIM(?))";
      $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);

      if ($dupStmt) {
        mysqli_stmt_bind_param($dupStmt, 'is', $menuId, $subMenuName);
        mysqli_stmt_execute($dupStmt);
        $dupResult = mysqli_stmt_get_result($dupStmt);
        $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
        mysqli_stmt_close($dupStmt);

        if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
          $alertType = 'warning';
          $alertMessage = 'Sub menu already exists under selected menu.';
        } else {
          if ($sortOrder <= 0) {
            $sortSql = "SELECT COALESCE(MAX(sort_order), 0) + 1 AS next_order FROM lms_sub_menu_master WHERE menu_id = ?";
            $sortStmt = mysqli_prepare($db_handle->conn, $sortSql);
            if ($sortStmt) {
              mysqli_stmt_bind_param($sortStmt, 'i', $menuId);
              mysqli_stmt_execute($sortStmt);
              $sortResult = mysqli_stmt_get_result($sortStmt);
              $sortRow = $sortResult ? mysqli_fetch_assoc($sortResult) : array('next_order' => 1);
              mysqli_stmt_close($sortStmt);
              $sortOrder = intval($sortRow['next_order']);
            }
          }

          $insertSql = "INSERT INTO lms_sub_menu_master (menu_id, sub_menu_name, sort_order, sub_menu_icon, sub_menu_route) VALUES (?, ?, ?, ?, ?)";
          $insertStmt = mysqli_prepare($db_handle->conn, $insertSql);

          if ($insertStmt) {
            mysqli_stmt_bind_param($insertStmt, 'isiss', $menuId, $subMenuName, $sortOrder, $subMenuIcon, $subMenuRoute);
            $ok = mysqli_stmt_execute($insertStmt);
            mysqli_stmt_close($insertStmt);

            if ($ok) {
              $newSubMenuId = mysqli_insert_id($db_handle->conn);
              ensure_sub_menu_allocation_for_roles($db_handle->conn, $menuId, $newSubMenuId);

              $alertType = 'success';
              $alertMessage = 'Sub menu added successfully.';
              $openAddSubMenuModal = false;
              $shouldSyncSidebar = true;
              if ($isAjaxRequest) {
                $menuNameSql = $db_handle->conn->prepare("SELECT menu_name FROM lms_menu_master WHERE menu_id = ?");
                $menuName = '';
                if ($menuNameSql) {
                  $menuNameSql->bind_param('i', $menuId);
                  $menuNameSql->execute();
                  $menuNameResult = $menuNameSql->get_result();
                  if ($menuNameResult && ($menuNameRow = $menuNameResult->fetch_assoc())) {
                    $menuName = $menuNameRow['menu_name'];
                  }
                  $menuNameSql->close();
                }

                $ajaxResponse = array(
                  'status' => 'success',
                  'message' => $alertMessage,
                  'sub_menu_id' => $newSubMenuId,
                  'menu_id' => $menuId,
                  'menu_name' => $menuName,
                  'sub_menu_name' => $subMenuName,
                  'sort_order' => $sortOrder,
                  'sub_menu_icon' => $subMenuIcon,
                  'sub_menu_route' => $subMenuRoute
                );
              }
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to add sub menu.';
              if ($isAjaxRequest) {
                $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
              }
            }
          } else {
            $alertType = 'danger';
            $alertMessage = 'Unable to prepare add statement for sub menu.';
            if ($isAjaxRequest) {
              $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
            }
          }
        }
      } else {
        $alertType = 'danger';
        $alertMessage = 'Unable to validate duplicate sub menu.';
        if ($isAjaxRequest) {
          $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
        }
      }
    }
  } elseif ($subMenuAction === 'update') {
    $subMenuId = intval($_POST['sub_menu_id'] ?? 0);
    $menuId = intval($_POST['menu_id'] ?? 0);
    $subMenuName = clean_master_value($_POST['sub_menu_name'] ?? '');
    $sortOrder = clean_sort_order($_POST['sort_order'] ?? 0);
    $subMenuRoute = clean_route_value($_POST['sub_menu_route'] ?? '#');
    $subMenuIcon = sanitize_icon_class($_POST['sub_menu_icon'] ?? '', get_default_submenu_icon($subMenuName));

    if ($subMenuId <= 0 || $menuId <= 0 || $subMenuName === '') {
      $alertType = 'warning';
      $alertMessage = 'Valid sub menu details are required for update.';
    } else {
      $dupSql = "SELECT COUNT(*) AS cnt FROM lms_sub_menu_master WHERE menu_id = ? AND LOWER(TRIM(sub_menu_name)) = LOWER(TRIM(?)) AND sub_menu_id <> ?";
      $dupStmt = mysqli_prepare($db_handle->conn, $dupSql);

      if ($dupStmt) {
        mysqli_stmt_bind_param($dupStmt, 'isi', $menuId, $subMenuName, $subMenuId);
        mysqli_stmt_execute($dupStmt);
        $dupResult = mysqli_stmt_get_result($dupStmt);
        $dupRow = $dupResult ? mysqli_fetch_assoc($dupResult) : array('cnt' => 0);
        mysqli_stmt_close($dupStmt);

        if (!empty($dupRow) && intval($dupRow['cnt']) > 0) {
          $alertType = 'warning';
          $alertMessage = 'Sub menu already exists under selected menu.';
        } else {
          $updateSql = "UPDATE lms_sub_menu_master SET menu_id = ?, sub_menu_name = ?, sort_order = ?, sub_menu_icon = ?, sub_menu_route = ? WHERE sub_menu_id = ?";
          $updateStmt = mysqli_prepare($db_handle->conn, $updateSql);

          if ($updateStmt) {
            mysqli_stmt_bind_param($updateStmt, 'isissi', $menuId, $subMenuName, $sortOrder, $subMenuIcon, $subMenuRoute, $subMenuId);
            $ok = mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            if ($ok) {
              $syncSql = "UPDATE lms_menu_allocation_master SET menu_id = ? WHERE sub_menu_id = ?";
              $syncStmt = mysqli_prepare($db_handle->conn, $syncSql);
              if ($syncStmt) {
                mysqli_stmt_bind_param($syncStmt, 'ii', $menuId, $subMenuId);
                mysqli_stmt_execute($syncStmt);
                mysqli_stmt_close($syncStmt);
              }

              ensure_sub_menu_allocation_for_roles($db_handle->conn, $menuId, $subMenuId);
              $alertType = 'success';
              $alertMessage = 'Sub menu updated successfully.';
              $shouldSyncSidebar = true;
            } else {
              $alertType = 'danger';
              $alertMessage = 'Unable to update sub menu.';
            }
          } else {
            $alertType = 'danger';
            $alertMessage = 'Unable to prepare update statement for sub menu.';
          }
        }
      } else {
        $alertType = 'danger';
        $alertMessage = 'Unable to validate duplicate sub menu before update.';
      }
    }
  } elseif ($subMenuAction === 'delete') {
    $subMenuId = intval($_POST['sub_menu_id'] ?? 0);

    if ($subMenuId <= 0) {
      $alertType = 'warning';
      $alertMessage = 'Invalid sub menu selected for delete.';
        if ($isAjaxRequest) {
          $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
        }
    } else {
      $allocDeleteSql = "DELETE FROM lms_menu_allocation_master WHERE sub_menu_id = ?";
      $allocDeleteStmt = mysqli_prepare($db_handle->conn, $allocDeleteSql);
      if ($allocDeleteStmt) {
        mysqli_stmt_bind_param($allocDeleteStmt, 'i', $subMenuId);
        mysqli_stmt_execute($allocDeleteStmt);
        mysqli_stmt_close($allocDeleteStmt);
      }

      $deleteSql = "DELETE FROM lms_sub_menu_master WHERE sub_menu_id = ?";
      $deleteStmt = mysqli_prepare($db_handle->conn, $deleteSql);

      if ($deleteStmt) {
        mysqli_stmt_bind_param($deleteStmt, 'i', $subMenuId);
        $ok = mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt);

        if ($ok) {
          $alertType = 'success';
          $alertMessage = 'Sub menu deleted successfully.';
          $shouldSyncSidebar = true;
            if ($isAjaxRequest) {
              $ajaxResponse = array('status' => 'success', 'message' => $alertMessage, 'sub_menu_id' => $subMenuId);
            }
        } else {
          $alertType = 'danger';
          $alertMessage = 'Unable to delete sub menu.';
            if ($isAjaxRequest) {
              $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
            }
        }
      } else {
        $alertType = 'danger';
        $alertMessage = 'Unable to prepare delete statement for sub menu.';
          if ($isAjaxRequest) {
            $ajaxResponse = array('status' => 'error', 'message' => $alertMessage);
          }
      }
    }
  }
}

if (isset($_GET['tab'])) {
  $requestedTab = trim($_GET['tab']);
  if ($requestedTab !== '') {
    $activeTab = $requestedTab;
  }
}

if ($isAjaxRequest && $ajaxResponse !== null) {
  if (ob_get_length()) {
    ob_clean();
  }
  header('Content-Type: application/json');
  echo json_encode($ajaxResponse);
  exit();
}

normalize_sub_menu_sequence_by_menu($db_handle->conn);

$customLookupMaps = array(
  'lms_specialization_master' => get_lookup_map($db_handle->conn, 'lms_specialization_master', 'specialization_id', 'specialization_name'),
  'lms_minorcourse' => get_lookup_map($db_handle->conn, 'lms_minorcourse', 'course_id', 'course_name'),
  'lms_semester_master' => get_lookup_map($db_handle->conn, 'lms_semester_master', 'semester_id', 'semester_name'),
  'lms_batch_master' => get_lookup_map($db_handle->conn, 'lms_batch_master', 'batch_id', 'batch_name'),
  'lms_session_master' => get_lookup_map($db_handle->conn, 'lms_session_master', 'session_id', 'session_name')
);

$customMasterRows = array();
foreach ($customMasters as $type => $meta) {
  $table = $meta['table'];
  $pk = $meta['pk'];
  $result = $db_handle->conn->query("SELECT * FROM $table ORDER BY $pk DESC");
  $rows = array();

  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
  }

  $customMasterRows[$type] = $rows;
}

$customEditType = isset($_GET['custom_edit_type']) ? trim($_GET['custom_edit_type']) : '';
$customEditId = isset($_GET['custom_edit_id']) ? intval($_GET['custom_edit_id']) : 0;
$customEditRow = array();

if ($customEditType !== '' && $customEditId > 0 && isset($customMasters[$customEditType])) {
  $meta = $customMasters[$customEditType];
  $table = $meta['table'];
  $pk = $meta['pk'];
  $editStmt = mysqli_prepare($db_handle->conn, "SELECT * FROM $table WHERE $pk = ? LIMIT 1");
  if ($editStmt) {
    mysqli_stmt_bind_param($editStmt, 'i', $customEditId);
    mysqli_stmt_execute($editStmt);
    $editResult = mysqli_stmt_get_result($editStmt);
    if ($editResult) {
      $customEditRow = mysqli_fetch_assoc($editResult) ?: array();
    }
    mysqli_stmt_close($editStmt);
  }
}

$masterRows = array();
foreach ($masters as $type => $meta) {
  $table = $meta['table'];
  $pk = $meta['pk'];
  $nameCol = $meta['name'];

  $rows = array();
  if ($type === 'menu') {
    $result = $db_handle->conn->query("SELECT $pk AS master_id, $nameCol AS master_name, COALESCE(NULLIF(TRIM(menu_icon), ''), 'fa fa-folder') AS menu_icon FROM $table ORDER BY $nameCol ASC");
  } else {
    $result = $db_handle->conn->query("SELECT $pk AS master_id, $nameCol AS master_name FROM $table ORDER BY $nameCol ASC");
  }
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
  }
  $masterRows[$type] = $rows;
}

$menuOptions = array();
$menuResult = $db_handle->conn->query("SELECT menu_id, menu_name, COALESCE(NULLIF(TRIM(menu_icon), ''), 'fa fa-folder') AS menu_icon FROM lms_menu_master ORDER BY menu_name ASC");
if ($menuResult) {
  while ($menuRow = $menuResult->fetch_assoc()) {
    $menuOptions[] = $menuRow;
  }
}

$subMenuRows = array();
$subMenuResult = $db_handle->conn->query("SELECT sm.sub_menu_id, sm.menu_id, sm.sub_menu_name, sm.sort_order, COALESCE(NULLIF(TRIM(sm.sub_menu_icon), ''), 'fa fa-angle-double-right') AS sub_menu_icon, COALESCE(NULLIF(TRIM(sm.sub_menu_route), ''), '#') AS sub_menu_route, m.menu_name FROM lms_sub_menu_master sm INNER JOIN lms_menu_master m ON m.menu_id = sm.menu_id ORDER BY m.menu_name ASC, sm.sort_order ASC, sm.sub_menu_id ASC");
if ($subMenuResult) {
  while ($subMenuRow = $subMenuResult->fetch_assoc()) {
    $subMenuRows[] = $subMenuRow;
  }
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Master CRUD</li>
    </ol>
  </section>

  <section class="content" style="margin-top: 20px;">
    <div class="box" style="padding: 10px;">
      <h3><i class="fa fa-cogs"></i> Master Data</h3>

      <?php if ($alertMessage !== '') { ?>
        <div class="alert alert-<?php echo htmlspecialchars($alertType); ?> alert-dismissible" style="margin-top: 15px;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo htmlspecialchars($alertMessage); ?>
        </div>
      <?php } ?>

      <div id="ajax-status-message" style="margin-top: 15px;"></div>

      <ul class="nav nav-tabs" style="margin-top: 20px;">
        <li class="<?php echo ($activeTab === 'class-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#class-list">Class</a></li>
        <li class="<?php echo ($activeTab === 'section-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#section-list">Section</a></li>
        <li class="<?php echo ($activeTab === 'department-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#department-list">Departments</a></li>
        <li class="<?php echo ($activeTab === 'academic_year-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#academic_year-list">Academic Year</a></li>
        <li class="<?php echo ($activeTab === 'graduating_year-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#graduating_year-list">Graduating Year</a></li>
        <li class="<?php echo ($activeTab === 'specialization-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#specialization-list">Specialization</a></li>
        <li class="<?php echo ($activeTab === 'specialization_subject-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#specialization_subject-list">Specialization Subject</a></li>
        <li class="<?php echo ($activeTab === 'minor_course-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#minor_course-list">Minor Course</a></li>
        <li class="<?php echo ($activeTab === 'minor_subject-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#minor_subject-list">Minor Subject</a></li>
        <li class="<?php echo ($activeTab === 'menu-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#menu-list">Menu</a></li>
        <li class="<?php echo ($activeTab === 'sub-menu-list') ? 'active' : ''; ?>"><a data-toggle="tab" href="#sub-menu-list">Sub Menu</a></li>
      </ul>

      <div class="tab-content" style="padding-top: 20px;">
        <?php foreach ($masters as $type => $meta) {
          $listTabId = $type . '-list';
          $addTabId = $type . '-add';
          $title = $meta['title'];
          $rows = $masterRows[$type];
        ?>

          <div id="<?php echo $listTabId; ?>" class="tab-pane fade <?php echo ($activeTab === $listTabId) ? 'in active' : ''; ?>">
            <div class="clearfix" style="margin-bottom: 15px;">
              <button
                type="button"
                class="btn btn-success pull-right open-add-modal"
                data-toggle="modal"
                data-target="#addMasterModal"
                data-master-type="<?php echo htmlspecialchars($type); ?>"
                data-master-title="<?php echo htmlspecialchars($title); ?>"
              >
                <i class="fa fa-plus"></i>
              </button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped text-center">
                <thead>
                  <tr>
                    <th style="width: 80px;">No.</th>
                    <th><?php echo htmlspecialchars($title); ?> Name</th>
                    <?php if ($type === 'menu') { ?>
                    <th>Icon</th>
                    <?php } ?>
                    <th style="width: 100px;">Edit</th>
                    <th style="width: 100px;">Delete</th>
                  </tr>
                </thead>
                <tbody>
                <?php if (empty($rows)) { ?>
                  <tr>
                    <td colspan="<?php echo ($type === 'menu') ? '5' : '4'; ?>">No <?php echo htmlspecialchars(strtolower($title)); ?> found.</td>
                  </tr>
                <?php } else {
                  $serialNumber = 1;
                  foreach ($rows as $row) {
                    $id = intval($row['master_id']);
                    $name = (string) $row['master_name'];
                    $menuIconValue = ($type === 'menu') ? (string) ($row['menu_icon'] ?? 'fa fa-folder') : '';
                ?>
                  <tr>
                    <td><?php echo $serialNumber; ?></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <?php if ($type === 'menu') { ?>
                    <td><i class="<?php echo htmlspecialchars($menuIconValue); ?>" aria-hidden="true"></i></td>
                    <?php } ?>
                    <td>
                      <button
                        type="button"
                        class="btn btn-sm btn-primary open-edit-modal"
                        data-toggle="modal"
                        data-target="#editMasterModal"
                        data-master-type="<?php echo htmlspecialchars($type); ?>"
                        data-master-id="<?php echo $id; ?>"
                        data-master-name="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>"
                        data-menu-icon="<?php echo htmlspecialchars($menuIconValue, ENT_QUOTES); ?>"
                        data-master-title="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>"
                      >
                        <i class="fa fa-pencil"></i>
                      </button>
                    </td>
                    <td>
                      <form method="POST" class="ajax-delete-form" style="display:inline;" onsubmit="return confirmMasterDelete(<?php echo json_encode($name); ?>);">
                        <input type="hidden" name="master_action" value="delete">
                        <input type="hidden" name="master_type" value="<?php echo htmlspecialchars($type, ENT_QUOTES); ?>">
                        <input type="hidden" name="master_id" value="<?php echo $id; ?>">
                        <button type="submit" class="btn btn-sm btn-danger">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php
                    $serialNumber++;
                  }
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

        <?php } ?>

        <?php foreach ($customMasters as $type => $meta) {
          $listTabId = $type . '-list';
          $title = $meta['title'];
          $rows = $customMasterRows[$type];
          $formFields = $meta['formFields'];
          $isEditingThisType = ($customEditType === $type && $customEditId > 0 && !empty($customEditRow));
        ?>
          <div id="<?php echo $listTabId; ?>" class="tab-pane fade <?php echo ($activeTab === $listTabId) ? 'in active' : ''; ?>">
            <?php if ($isEditingThisType) { ?>
              <div class="box box-warning" style="padding: 10px; margin-bottom: 20px;">
                <div class="box-header with-border">
                  <h4 class="box-title">Edit <?php echo htmlspecialchars($title); ?></h4>
                </div>
                <form method="POST" class="form-horizontal" style="margin-top: 15px;">
                  <input type="hidden" name="custom_master_action" value="update">
                  <input type="hidden" name="custom_master_type" value="<?php echo htmlspecialchars($type, ENT_QUOTES); ?>">
                  <input type="hidden" name="custom_master_id" value="<?php echo $customEditId; ?>">
                  <?php foreach ($formFields as $field) {
                    echo render_custom_field_control('edit_' . $type, $field, $customEditRow[$field['name']] ?? '', $customLookupMaps);
                  } ?>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10" style="padding-left: 0;">
                      <button type="submit" class="btn btn-primary">Update</button>
                      <a href="class_crud_new.php?tab=<?php echo urlencode($listTabId); ?>" class="btn btn-default">Cancel</a>
                    </div>
                  </div>
                </form>
              </div>
            <?php } ?>

            <div class="box box-default" style="padding: 10px; margin-bottom: 20px;">
              <div class="box-header with-border">
                <h4 class="box-title">Add <?php echo htmlspecialchars($title); ?></h4>
              </div>
              <form method="POST" class="form-horizontal" style="margin-top: 15px;">
                <input type="hidden" name="custom_master_action" value="add">
                <input type="hidden" name="custom_master_type" value="<?php echo htmlspecialchars($type, ENT_QUOTES); ?>">
                <?php foreach ($formFields as $field) {
                  $defaultValue = isset($field['default']) ? $field['default'] : '';
                  echo render_custom_field_control('add_' . $type, $field, $defaultValue, $customLookupMaps);
                } ?>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10" style="padding-left: 0;">
                    <button type="submit" class="btn btn-success">Save</button>
                  </div>
                </div>
              </form>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-striped text-center">
                <thead>
                  <tr>
                    <th style="width: 80px;">No.</th>
                    <?php foreach ($meta['displayFields'] as $displayField) {
                      $headerLabel = '';
                      foreach ($formFields as $field) {
                        if ($field['name'] === $displayField) {
                          $headerLabel = $field['label'];
                          break;
                        }
                      }
                      if ($headerLabel === '') {
                        $headerLabel = ucwords(str_replace('_', ' ', $displayField));
                      }
                    ?>
                      <th><?php echo htmlspecialchars($headerLabel); ?></th>
                    <?php } ?>
                    <th style="width: 100px;">Edit</th>
                    <th style="width: 100px;">Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($rows)) { ?>
                    <tr>
                      <td colspan="<?php echo count($meta['displayFields']) + 3; ?>">No <?php echo htmlspecialchars(strtolower($title)); ?> found.</td>
                    </tr>
                  <?php } else {
                    $serialNumber = 1;
                    foreach ($rows as $row) {
                      $customRowId = intval($row[$meta['pk']]);
                      $rowLabelParts = array();
                  ?>
                    <tr>
                      <td><?php echo $serialNumber; ?></td>
                      <?php foreach ($meta['displayFields'] as $displayField) {
                        $fieldConfig = null;
                        foreach ($formFields as $field) {
                          if ($field['name'] === $displayField) {
                            $fieldConfig = $field;
                            break;
                          }
                        }
                        $displayValue = '';
                        if ($fieldConfig !== null) {
                          $displayValue = custom_master_list_display($row, $fieldConfig, $customLookupMaps);
                        } else {
                          $displayValue = isset($row[$displayField]) ? $row[$displayField] : '';
                        }
                        $rowLabelParts[] = (string) $displayValue;
                      ?>
                        <td><?php echo htmlspecialchars((string) $displayValue); ?></td>
                      <?php } ?>
                      <?php $rowLabel = trim(implode(' - ', array_filter($rowLabelParts, 'strlen'))); ?>
                      <td>
                        <a href="class_crud_new.php?tab=<?php echo urlencode($listTabId); ?>&custom_edit_type=<?php echo urlencode($type); ?>&custom_edit_id=<?php echo $customRowId; ?>" class="btn btn-sm btn-primary">
                          <i class="fa fa-pencil"></i>
                        </a>
                      </td>
                      <td>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete ' + <?php echo json_encode($rowLabel !== '' ? $rowLabel : $title); ?> + '?');">
                          <input type="hidden" name="custom_master_action" value="delete">
                          <input type="hidden" name="custom_master_type" value="<?php echo htmlspecialchars($type, ENT_QUOTES); ?>">
                          <input type="hidden" name="custom_master_id" value="<?php echo $customRowId; ?>">
                          <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php
                      $serialNumber++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php } ?>

        <div id="sub-menu-list" class="tab-pane fade <?php echo ($activeTab === 'sub-menu-list') ? 'in active' : ''; ?>">
          <div class="clearfix" style="margin-bottom: 15px;">
            <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#addSubMenuModal">
              <i class="fa fa-plus"></i>
            </button>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
              <thead>
                <tr>
                  <th style="width: 80px;">No.</th>
                  <th>Menu Name</th>
                  <th style="width: 100px;">Sequence</th>
                  <th>Sub Menu Name</th>
                  <th>Route</th>
                  <th>Icon</th>
                  <th style="width: 100px;">Edit</th>
                  <th style="width: 100px;">Delete</th>
                </tr>
              </thead>
              <tbody>
              <?php if (empty($subMenuRows)) { ?>
                <tr>
                  <td colspan="8">No sub menu found.</td>
                </tr>
              <?php } else {
                $subSerial = 1;
                foreach ($subMenuRows as $subRow) {
                  $subId = intval($subRow['sub_menu_id']);
                  $subMenuIdValue = intval($subRow['menu_id']);
                  $sortOrderValue = intval($subRow['sort_order']);
                  $menuNameValue = (string) $subRow['menu_name'];
                  $subNameValue = (string) $subRow['sub_menu_name'];
                  $subIconValue = (string) $subRow['sub_menu_icon'];
                  $subRouteValue = (string) $subRow['sub_menu_route'];
              ?>
                <tr>
                  <td><?php echo $subSerial; ?></td>
                  <td><?php echo htmlspecialchars($menuNameValue); ?></td>
                  <td><?php echo $sortOrderValue; ?></td>
                  <td><?php echo htmlspecialchars($subNameValue); ?></td>
                  <td><?php echo htmlspecialchars($subRouteValue); ?></td>
                  <td><i class="<?php echo htmlspecialchars($subIconValue); ?>" aria-hidden="true"></i></td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-sm btn-primary open-submenu-edit-modal"
                      data-toggle="modal"
                      data-target="#editSubMenuModal"
                      data-sub-menu-id="<?php echo $subId; ?>"
                      data-menu-id="<?php echo $subMenuIdValue; ?>"
                      data-sub-menu-name="<?php echo htmlspecialchars($subNameValue, ENT_QUOTES); ?>"
                      data-sub-menu-route="<?php echo htmlspecialchars($subRouteValue, ENT_QUOTES); ?>"
                      data-sub-menu-icon="<?php echo htmlspecialchars($subIconValue, ENT_QUOTES); ?>"
                      data-sort-order="<?php echo $sortOrderValue; ?>"
                    >
                      <i class="fa fa-pencil"></i>
                    </button>
                  </td>
                  <td>
                    <form method="POST" class="ajax-delete-form" style="display:inline;" onsubmit="return confirmSubMenuDelete(<?php echo json_encode($subNameValue); ?>);">
                      <input type="hidden" name="sub_menu_action" value="delete">
                      <input type="hidden" name="sub_menu_id" value="<?php echo $subId; ?>">
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php
                  $subSerial++;
                }
              }
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div id="addMasterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="add-master-title">Add</h4>
      </div>
      <form method="POST" class="ajax-add-form">
        <div class="modal-body">
          <input type="hidden" name="master_action" value="add">
          <input type="hidden" name="master_type" id="add_master_type" value="">

          <div class="form-group">
            <label for="add_master_name" class="control-label">Name</label>
            <input type="text" name="master_name" id="add_master_name" class="form-control" placeholder="Enter name" required>
          </div>

          <div class="form-group" id="add_menu_icon_group" style="display:none;">
            <label for="add_menu_icon" class="control-label">Menu Icon</label>
            <input type="hidden" name="menu_icon" id="add_menu_icon" value="fa fa-folder">
            <div class="icon-picker" data-target-input="add_menu_icon">
              <?php foreach ($availableMenuIcons as $iconClass) { ?>
                <button type="button" class="icon-option <?php echo ($iconClass === 'fa fa-folder') ? 'active' : ''; ?>" data-icon="<?php echo htmlspecialchars($iconClass); ?>" title="<?php echo htmlspecialchars($iconClass); ?>">
                  <i class="<?php echo htmlspecialchars($iconClass); ?>" aria-hidden="true"></i>
                </button>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="addSubMenuModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Sub Menu</h4>
      </div>
      <form method="POST" class="ajax-add-form">
        <div class="modal-body">
          <input type="hidden" name="sub_menu_action" value="add">

          <div class="form-group">
            <label for="add_sub_menu_parent" class="control-label">Menu</label>
            <select name="menu_id" id="add_sub_menu_parent" class="form-control" required>
              <option value="">Select Menu</option>
              <?php foreach ($menuOptions as $menuOption) { ?>
                <option value="<?php echo intval($menuOption['menu_id']); ?>"><?php echo htmlspecialchars($menuOption['menu_name']); ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="add_sub_menu_name" class="control-label">Sub Menu Name</label>
            <input type="text" name="sub_menu_name" id="add_sub_menu_name" class="form-control" placeholder="Enter Sub Menu Name" required>
          </div>

          <div class="form-group">
            <label for="add_sub_menu_route" class="control-label">Sub Menu Route</label>
            <input type="text" name="sub_menu_route" id="add_sub_menu_route" class="form-control" placeholder="example.php or #" value="#" required>
          </div>

          <div class="form-group">
            <label for="add_sub_menu_icon" class="control-label">Sub Menu Icon</label>
            <input type="hidden" name="sub_menu_icon" id="add_sub_menu_icon" value="fa fa-angle-double-right">
            <div class="icon-picker" data-target-input="add_sub_menu_icon">
              <?php foreach ($availableMenuIcons as $iconClass) { ?>
                <button type="button" class="icon-option <?php echo ($iconClass === 'fa fa-angle-double-right') ? 'active' : ''; ?>" data-icon="<?php echo htmlspecialchars($iconClass); ?>" title="<?php echo htmlspecialchars($iconClass); ?>">
                  <i class="<?php echo htmlspecialchars($iconClass); ?>" aria-hidden="true"></i>
                </button>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label for="add_sort_order" class="control-label">Sequence</label>
            <input type="number" name="sort_order" id="add_sort_order" class="form-control" min="1" placeholder="Auto if blank">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="delete-submenu-form" method="POST" style="display:none;">
  <input type="hidden" name="sub_menu_action" value="delete">
  <input type="hidden" name="sub_menu_id" id="delete_sub_menu_id" value="">
</form>

<div id="editSubMenuModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Sub Menu</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="sub_menu_action" value="update">
          <input type="hidden" name="sub_menu_id" id="edit_sub_menu_id" value="">

          <div class="form-group">
            <label for="edit_sub_menu_parent" class="control-label">Menu</label>
            <select name="menu_id" id="edit_sub_menu_parent" class="form-control" required>
              <option value="">Select Menu</option>
              <?php foreach ($menuOptions as $menuOption) { ?>
                <option value="<?php echo intval($menuOption['menu_id']); ?>"><?php echo htmlspecialchars($menuOption['menu_name']); ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="edit_sub_menu_name" class="control-label">Sub Menu Name</label>
            <input type="text" name="sub_menu_name" id="edit_sub_menu_name" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="edit_sub_menu_route" class="control-label">Sub Menu Route</label>
            <input type="text" name="sub_menu_route" id="edit_sub_menu_route" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="edit_sub_menu_icon" class="control-label">Sub Menu Icon</label>
            <input type="hidden" name="sub_menu_icon" id="edit_sub_menu_icon" value="fa fa-angle-double-right">
            <div class="icon-picker" data-target-input="edit_sub_menu_icon">
              <?php foreach ($availableMenuIcons as $iconClass) { ?>
                <button type="button" class="icon-option <?php echo ($iconClass === 'fa fa-angle-double-right') ? 'active' : ''; ?>" data-icon="<?php echo htmlspecialchars($iconClass); ?>" title="<?php echo htmlspecialchars($iconClass); ?>">
                  <i class="<?php echo htmlspecialchars($iconClass); ?>" aria-hidden="true"></i>
                </button>
              <?php } ?>
            </div>
          </div>

          <div class="form-group">
            <label for="edit_sort_order" class="control-label">Sequence</label>
            <input type="number" name="sort_order" id="edit_sort_order" class="form-control" min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="delete-master-form" method="POST" style="display:none;">
  <input type="hidden" name="master_action" value="delete">
  <input type="hidden" name="master_type" id="delete_master_type" value="">
  <input type="hidden" name="master_id" id="delete_master_id" value="">
</form>

<div id="editMasterModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="edit-master-title">Edit</h4>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="master_action" value="update">
          <input type="hidden" name="master_type" id="edit_master_type" value="">
          <input type="hidden" name="master_id" id="edit_master_id" value="">

          <div class="form-group">
            <label for="edit_master_name" class="control-label">Name</label>
            <input type="text" name="master_name" id="edit_master_name" class="form-control" required>
          </div>

          <div class="form-group" id="edit_menu_icon_group" style="display:none;">
            <label for="edit_menu_icon" class="control-label">Menu Icon</label>
            <input type="hidden" name="menu_icon" id="edit_menu_icon" value="fa fa-folder">
            <div class="icon-picker" data-target-input="edit_menu_icon">
              <?php foreach ($availableMenuIcons as $iconClass) { ?>
                <button type="button" class="icon-option <?php echo ($iconClass === 'fa fa-folder') ? 'active' : ''; ?>" data-icon="<?php echo htmlspecialchars($iconClass); ?>" title="<?php echo htmlspecialchars($iconClass); ?>">
                  <i class="<?php echo htmlspecialchars($iconClass); ?>" aria-hidden="true"></i>
                </button>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .icon-picker {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    max-height: 180px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px;
  }

  .icon-picker .icon-option {
    width: 36px;
    height: 36px;
    border: 1px solid #d2d6de;
    border-radius: 4px;
    background: #fff;
    color: #333;
    cursor: pointer;
  }

  .icon-picker .icon-option:hover {
    border-color: #3c8dbc;
  }

  .icon-picker .icon-option.active {
    background: #3c8dbc;
    color: #fff;
    border-color: #367fa9;
  }
</style>

<script>
  $(document).ready(function() {
    initIconPickers();

    $('#addMasterModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var masterType = button.data('master-type');
      var masterTitle = button.data('master-title');

      $('#add_master_type').val(masterType);
      $('#add_master_name').val('');
      $('#add-master-title').text('Add ' + masterTitle);
      $('#add_master_name').attr('placeholder', 'Enter ' + masterTitle + ' Name');

      if (masterType === 'menu') {
        $('#add_menu_icon_group').show();
        setIconPickerValue('add_menu_icon', 'fa fa-folder');
      } else {
        $('#add_menu_icon_group').hide();
      }
    });

    $('#editMasterModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var masterType = button.data('master-type');
      var masterId = button.data('master-id');
      var masterName = button.data('master-name');
      var menuIcon = button.data('menu-icon') || 'fa fa-folder';
      var masterTitle = button.data('master-title');

      $('#edit_master_type').val(masterType);
      $('#edit_master_id').val(masterId);
      $('#edit_master_name').val(masterName);
      $('#edit-master-title').text('Edit ' + masterTitle);

      if (masterType === 'menu') {
        $('#edit_menu_icon_group').show();
        setIconPickerValue('edit_menu_icon', menuIcon);
      } else {
        $('#edit_menu_icon_group').hide();
      }
    });

    $('#editSubMenuModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var subMenuId = button.data('sub-menu-id');
      var menuId = button.data('menu-id');
      var subMenuName = button.data('sub-menu-name');
      var subMenuRoute = button.data('sub-menu-route');
      var subMenuIcon = button.data('sub-menu-icon');
      var sortOrder = button.data('sort-order');

      $('#edit_sub_menu_id').val(subMenuId);
      $('#edit_sub_menu_parent').val(menuId);
      $('#edit_sub_menu_name').val(subMenuName);
      $('#edit_sub_menu_route').val(subMenuRoute);
      setIconPickerValue('edit_sub_menu_icon', subMenuIcon || 'fa fa-angle-double-right');
      $('#edit_sort_order').val(sortOrder);
    });

    $(document).on('submit', '.ajax-add-form', function(event) {
      event.preventDefault();

      var form = $(this);
      var button = form.find('button[type="submit"]');
      var originalHtml = button.html();
      var data = form.serialize();

      button.prop('disabled', true);

      $.ajax({
        type: 'POST',
        url: 'class_crud_new.php?tab=<?php echo urlencode($activeTab); ?>',
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response && response.status === 'success') {
            $('#addMasterModal').modal('hide');
            $('#addSubMenuModal').modal('hide');

            $('#ajax-status-message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + response.message + '</div>');

            if (form.find('input[name="master_action"]').length) {
              var masterType = form.find('input[name="master_type"]').val();
              var masterName = form.find('input[name="master_name"]').val();
              var targetTable = $('#' + masterType + '-list tbody');
                var rowCount = targetTable.find('tr').length + 1;
              var noRows = targetTable.find('tr td[colspan]').first();

              if (noRows.length) {
                noRows.closest('tr').remove();
              }

              var newRow = '';
              if (masterType === 'menu') {
                var menuIcon = response.menu_icon || 'fa fa-folder';
                  var safeMenuName = $('<div/>').text(masterName).html();
                newRow = '<tr>' +
                  '<td>' + rowCount + '</td>' +
                  '<td>' + safeMenuName + '</td>' +
                  '<td><i class="' + menuIcon + '" aria-hidden="true"></i></td>' +
                  '<td><button type="button" class="btn btn-sm btn-primary open-edit-modal" data-toggle="modal" data-target="#editMasterModal" data-master-type="menu" data-master-id="' + response.master_id + '" data-master-name="' + safeMenuName + '" data-menu-icon="' + menuIcon + '" data-master-title="Menu"><i class="fa fa-pencil"></i></button></td>' +
                  '<td><form method="POST" class="ajax-delete-form" style="display:inline;" onsubmit="return confirmMasterDelete(' + JSON.stringify(masterName) + ');"><input type="hidden" name="master_action" value="delete"><input type="hidden" name="master_type" value="menu"><input type="hidden" name="master_id" value="' + response.master_id + '"><button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></form></td>' +
                  '</tr>';

                targetTable.append(newRow);
                appendSidebarMenu(response.master_id, masterName, response.menu_icon);

                // Keep submenu menu dropdowns in sync without refresh.
                var optionExists = $('#add_sub_menu_parent option[value="' + response.master_id + '"]').length > 0;
                if (!optionExists) {
                  var newOption = $('<option/>', {
                    value: response.master_id,
                    text: masterName
                  });
                  $('#add_sub_menu_parent').append(newOption.clone());
                  $('#edit_sub_menu_parent').append(newOption.clone());
                }
              }
            } else if (form.find('input[name="sub_menu_action"]').length) {
              var menuId = form.find('select[name="menu_id"]').val();
              var menuName = response.menu_name || '';
              var subMenuName = form.find('input[name="sub_menu_name"]').val();
              var sortOrder = response.sort_order || form.find('input[name="sort_order"]').val();
              var subMenuRoute = response.sub_menu_route || form.find('input[name="sub_menu_route"]').val() || '#';
              var subMenuIcon = response.sub_menu_icon || form.find('input[name="sub_menu_icon"]').val() || 'fa fa-angle-double-right';
              var targetTable = $('#sub-menu-list tbody');
              var rowCount = targetTable.find('tr').length + 1;
              var noRows = targetTable.find('tr td[colspan]').first();

              if (noRows.length) {
                noRows.closest('tr').remove();
              }

              var safeMenuName = $('<div/>').text(menuName).html();
              var safeSubMenuName = $('<div/>').text(subMenuName).html();
              targetTable.append(
                '<tr>' +
                  '<td>' + rowCount + '</td>' +
                  '<td>' + safeMenuName + '</td>' +
                  '<td>' + $('<div/>').text(String(sortOrder)).html() + '</td>' +
                  '<td>' + safeSubMenuName + '</td>' +
                  '<td>' + $('<div/>').text(subMenuRoute).html() + '</td>' +
                  '<td><i class="' + subMenuIcon + '" aria-hidden="true"></i></td>' +
                  '<td><button type="button" class="btn btn-sm btn-primary open-submenu-edit-modal" data-toggle="modal" data-target="#editSubMenuModal" data-sub-menu-id="' + response.sub_menu_id + '" data-menu-id="' + menuId + '" data-sub-menu-name="' + $('<div/>').text(subMenuName).html() + '" data-sub-menu-route="' + $('<div/>').text(subMenuRoute).html() + '" data-sub-menu-icon="' + $('<div/>').text(subMenuIcon).html() + '" data-sort-order="' + sortOrder + '"><i class="fa fa-pencil"></i></button></td>' +
                  '<td><form method="POST" class="ajax-delete-form" style="display:inline;" onsubmit="return confirmSubMenuDelete(' + JSON.stringify(subMenuName) + ');"><input type="hidden" name="sub_menu_action" value="delete"><input type="hidden" name="sub_menu_id" value="' + response.sub_menu_id + '"><button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></form></td>' +
                '</tr>'
              );

              appendSidebarSubMenu(menuId, menuName, response.sub_menu_id, subMenuName, response.sub_menu_route, response.sub_menu_icon, sortOrder);
            }
          } else {
            alert((response && response.message) ? response.message : 'Unable to save record.');
          }
        },
        error: function() {
          alert('Unable to save record.');
        },
        complete: function() {
          button.prop('disabled', false).html(originalHtml);
        }
      });
    });

    $(document).on('submit', '.ajax-delete-form', function(event) {
      event.preventDefault();

      var form = $(this);
      var row = form.closest('tr');
      var table = row.closest('table');
      var columnCount = table.find('thead th').length;
      var button = form.find('button[type="submit"]');
      var originalHtml = button.html();

      button.prop('disabled', true);

      $.ajax({
        type: 'POST',
        url: 'class_crud_new.php?tab=<?php echo urlencode($activeTab); ?>',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
          if (response && response.status === 'success') {
            row.fadeOut(200, function() {
              $(this).remove();
            });

            var tableBody = row.closest('tbody');
            if (tableBody.find('tr').length === 1) {
              tableBody.append('<tr><td colspan="' + columnCount + '">No records found.</td></tr>');
            }

            if (response.master_type === 'menu' && response.master_id) {
              $('#sidebar-menu-' + response.master_id).remove();

              // Remove deleted menu from submenu menu dropdowns immediately.
              $('#add_sub_menu_parent option[value="' + response.master_id + '"]').remove();
              $('#edit_sub_menu_parent option[value="' + response.master_id + '"]').remove();
            }
            if (response.sub_menu_id) {
              var sidebarSub = $('#sidebar-submenu-item-' + response.sub_menu_id);
              var sidebarList = sidebarSub.closest('ul.treeview-menu');
              sidebarSub.remove();

              if (sidebarList.length && sidebarList.find('li').length === 0) {
                // keep empty menu container to allow future add without refresh
              }
            }

            $('#ajax-status-message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + response.message + '</div>');
          } else {
            alert((response && response.message) ? response.message : 'Unable to delete record.');
          }
        },
        error: function() {
          alert('Unable to delete record.');
        },
        complete: function() {
          button.prop('disabled', false).html(originalHtml);
        }
      });
    });

    <?php if ($openAddModalType !== '') { ?>
      $('#add_master_type').val('<?php echo htmlspecialchars($openAddModalType, ENT_QUOTES); ?>');
      $('#add-master-title').text('Add <?php echo htmlspecialchars($masters[$openAddModalType]['title'], ENT_QUOTES); ?>');
      $('#add_master_name').attr('placeholder', 'Enter <?php echo htmlspecialchars($masters[$openAddModalType]['title'], ENT_QUOTES); ?> Name');
      if ('<?php echo htmlspecialchars($openAddModalType, ENT_QUOTES); ?>' === 'menu') {
        $('#add_menu_icon_group').show();
      } else {
        $('#add_menu_icon_group').hide();
      }
      $('#addMasterModal').modal('show');
    <?php } ?>

    <?php if ($openAddSubMenuModal) { ?>
      $('#addSubMenuModal').modal('show');
    <?php } ?>

  });

  function confirmMasterDelete(masterName) {
    return confirm('Delete "' + masterName + '"?');
  }

  function initIconPickers() {
    $(document).on('click', '.icon-picker .icon-option', function() {
      var button = $(this);
      var wrapper = button.closest('.icon-picker');
      var inputId = wrapper.data('target-input');
      var iconClass = button.data('icon');
      setIconPickerValue(inputId, iconClass);
    });

    $('.icon-picker').each(function() {
      var wrapper = $(this);
      var inputId = wrapper.data('target-input');
      var selectedIcon = $('#' + inputId).val();
      setIconPickerValue(inputId, selectedIcon);
    });
  }

  function setIconPickerValue(inputId, iconClass) {
    if (!iconClass) {
      return;
    }

    var input = $('#' + inputId);
    var picker = $('.icon-picker[data-target-input="' + inputId + '"]');
    var button = picker.find('.icon-option[data-icon="' + iconClass + '"]');

    if (!button.length) {
      button = picker.find('.icon-option').first();
      if (!button.length) {
        return;
      }
      iconClass = button.data('icon');
    }

    input.val(iconClass);
    picker.find('.icon-option').removeClass('active');
    button.addClass('active');
  }

  function confirmSubMenuDelete(subMenuName) {
    return confirm('Delete sub menu "' + subMenuName + '"?');
  }

  function appendSidebarMenu(menuId, menuName, menuIcon) {
    if ($('#sidebar-menu-' + menuId).length) {
      return;
    }

    var html = '';
    html += '<li class="treeview" data-menu-id="' + menuId + '" id="sidebar-menu-' + menuId + '">';
    html += '<a href="#">';
    html += '<i class="' + menuIcon + '" aria-hidden="true"></i> <span>' + menuName.toUpperCase() + '</span>';
    html += '<span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>';
    html += '</a>';
    html += '<ul class="treeview-menu" id="sidebar-submenu-' + menuId + '"></ul>';
    html += '</li>';
    $('#sidebar-dynamic-menu').append(html);
  }

  function appendSidebarSubMenu(menuId, menuName, subMenuId, subMenuName, subMenuRoute, subMenuIcon, sortOrder) {
    var target = $('#sidebar-submenu-' + menuId);
    if (!target.length) {
      appendSidebarMenu(menuId, menuName || 'Menu', 'fa fa-folder');
      target = $('#sidebar-submenu-' + menuId);
    }

    if ($('#sidebar-submenu-item-' + subMenuId).length) {
      return;
    }

    target.append('<li data-sub-menu-id="' + subMenuId + '" id="sidebar-submenu-item-' + subMenuId + '"><a href="' + subMenuRoute + '"><i class="' + subMenuIcon + '"></i>' + subMenuName.toUpperCase() + '</a></li>');
  }
</script>

<?php include "header/footer.php"; ?>

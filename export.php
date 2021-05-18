<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

ini_set('zlib.output_compression', 'Off');

ob_start();
require_once('include/export_utils.php');
global $sugar_config;
global $current_user;
global $app_list_strings;

$the_module = clean_string($_REQUEST['module']);

if ($sugar_config['disable_export'] 	|| (!empty($sugar_config['admin_export_only']) && !(is_admin($current_user) || (ACLController::moduleSupportsACL($the_module)  && ACLAction::getUserAccessLevel($current_user->id, $the_module, 'access') == ACL_ALLOW_ENABLED &&
    (ACLAction::getUserAccessLevel($current_user->id, $the_module, 'admin') == ACL_ALLOW_ADMIN ||
     ACLAction::getUserAccessLevel($current_user->id, $the_module, 'admin') == ACL_ALLOW_ADMIN_DEV))))) {
    die($GLOBALS['app_strings']['ERR_EXPORT_DISABLED']);
}


if (!empty($_REQUEST['sample'])) {

    $content = exportSample(clean_string($_REQUEST['module']));
} else {
    if (!empty($_REQUEST['uid'])) {
        $content = export(clean_string($_REQUEST['module']), $_REQUEST['uid'], isset($_REQUEST['members']) ? $_REQUEST['members'] : false);
    } else {
        $content = export(clean_string($_REQUEST['module']));
    }
}
$filename = $_REQUEST['module'];

if (!empty($app_list_strings['moduleList'][$_REQUEST['module']])) {
    $filename = $app_list_strings['moduleList'][$_REQUEST['module']];
}

if (!empty($_REQUEST['members'])) {
    $filename .= '_'.'members';
}


ob_clean();
printCSV($content, $filename);
sugar_cleanup(true);

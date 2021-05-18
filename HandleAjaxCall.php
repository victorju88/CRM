<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

 require_once('include/entryPoint.php');
 require_once('ModuleInstall/PackageManager/PackageController.php');
if (!is_admin($GLOBALS['current_user'])) {
    sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}
    $requestedMethod = $_REQUEST['method'];
    $pmc = new PackageController();
  
    if (method_exists($pmc, $requestedMethod)) {
        echo $pmc->$requestedMethod();
    } else {
        echo 'no method';
    }
  

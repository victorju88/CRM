<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}



global $beanList, $beanFiles, $locale;

if (isset($_REQUEST['module']) && isset($_REQUEST['action']) && isset($_REQUEST['record'])) {
    $currentModule = clean_string($_REQUEST['module']);
    $action = clean_string($_REQUEST['action']);
    $record = clean_string($_REQUEST['record']);
} else {
    die("module, action, and record id all are required");
}

$entity = $GLOBALS['beanList'][$currentModule];
require_once($GLOBALS['beanFiles'][$entity]);
$GLOBALS['focus'] = new $entity();
$GLOBALS['focus']->retrieve(clean_string($_REQUEST['record']));

include("modules/$currentModule/$action.php");

<?php
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}



ob_start();
require_once('include/entryPoint.php');
require("modules/iCals/Server.php");

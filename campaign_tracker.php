<?php
 if (!defined('sugarEntry')) {
     define('sugarEntry', true);
 }

require_once('include/entryPoint.php');



require_once('modules/Campaigns/utils.php');

if (!empty($_REQUEST['identifier'])) {
    $keys=log_campaign_activity($_REQUEST['identifier'], 'link');
}

if (empty($_REQUEST['track'])) {
    $track = "";
} else {
    $track = $_REQUEST['track'];
}
$track = $db->quote($track);

if (preg_match('/^[0-9A-Za-z\-]*$/', $track)) {
    $query = "SELECT refer_url FROM campaigns WHERE tracker_key='$track'";
    $res = $db->query($query);

    $row = $db->fetchByAssoc($res);

    $redirect_URL = $row['refer_url'];
    sugar_cleanup();
    header("Location: $redirect_URL");
} else {
    sugar_cleanup();
}
exit;

<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once('include/entryPoint.php');
require_once('include/utils/file_utils.php');
ob_start();

require_once('soap/SoapError.php');
require_once('include/nusoap/nusoap.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('service/core/SoapHelperWebService.php');
require_once('modules/Cases/Case.php');
//ignore notices


global $HTTP_RAW_POST_DATA;

$administrator = BeanFactory::newBean('Administration');
$administrator->retrieveSettings();

// Sugarcrm namespace is necessary for backwards compatibility with existing SOAP clients
$NAMESPACE = 'http://www.sugarcrm.com/sugarcrm';
$server = new soap_server;
$server->configureWSDL('sugarsoap', $NAMESPACE, $sugar_config['site_url'].'/soap.php');

//New API is in these files
if (!empty($administrator->settings['portal_on'])) {
    require_once('soap/SoapPortalUsers.php');
}

require_once('soap/SoapSugarUsers.php');
//require_once('soap/SoapSugarUsers_version2.php');
require_once('soap/SoapData.php');
require_once('soap/SoapDeprecated.php');



/* Begin the HTTP listener service and exit. */
ob_clean();

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
}

require_once('include/resource/ResourceManager.php');
$resourceManager = ResourceManager::getInstance();
$resourceManager->setup('Soap');
$observers = $resourceManager->getObservers();
//Call set_soap_server for SoapResourceObserver instance(s)
foreach ($observers as $observer) {
    if (method_exists($observer, 'set_soap_server')) {
        $observer->set_soap_server($server);
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
global $soap_server_object;
$soap_server_object = $server;
$server->service($HTTP_RAW_POST_DATA);
ob_end_flush();
flush();
sugar_cleanup();
exit();

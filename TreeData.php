<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


$ret=array();
$params1=array();
$nodes=array();

$GLOBALS['log']->debug("TreeData:session started");
$current_language = $GLOBALS['current_language'];

//process request parameters. consider following parameters.
//function, and all parameters prefixed with PARAM.
//PARAMT_ are tree level parameters.
//PARAMN_ are node level parameters.
//module  name and function name parameters are the only ones consumed
//by this file..
foreach ($_REQUEST as $key=>$value) {
    switch ($key) {
    
        case "function":
        case "call_back_function":
            $func_name=$value;
            $params1['TREE']['function']=$value;
            break;
            
        default:
            $pssplit=explode('_', $key);
            if ($pssplit[0] =='PARAMT') {
                unset($pssplit[0]);
                $params1['TREE'][implode('_', $pssplit)]=$value;
            } else {
                if ($pssplit[0] =='PARAMN') {
                    $depth=$pssplit[count($pssplit)-1];
                    //parmeter is surrounded  by PARAMN_ and depth info.
                    unset($pssplit[count($pssplit)-1]);
                    unset($pssplit[0]);
                    $params1['NODES'][$depth][implode('_', $pssplit)]=$value;
                } else {
                    if ($key=='module') {
                        if (!isset($params1['TREE']['module'])) {
                            $params1['TREE'][$key]=$value;
                        }
                    } else {
                        $params1['REQUEST'][$key]=$value;
                    }
                }
            }
    }
}
$modulename=$params1['TREE']['module']; ///module is a required parameter for the tree.
require('include/modules.php');
if (!empty($modulename) && !empty($func_name) && isset($beanList[$modulename])) {
    require_once('modules/'.$modulename.'/TreeData.php');
    $TreeDataFunctions = array(
        'ProductTemplates' => array('get_node_data'=>'','get_categories_and_products'=>''),
        'ProductCategories' => array('get_node_data'=>'','get_product_categories'=>''),
        'KBTags' => array(
            'get_node_data'=>'',
            'get_tags_nodes'=>'',
            'get_tags_nodes_cached'=>'',
            'childNodes'=>'',
            'get_searched_tags_nodes'=>'',
            'find_peers'=>'',
            'getRootNode'=>'',
            'getParentNode'=>'',
            'get_tags_modal_nodes'=>'',
            'get_admin_browse_articles'=>'',
            'tagged_documents_count'=>'',
            'tag_count'=>'',
            'get_browse_documents'=>'',
            'get_tag_nodes_for_browsing'=>'',
            'create_browse_node'=>'',
            'untagged_documents_count'=>'',
            'check_tag_child_tags_for_articles'=>'',
            'childTagsHaveArticles'=>'',
            ),
        'KBDocuments' => array(
            'get_node_data'=>'',
            'get_category_nodes'=>'',
            'get_documents'=>'',
            ),
        'Forecasts' => array(
            'get_node_data'=>'',
            'get_worksheet'=>'',
            'commit_forecast'=>'',
            'save_worksheet'=>'',
            'list_nav'=>'',
            'reset_worksheet'=>'',
            'get_chart'=>'',
            ),
        'Documents' => array(
            'get_node_data'=>'',
            'get_category_nodes'=>'',
            'get_documents'=>'',
            ),
        );
        
    if (isset($TreeDataFunctions[$modulename][$func_name])) {
        $ret=call_user_func($func_name, $params1);
    }
}

if (!empty($ret)) {
    echo $ret;
}

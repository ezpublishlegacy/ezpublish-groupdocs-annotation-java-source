<?php

/**
 * File containing the eZ Publish view implementation.
 *
 * @copyright GroupDocs
 * @version 1.0
 * @extention groupdocsannotationjava
 */
/*

 */
///////////////////////////////////////// FORM STARTED /////////////////////////////////////////
// take copy of global object 
$db = eZDB::instance();
$http = eZHTTPTool::instance();

// Create mysql table if not exist
if (!isset($_SESSION['gdajcreatetable']) || !$_SESSION['gdajcreatetable']) {
    $query = 'CREATE TABLE IF NOT EXISTS `gdaj` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `url` varchar(250) NOT NULL,
						  `file_hook` varchar(250) NOT NULL,
						  `width` int(5) NOT NULL,
						  `height` int(5) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM;';
    $db->query($query);
    $_SESSION['gdajcreatetable'] = 1;
}

include_once( 'extension/groupdocsannotationjava/classes/groupdocsannotationjava.php' );
$module = $Params['Module'];

// If the variable 'name' is sent by GET or POST, show variable 
$value = '';

// DELETE GroupDocs File ID 
if ($http->hasVariable('del_id')) {
    $del_id = $http->variable('del_id');
    $query = 'DELETE FROM gdaj WHERE id=' . (int) $del_id;
    $db->arrayQuery($query);
    return $module->redirectTo('/groupdocsannotationjava/config');
}

// SAVE GroupDocs File ID
if ($http->hasVariable('url')) {
    $url = $http->variable('url');
    $width = (int) $http->variable('width');
    $height = (int) $http->variable('height');

    if ($url != '') {

        if (substr($url, -1) != "/") {
            $url = $url . "/";
        }
        // assign hook_id
        $HookId = GroupDocsAnnotationJava::getMaxId();
        $file_hook = '#gdannotationjava' . ($HookId + 1) . '#'; // as no records show zero
        // generate new data object 
        $GDObject = GroupDocsAnnotationJava::create($url, $file_hook, $width, $height);
        eZDebug::writeDebug('1.' . print_r($GDObject, true), 'GDObject before saving: ID not set');

        // save object in database 
        $GDObject->store();
        eZDebug::writeDebug('2.' . print_r($GDObject, true), 'GDObject after saving: ID set');

        // ask for the ID of the new created object 
        $id = $GDObject->attribute('id');

        // investigate the amount of data existing 
        $count = GroupDocsAnnotationJava::getListCount();
        $statusMessage = 'URL: >>' . $url .
                '<< Hook:  >>' . $hook .
                '<< In database with ID >>' . $id .
                '<< saved!New ammount = ' . $count;

        return $module->redirectTo('/groupdocsannotationjava/config');
    } else
        $statusMessage = 'Please insert data';

    // initialize Templateobject 
    $tpl = eZTemplate::factory();

    $tpl->setVariable('status_message', $statusMessage);
    // Write variable $statusMessage in the file eZ Debug Output / Log 
    // here the 4 different types: Notice, Debug, Warning, Error 
    eZDebug::writeNotice($statusMessage, 'groupdocsannotationjava:groupdocsannotationjava/config.php');
    eZDebug::writeDebug($statusMessage, 'groupdocsannotationjava:groupdocsannotationjava/config.php');
    eZDebug::writeWarning($statusMessage, 'groupdocsannotationjava:groupdocsannotationjava/config.php');
    eZDebug::writeError($statusMessage, 'groupdocsannotationjava:groupdocsannotationjava/config.php');
}
/////////////////////////////////////////// form ended ////////////////////////////////////////////////
// Get list of file from DB
$dataArray = array();
$query = 'SELECT * FROM gdaj';
$rows = $db->arrayQuery($query);
if ($rows)
    foreach ($rows as $row) {
        if ($row['width'] === '0')
            $row['width'] = '';
        if ($row['height'] === '0')
            $row['height'] = '';
        $dataArray[$row['id']] = array($row['url'], $row['file_hook'], $row['width'], $row['height']);
    }
// initialize Templateobject
$tpl = eZTemplate::factory();

// create example Array in the template => {$data_array}
$tpl->setVariable('data_array', $dataArray);
/////////////////////////////////// inistialization ended ///////////////////////////////////////
//carry out internal processing here, none required in this case.
// setting up what to render to the user:
$Result = array();

//$t = $tpl->compileTemplateFile('design:groupdocsannotation/config.tpl');
$t = $tpl->fetch('design:groupdocsannotationjava/config.tpl');

$Result['content'] = $t; //main tpl file to display the output

$Result['left_menu'] = "design:groupdocsannotationjava/leftmenu.tpl";

$Result['path'] = array(array(
        'url' => 'groupdocsannotationjava/config',
        'text' => 'Groupdocs Annotation for Java'
    )); //what to show in the Title bar for this URL
// read variable GdvDebug of INI block [GDANExtensionSettings] 
// of INI file jacextension.ini  

$groupdocsannotationjavaINI = eZINI::instance('groupdocsannotationjava.ini');

$gdajDebug = $groupdocsannotationjavaINI->variable('GDAJExtensionSetting', 'JacDebug');

// If Debug is activated do something 
if ($gdajDebug === 'enabled')
    echo 'groupdocsannotationjava.ini: [GDAJExtensionSetting] GdajDebug=enabled';
?>
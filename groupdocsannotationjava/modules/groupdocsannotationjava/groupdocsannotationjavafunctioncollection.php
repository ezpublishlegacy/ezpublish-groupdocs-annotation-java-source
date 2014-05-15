<?php
 include_once( 'extension/groupdocsannotationjava/classes/groupdocsannotationjava.php' );
/*
class GroupdocsAnnotationJavaFunctionCollection
{

function GroupdocsAnnotationJavaFunctionCollection()
{
}

function &fetchList( $offset, $limit )
{
$parameters = array( 'offset' => $offset,
'limit' => $limit );
$lista =& Groupdocsannotationjava( $parameters );

return array( 'result' => &$lista );
}

}
*/
class eZModul4FunctionCollection
{ 
    public function __construct() 
    {
        // ...
    }
 
    /*
     * Is opened by('modul1', 'list', hash('as_object', $bool ) ) fetch
     * @param bool $asObject
     */ 
    public static function fetchList( $asObject ) 
    { 
        return array( 'result' => GroupDocsAnnotationJava::fetchList( $asObject ) ); 
    }
 
    /*
     * Is opened by('modul1', 'count', hash() ) fetch
     */
    public static function fetchJacExtensionDataListCount()
    { 
        return array( 'result' => GroupDocsAnnotationJava::getListCount() ); 
    } 
} 
?>
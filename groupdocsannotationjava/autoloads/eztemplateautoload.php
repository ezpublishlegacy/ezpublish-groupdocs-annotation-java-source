<?php
include_once( 'extension/groupdocsannotationjava/classes/groupdocsannotationjava.php' );

/** 
 * Operator: gdan('list') and gdan('count') <br> 
 * Count: {gdan('count')} <br> 
 * Liste: {gdan('list')|attribute(show)} 
 */ 
class GADJOperator
{ 
    public $Operators;
 
    public function __construct( $name = 'gdaj' )
    { 
        $this->Operators = array( $name ); 
    }
 
    /** 
     * Returns the template operators.
     * @return array
     */ 
    function operatorList()
    { 
        return $this->Operators; 
    }
 
    /**
     * Returns true to tell the template engine that the parameter list 
     * exists per operator type. 
     */ 
    public function namedParameterPerOperator() 
    { 
        return true; 
    }
 
    /**
     * @see eZTemplateOperator::namedParameterList 
     **/ 
    public function namedParameterList() 
    { 
        return array( 'gdan' => array( 'result_type' => array( 'type' => 'string',    
                                                              'required' => true, 
                                                              'default' => 'list' ))
                    ); 
    }
 
    /**
     * Depending of the parameters that have been transmitted, fetch objects JACExtensionData 
     * {gdan('list)} or count data {gdan('count')} 
     */ 
    public function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    { 
        $result_type = $namedParameters['result_type']; 
        if( $result_type == 'list') 
            $operatorValue = GroupDocsAnnotationJava::fetchList(true); 
        else if( $result_type == 'count') 
            $operatorValue = GroupDocsAnnotationJava::getListCount(); 
    } 
} 
?>
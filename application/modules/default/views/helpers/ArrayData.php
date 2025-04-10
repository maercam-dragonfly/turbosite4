<?php

class Zend_View_Helper_ArrayData extends Zend_View_Helper_Abstract
{
    public function ArrayData($data)
    {
        return $this->view->partial('partials/arrayData.phtml', 'default',array('arrayData' => $data));   
        
    }
}

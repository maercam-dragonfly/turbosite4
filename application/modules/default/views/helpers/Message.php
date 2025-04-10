<?php

class Zend_View_Helper_Message extends Zend_View_Helper_Abstract
{
    public function Message( $data )
    {
        if(isset($data)){ 
            if(is_array($data['text'])){
                $data['text'] = $this->view->ArrayData($data['text']);
            }
            return $this->view->partial('partials/message.phtml', 'default',array('dialogMessage' => $data));   
        }
         
    }
}

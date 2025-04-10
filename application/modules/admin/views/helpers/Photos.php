<?php
/**
 * Description of Zend_View_Helper_Gallery
 *
 * @author Marcin
 */
class Zend_View_Helper_Photos extends Zend_View_Helper_Abstract{
    
     public function Photos ( $data = null )
    {
         return $this->view->partial('partials/helpers/photos.phtml', 'admin', array('photos' => $data));   
    }
    
}


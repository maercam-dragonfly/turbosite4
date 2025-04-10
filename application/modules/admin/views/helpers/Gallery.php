<?php
/**
 * Description of Zend_View_Helper_Gallery
 *
 * @author Marcin
 */
class Zend_View_Helper_Gallery extends Zend_View_Helper_Abstract{
    
     public function Gallery ( $data = null )
    {
         return $this->view->partial('partials/helpers/gallery.phtml', 'admin', array('gallery' => $data));   
    }
    
}


<?php
/**
 * Description of Zend_View_Helper_Gallery
 *
 * @author Marcin
 */
class Zend_View_Helper_GalleryAdd extends Zend_View_Helper_Abstract{
    
     public function GalleryAdd ( $data = null )
    {
         return $this->view->partial('partials/helpers/galleryadd.phtml', 'admin', array('gallery' => $data));   
    }
    
}


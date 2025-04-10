<?php

/**
 *
 * @author Marcin
 */
class Zend_View_Helper_Paginate extends Zend_View_Helper_Abstract {

    public function Paginate($pagesCount,$currentPage,$urlProvider,$display) {
        return $this->view->partial('partials/paginate.phtml', 'default', 
                array(
                    'pagesCount' => $pagesCount,
                    'currentPage' => $currentPage,
                    'urlProvider' => $urlProvider,
                    'display' => $display
                    )
                );
    }

}


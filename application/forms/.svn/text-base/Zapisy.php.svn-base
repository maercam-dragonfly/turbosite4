<?php

class Form_Zapisy extends Form_BaseZapisy{
   public function init()
    {
       parent::init();
        $registered = new Zend_Form_Element_Checkbox('registered');
        $this->addElement($registered);
        $registered->setLabel('Rejestruj');
        $registered->setDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Label'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'element-group'))
        ));
    }
}

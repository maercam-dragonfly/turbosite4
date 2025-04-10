<?php

/**
 * Description of Form_Article
 *
 * @author Marcin
 */
class Form_AreYouSure extends Zend_Form{
   public function init()
    {
       parent::init();
       
       $yes = new Zend_Form_Element_Submit('yes');
       $yes->setLabel('Tak');
       
       $no = new Zend_Form_Element_Submit('no');
       $no->setLabel('Nie');
       
       $this->addElement($yes);  
       $this->addElement($no); 
       
        $this->clearDecorators();
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div'))
             ->addDecorator('Form');
       $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Label'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'element-group'))
        ));
        $yes->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit-group'))
        ));
        
        $no->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit-group'))
        ));
       $this->setMethod('post');
    }
}

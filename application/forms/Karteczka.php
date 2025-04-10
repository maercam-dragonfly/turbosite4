<?php

/**
 * Description of Form_Article
 *
 * @author Marcin
 */
class Form_Karteczka extends Zend_Form{
    
    public function __construct($opptions) {
        //Zend_Debug::dump($opptions);
        parent::__construct($opptions);
        
    }
    public function init()
    {
       parent::init();
      
       $this->setAttrib('class', $this->getAttrib('class'));
       
       $title = new Zend_Form_Element_Text('title');
       $title->setLabel('Tytuł');
       
       $content = new Zend_Form_Element_Textarea('content');
       $content->setLabel('Treść');
       
       $ident = new Zend_Form_Element_Hidden('ident');
       $ident->setValue($this->getAttrib('class'));
       
       
       $submit = new Zend_Form_Element_Submit('submit');
       $submit->setLabel("zapisz");
       
       $this->addElement($title);  
       $this->addElement($content); 
       $this->addElement($submit);
       $this->addElement($ident);
       
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

        $submit->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit-group'))
        ));

       $this->setMethod('post');
    }
}

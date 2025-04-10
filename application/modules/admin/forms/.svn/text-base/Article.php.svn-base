<?php

/**
 * Description of Form_Article
 *
 * @author Marcin
 */
class Form_Article extends Zend_Form{
   public function init()
    {
       parent::init();
       $categories = Doctrine_Query::create()
                             ->select('c.name')
                             ->from('Application_Model_Category c')
                             ->execute();
       $categories_options = array();
       foreach($categories as $cat){
           $categories_options[] = $cat['name'];
           
       }
        $title = new Zend_Form_Element_Text('title');
        $content = new Zend_Form_Element_Textarea('content');
        $category = new Zend_Form_Element_Select('category');
        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElement($title);
        $this->addElement($category);
        $this->addElement($content);       
        $this->addElement($submit);
        //ustawienie właściwości pól
        $title->setLabel('Tytuł')
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty(), true)
          ->addValidator(new Zend_Validate_StringLength(0, 255), true)
          ->addFilter(new Zend_Filter_StripTags())
          ->addFilter(new Zend_Filter_StringTrim());
        
        $content->setLabel('Treść')
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty(), true)
          ->addFilter(new Zend_Filter_StringTrim());
        
        $category->setLabel('kategoria')
         ->setRequired(true)
         ->addValidator(new Zend_Validate_NotEmpty(), true)
         ->addMultiOptions($categories_options);
         
        
        $submit->setLabel('Zapisz');
        
        //ustawienie dekoratorów formularza
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
        
        $title->getValidator('NotEmpty')->setMessages(array(
            Zend_Validate_NotEmpty::IS_EMPTY => 'Proszę podać tytuł'
        ));
        $title->getValidator('StringLength')->setMessages(array(
            Zend_Validate_StringLength::TOO_LONG => 'Proszę podać krótszy tytuł'   
        ));
        $content->getValidator('NotEmpty')->setMessages(array(
            Zend_Validate_NotEmpty::IS_EMPTY => 'Proszę podać treść'
        ));
        
        $this->setMethod('post');
    }
}

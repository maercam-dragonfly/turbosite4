<?php

/**
 * Description of Form_Article
 *
 * @author Marcin
 */
class Form_Article extends ZendX_JQuery_Form {
 
    public static $formJQueryElements = array(
        array('UiWidgetElement', array('tag' => '')), // it necessary to include for jquery elements
        array('Errors'),
        array('Label'),
        array('HtmlTag', array('tag' => 'div', 'class' => 'element-group'))
    );

    public function init() {
        parent::init();
        $categories = Doctrine_Query::create()
                ->select('c.name')
                ->from('Application_Model_Category c')
                ->execute();
        $categories_options = array();
        foreach ($categories as $cat) {
            $categories_options[] = $cat['name'];
        }
        $title = new Zend_Form_Element_Text('title');
        $content = new Zend_Form_Element_Textarea('content');
        $category = new Zend_Form_Element_Select('category');
        $view = new Zend_Form_Element_Button('view');
        $submit = new Zend_Form_Element_Submit('submit');
        $date = new ZendX_JQuery_Form_Element_DatePicker('date');
        //$birthdate->getDecorators();
        $date->setLabel('Data')
                ->setValue(date("d.m.Y"))
                ->setJQueryParam('dateFormat', 'dd.mm.yy')
                ->setJQueryParam('changeYear', 'true')
                ->setJqueryParam('changeMonth', 'true')
                ->setDescription('dd.mm.yyyy')
                ->addValidator(new Zend_Validate_Date(
                                array(
                                    'format' => 'dd.mm.yy',
                        )))
                ->setRequired(true);

        $datePickerDecorators = $date->getDecorators();
        $this->addElement($title);
        $this->addElement($date);
        $this->addElement($category);
        $this->addElement($content);
        $this->addElement($view);
        $this->addElement($submit);
        //ustawienie właściwości pól
        $title->setLabel('Tytuł')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_NotEmpty(), true)
                ->addValidator(new Zend_Validate_StringLength(0, 255), true)
                ->addValidator('regex', true, '([a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ\-_!,;]+)')
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());


        $content->setLabel('Treść')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_NotEmpty(), true)
                ->addFilter(new Zend_Filter_StringTrim());

        $category->setLabel('Kategoria')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_NotEmpty(), true)
                ->addMultiOptions($categories_options);

        $view->setLabel('Podgląd')
                ->setAttrib('id', 'view');
        $submit->setLabel('Zapisz');

        //ustawienie dekoratorów formularza
        $this->clearDecorators();
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'div', 'id' => 'article_form'))
                ->addDecorator('Form');


        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Label'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'element-group'))
        ));

        $view->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit-group'))
        ));
        $submit->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit-group'))
        ));

        $date->setDecorators(self::$formJQueryElements);
        
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

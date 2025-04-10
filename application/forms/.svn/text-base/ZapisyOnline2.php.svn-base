<?php

class laForm_ZapisyOnline extends Zend_Form{
   public function init()
    {
       //parent::init();
       //$categories = Doctrine_Query::create()
       //                      ->select('c.name')
       //                      ->from('Application_Model_Category c')
       //                      ->execute();
       //$categories_options = array();
       //foreach($categories as $cat){
       //    $categories_options[] = $cat['name'];
           
       //}
        $imie = new Zend_Form_Element_Text('imie');
        $nazwisko = new Zend_Form_Element_Text('nazwisko');
        $email = new Zend_Form_Element_Text('email');
        $telefon = new Zend_Form_Element_Text('telefon');
        $captchaImage = new Zend_Captcha_Image();
        $captchaImage->setFont(APPLICATION_PATH . '/data/arial.ttf')
                     ->setFontSize(36)
                     ->setImgDir(APPLICATION_PATH . '/../public/img/captcha')
                     ->setImgUrl('../img/captcha')
                     ->setExpiration(90)
                     ->setGcFreq(5)
                     ->setWidth(200)
                     ->setHeight(100)
                     ->setTimeout(60)
                     ->setWordlen(5);
 
        $captcha = new Zend_Form_Element_Captcha(
            'txt_captcha',
            array(
                'captcha' => $captchaImage
            )
        );
        $captcha->setLabel('Wpisz kod');
        
        $submit = new Zend_Form_Element_Submit('submit');
        
        
        $this->addElement($imie);
        $this->addElement($nazwisko);
        $this->addElement($email);
        $this->addElement($telefon);
       
        $this->addElement($captcha);
        
        //$this->addElement($category);
        //$this->addElement($content);       
        $this->addElement($submit);
        //ustawienie właściwości pól
        $imie->setLabel('Imię')
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty(), true)
          ->addValidator(new Zend_Validate_StringLength(0, 255), true)
          ->addFilter(new Zend_Filter_StripTags())
          ->addFilter(new Zend_Filter_StringTrim());
        $nazwisko->setLabel('Nazwisko')
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty(), true)
          ->addValidator(new Zend_Validate_StringLength(0, 255), true)
          ->addFilter(new Zend_Filter_StripTags())
          ->addFilter(new Zend_Filter_StringTrim());
        $email->setLabel('e-mail')
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty(), true)
          ->addValidator(new Zend_Validate_StringLength(0, 255), true)
          ->addValidator(new Zend_Validate_EmailAddress())
          ->addFilter(new Zend_Filter_StripTags())
          ->addFilter(new Zend_Filter_StringTrim());
        $telefon->setLabel('Telefon')
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty(), true)
          ->addValidator(new Zend_Validate_StringLength(0, 255), true)
          ->addFilter(new Zend_Filter_StripTags())
          ->addFilter(new Zend_Filter_StringTrim());
        
        
        //$content->setLabel('Treść')
        //  ->setRequired(true)
        //  ->addValidator(new Zend_Validate_NotEmpty(), true)
        //  ->addFilter(new Zend_Filter_StripTags())
        //  ->addFilter(new Zend_Filter_StringTrim());
        
        //$category->setLabel('kategoria')
        // ->setRequired(true)
        // ->addValidator(new Zend_Validate_NotEmpty(), true)
        // ->addMultiOptions($categories_options);
         
        
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
        
        
        //$captcha->setDecorators(
         //array( 'Captcha',
         //'Description',
         //'Errors',
         //array(array('data'=>'HtmlTag'), array('tag' => 'div')),
         //array('Label', array('tag' => 'div')),
         //array(array('row'=>'HtmlTag'),array('tag'=>'div'))
         //));
        $captcha->setDecorators(array(
            array('Captcha'),
            array('Errors'),
            array('Label', array('tag' => 'div')),
            array('FormElements', array('tag' => 'div')),
            array('Description', array('escape' => false)),
            array('HtmlTag', array('tag' => 'div', 'class' => 'element-group', 'id' => 'captcha'))
        ));
        
        $imie->getValidator('NotEmpty')->setMessages(array(
            Zend_Validate_NotEmpty::IS_EMPTY => 'Proszę podać imię',
        ));
        $imie->getValidator('StringLength')->setMessages(array(
            Zend_Validate_StringLength::TOO_LONG => 'Proszę podać krótsze imię',
        ));
        $nazwisko->getValidator('NotEmpty')->setMessages(array(
            Zend_Validate_NotEmpty::IS_EMPTY => 'Proszę podać nazwisko',
        ));
        $nazwisko->getValidator('StringLength')->setMessages(array(
            Zend_Validate_StringLength::TOO_LONG => 'Proszę podać krótsze nazwisko'   
        ));
        
        $email->getValidator('NotEmpty')->setMessages(array(
            Zend_Validate_NotEmpty::IS_EMPTY => 'Proszę podać adres e-mail',
        ));
        $email->getValidator('StringLength')->setMessages(array(
            Zend_Validate_StringLength::TOO_LONG => 'Za długi adres e-mail',
            Zend_Validate_EmailAddress => 'Prosze podac poprawny e-mail',
//            Zend_Validate_EmailAddress::DOT_ATOM => 'Prosze podac porawny e-mail',
//            Zend_Validate_EmailAddress::INVALID => 'Prosze podac porawny e-mail',
//            Zend_Validate_EmailAddress::INVALID_FORMAT => 'Prosze podac porawny e-mail',
//            Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Prosze podac porawny e-mail',
//            Zend_Validate_EmailAddress::INVALID_LOCAL_PART => 'Prosze podac porawny e-mail',
//            Zend_Validate_EmailAddress::INVALID_FORMAT => 'Prosze podac porawny e-mail'
        ));
        
        $telefon->getValidator('NotEmpty')->setMessages(array(
            Zend_Validate_NotEmpty::IS_EMPTY => 'Proszę podać numer telefonu',
        ));
        $telefon->getValidator('StringLength')->setMessages(array(
            Zend_Validate_StringLength::TOO_LONG => 'Za długi numer telefonu',
        ));
        
        $captcha->getValidator('Zend_Captcha_Image')->setMessages(array(  
            Zend_Captcha_Word::MISSING_VALUE => 'Napis nie może być pusty',  
            Zend_Captcha_Word::MISSING_ID    => 'Brak pola Captcha w formularzu',  
            Zend_Captcha_Word::BAD_CAPTCHA   => 'Błędny napis',  
        ));  
        //$content->getValidator('NotEmpty')->setMessages(array(
        //    Zend_Validate_NotEmpty::IS_EMPTY => 'Proszę podać treść'
        //));
        
        $this->setMethod('post');
    }
}

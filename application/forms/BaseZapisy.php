<?php

class Form_BaseZapisy extends Zend_Form {

    public function init() {

        $this->setAttrib('id', 'zapisy');
        $imie = new Zend_Form_Element_Text('imie');
        $nazwisko = new Zend_Form_Element_Text('nazwisko');
        $email = new Zend_Form_Element_Text('email');
        $telefon = new Zend_Form_Element_Text('telefon');
        $uwagi = new Zend_Form_Element_Textarea('uwagi');
        $submit = new Zend_Form_Element_Submit('zapisz');
        $reset = new Zend_Form_Element_Reset('reset');
        
        $pesel = new Zend_Form_Element_Text('pesel');
        $miejsce_urodzenia = new Zend_Form_Element_Text('miejsce_urodzenia');
        $adres_zamieszkania = new Zend_Form_Element_Text('adres_zamieszkania');
        $obywatelstwo = new Zend_Form_Element_Text('obywatelstwo');
        $rodzaj_kursu = new Zend_Form_Element_Select('rodzaj_kursu');
        
        $submit->setOrder(100);
        $reset->setOrder(99);
        $this->addElement($imie);
        $this->addElement($nazwisko);
        
        $this->addElement($rodzaj_kursu);
        
        $this->addElement($email);
        $this->addElement($telefon);
        
        $this->addElement($pesel);
        $this->addElement($miejsce_urodzenia);
        $this->addElement($adres_zamieszkania);
        $this->addElement($obywatelstwo);
        
        $this->addElement($uwagi);
        $this->addElement($submit);
        $this->addElement($reset);
        
        $imie->setLabel('Imię*')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_NotEmpty(), true)
                ->addValidator(new Zend_Validate_StringLength(0, 50), true)
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());

        $nazwisko->setLabel('Nazwisko*')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_NotEmpty(), true)
                ->addValidator(new Zend_Validate_StringLength(0, 50), true)
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());

        $rodzaj_kursu->setLabel('Rodzaj kursu*')
                ->setRequired(true);
                
        
        $email->setLabel('E-mail*')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_NotEmpty(), true)
                ->addValidator(new Zend_Validate_StringLength(0, 70), true)
                ->addValidator(new Zend_Validate_EmailAddress())
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());

        $telefon->setLabel('Telefon*')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_NotEmpty(), true)
                ->addValidator(new Zend_Validate_StringLength(0, 11), true)
                ->addValidator(new Zend_Validate_Digits(), true)
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());
        
        $pesel->setLabel('Pesel')
                ->addValidator(new Zend_Validate_StringLength(11,11), true)
                ->addValidator(new Zend_Validate_Digits(), true)
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());
        
        $miejsce_urodzenia->setLabel('Miejsce urodzenia')
                ->addValidator(new Zend_Validate_StringLength(0, 50), true)
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());
        
        $adres_zamieszkania->setLabel('Adres zamieszkania')
                ->addValidator(new Zend_Validate_StringLength(0, 50), true)
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());
        
        $obywatelstwo->setLabel('Obywatelstwo')
                ->addValidator(new Zend_Validate_StringLength(0, 50), true)
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());
        
        
        
        $uwagi->setLabel('Dodatkowe informacje')
                ->addFilter(new Zend_Filter_StripTags())
                ->addFilter(new Zend_Filter_StringTrim());
        $submit->setLabel('Zapisz');
        $reset->setLabel('Wyczyść');
        //ustawienie dekoratorów formularza
        $this->clearDecorators();
		
		$this->setDecorators(array(
    array('ViewScript', array('viewScript' => 'partials/zapisy_form.phtml'))
));

$this->setElementDecorators(array(

            array('Errors')

        ));
		/*
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
        
        $reset->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit-group reset-group'))
        ));*/

        //ustawienie walidatorów formularza
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
        $email->getValidator('EmailAddress')->setMessages(array(
            Zend_Validate_EmailAddress::INVALID_FORMAT => 'Proszę podać prawidłowy adres e-mail',
        ));

        $email->getValidator('StringLength')->setMessages(array(
            Zend_Validate_StringLength::TOO_LONG => 'Za długi adres e-mail',
                //Zend_Validate_EmailAddress::INVALID => 'Prosze podac poprawny e-mail',
                //Zend_Validate_EmailAddress::INVALID => 'Prosze podac poprawny e-mail',
                //Zend_Validate_EmailAddress::DOT_ATOM => 'Prosze podac porawny e-mail',
                //Zend_Validate_EmailAddress::INVALID_FORMAT => 'Prosze podac porawny e-mail',
                //Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Prosze podac porawny e-mail',
                //Zend_Validate_EmailAddress::INVALID_LOCAL_PART => 'Prosze podac porawny e-mail',
                //Zend_Validate_EmailAddress::INVALID_FORMAT => 'Prosze podac porawny e-mail'
        ));

        $telefon->getValidator('NotEmpty')->setMessages(array(
            Zend_Validate_NotEmpty::IS_EMPTY => 'Proszę podać numer telefonu',
        ));
        $telefon->getValidator('Digits')->setMessages(array(
            Zend_Validate_Digits::NOT_DIGITS => 'Proszę podać cyfry',
        ));
        $pesel->getValidator('Digits')->setMessages(array(
            Zend_Validate_Digits::NOT_DIGITS => 'Proszę podać cyfry',
        ));
        
        $pesel->getValidator('StringLength')->setMessages(array(
            Zend_Validate_StringLength::TOO_LONG => 'Za długi pesel',
            Zend_Validate_StringLength::TOO_SHORT => 'Za krótki pesel'
        ));
        
        $telefon->getValidator('StringLength')->setMessages(array(
            Zend_Validate_StringLength::TOO_LONG => 'Za długi numer telefonu',
        ));

        $this->setMethod('post');
    }

}

<?php

class Form_Login extends Zend_Form
{
    public function init()
    {        
        $this->addElement('text', 'login', array(
            'filters' => array(
                'StringTrim'
            ),
            'label' => 'Login:'
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Hasło:'
        ));
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Zaloguj'
        ));
    }
}
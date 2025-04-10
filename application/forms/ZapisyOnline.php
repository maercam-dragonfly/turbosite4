<?php
/**
 * Description of ZapisyOnline
 *
 * @author Marcin
 */
class Form_ZapisyOnline extends Form_BaseZapisy{
    public function init()
    {
        parent::init();
        
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
        
        if(APPLICATION_ENV != 'development'){
           
            $this->addElement($captcha);
        }else{
             echo APPLICATION_ENV;
        }
        $captcha->setDecorators(array(
            array('Captcha'),
            array('Errors'),
            array('Label', array('tag' => 'div')),
            array('FormElements', array('tag' => 'div')),
            array('Description', array('escape' => false)),
            array('HtmlTag', array('tag' => 'div', 'class' => 'element-group', 'id' => 'captcha'))
        ));
         $captcha->getValidator('Zend_Captcha_Image')->setMessages(array(  
            Zend_Captcha_Word::MISSING_VALUE => 'Napis nie może być pusty',  
            Zend_Captcha_Word::MISSING_ID    => 'Brak pola Captcha w formularzu',  
            Zend_Captcha_Word::BAD_CAPTCHA   => 'Błędny napis',  
        )); 
        
    }
}



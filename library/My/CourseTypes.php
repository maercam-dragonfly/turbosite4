<?php

class My_CourseTypes
{
    const KatB = 'kurs prawa jazdy kat. B';
    const DodatkowyJazda = 'kurs dodatkowy (5h jazdy) + zaświadczenie';
    const DodatkowyTeoria = 'kurs dodatkowy (10h teorii) + zaświadczenie';
    const JazdyUzpupel = 'jazdy uzupełniające';
    
    private static $_courseTypes;
    
    public static function init(){
        self::$_courseTypes = array(
            self::KatB,
            self::JazdyUzpupel
            );
    }
    private static $_courseMail = array(
        'zapisyKursKatBMail',
        'zapisyKursDodatkowyJazdaMail',
        'zapisyKursDotatkowyTeoriaMail',
        'zapisyJazdyUzupelniajaceMail'
    );
    private static $_courseTypesMailTitles = array(
        'Zapis na kurs prawa jazdy',
        'Zapis na kurs dodatkowy',
        'Zapis na kurs dodatkowy',
        'Zapis na jazdy doszkalające'
        
    );

    public static function getAllCourseTypes(){
        return self::$_courseTypes;    
    }
    
    /**
     * 
     * @param type $id
     * @return string
     * @assert (0) == 'kurs prawa jazdy kat. B'
     */
    public static function getCourseTypeById($id){   
        return self::$_courseTypes[$id];
    }
    
    public static function getMailTitleById($id){   
        return self::$_courseTypesMailTitles[$id];
    }
    
    public static function getMailContentNameByCourseTypeId($id){
        $prefix = 'partials/mail/';
        $sufix = '.phtml';
        $name = self::$_courseMail[$id];
        return $prefix.$name.$sufix;           
    }
}
My_CourseTypes::init();


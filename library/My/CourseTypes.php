<?php

class My_CourseTypes
{
    const KatB = 'kurs prawa jazdy kat. B';
    const DodatkowyJazda = 'kurs dodatkowy (5h jazdy) + zaświadczenie';
    const DodatkowyTeoria = 'kurs dodatkowy (10h teorii) + zaświadczenie';
    const JazdyUzpupel = 'jazdy uzupełniające';
    const Pytanie = 'pytanie';
	
	private static $_mapping = [
    0 => 'kurs',
	1 => 'jazdy',
    2 => 'pytanie'
];
	
    private static $_courseTypes;
    
    public static function init(){
        self::$_courseTypes = array(
            self::KatB,
			self::DodatkowyJazda,
			self::DodatkowyTeoria,
            self::JazdyUzpupel,
			self::Pytanie
            );
    }
    private static $_courseMail = array(
        'zapisyKursKatBMail',
        'zapisyKursDodatkowyJazdaMail',
        'zapisyKursDotatkowyTeoriaMail',
        'zapisyJazdyUzupelniajaceMail',
		'zapisyPytanieMail'
    );
    private static $_courseTypesMailTitles = array(
        'Zapis na kurs prawa jazdy',
        'Zapis na kurs dodatkowy',
        'Zapis na kurs dodatkowy',
        'Zapis na jazdy doszkalające',
		'Zadanie pytania'
    );

    public static function getAllCourseTypes(){
        return self::$_courseTypes;    
    }
	
    public static function getCourseTypeByMappingId($id){   
	
		if(isset(self::$_mapping[$id])){
			return self::$_mapping[$id];
		} else {
			return '';
		}
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


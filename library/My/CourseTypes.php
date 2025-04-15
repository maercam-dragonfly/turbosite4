<?php

class My_CourseTypes
{
    const KatB = 'kurs prawa jazdy kat. B';
	const JazdyUzpupel = 'jazdy uzupełniające';
    const Pytanie = 'pytanie';
    const DodatkowyJazda = 'kurs dodatkowy (5h jazdy) + zaświadczenie';
    const DodatkowyTeoria = 'kurs dodatkowy (10h teorii) + zaświadczenie';
	
	private static $_mapping = [
    0 => 'kurs',
	1 => 'jazdy',
    2 => 'pytanie'
];
	
    private static $_courseTypes;
    
    public static function init(){
        self::$_courseTypes = array(
            self::KatB,
			self::JazdyUzpupel,
			self::Pytanie,
            self::DodatkowyJazda,
			self::DodatkowyTeoria
            );
    }
    private static $_courseMail = array(
        'zapisyKursKatBMail',
        'zapisyJazdyUzupelniajaceMail',
        'zapisyPytanieMail',
        'zapisyKursDotatkowyTeoriaMail', 
		'zapisyKursDotatkowyTeoriaMail' 
    );
    private static $_courseTypesMailTitles = array(
        'Zapis na kurs prawa jazdy - OSK AUTO TURBO',
        'Zapis na jazdy doszkalające - OSK AUTO TURBO',
        'Zadanie pytania - OSK AUTO TURBO',
        'Zapis na kurs dodatkowy',
		'Zapis na kurs dodatkowy'
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


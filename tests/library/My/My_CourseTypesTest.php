<?php

class My_CourseTypesTest extends SmartGroupTestCase {

    protected function setUp() {
        parent::setUp();
    }

    function providertestGetCourseTypeById(){
        return array(
            array(0, My_CourseTypes::KatB),
            array(1, My_CourseTypes::DodatkowyJazda),
            array(2, My_CourseTypes::DodatkowyTeoria),
            array(3, My_CourseTypes::JazdyUzpupel)
        );
    }

    /**
     * @covers My_CourseTypes::getCourseTypeById
     * @dataProvider providertestGetCourseTypeById
     */
    public function testGetCourseTypeById($id,$text) {
        $this->assertEquals(
                $text
                , My_CourseTypes::getCourseTypeById($id)
        );
    }

    /**
     * @covers My_CourseTypes::getAllCourseTypes
     */
    public function testGetAllCourseTypes() {
        $expectedResult = array(
            My_CourseTypes::KatB,
            My_CourseTypes::DodatkowyJazda,
            My_CourseTypes::DodatkowyTeoria,
            My_CourseTypes::JazdyUzpupel
        );
        $result = My_CourseTypes::getAllCourseTypes();
        
        $this->assertEquals($result,$expectedResult);
    }
    
    function providerGetMailContentNameByCourseTypeId(){
        return array(
            array(0,'Szanowni Państwo',true),
            array(1,'jazda',true),
            array(2,'teoria',true),
            array(3,'uzupelniajace',true),
        );
    }
    //TODO sprawdzanie tematu
    /**
     * @covers My_CourseTypes::getMailContentNameByCourseTypeId
     * @dataProvider providerGetMailContentNameByCourseTypeId
     */
    public function testGetMailContentNameByCourseTypeId($id,$text,$expectedResult) {
        $this->assertEquals(
                self::contains(
                        $text, 
                        self::getContentMail(
                        My_CourseTypes::getMailContentNameByCourseTypeId($id)
                                )),
                $expectedResult);
    }
    
    static function contains($substring, $string) {
        $pos = strpos($string, $substring);
        if($pos === false)return false;
        else return true;
    }
    
    static function getContentMail($nameContentMail){
        $basePath = APPLICATION_PATH . '/modules';
        $helper = new Zend_View_Helper_Partial();
        Zend_Controller_Front::getInstance()->resetInstance();
        $view = new Zend_View(array(
            'scriptPath' => $basePath . '/default/views/scripts'
        ));
        $helper->setView($view);
        $part = $helper->partial($nameContentMail,array('includeStyles' => false));
        return $part;
    }

}

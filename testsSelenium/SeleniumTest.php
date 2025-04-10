<?php

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

/**
 * Description of newSeleneseTest
 *
 * @author Marcin
 */
class SeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp()
    {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://autoturbo.pl/');
    }

    function providerZapisy() {
        $baseData = array(
                'imie' => 'marcin',
                'nazwisko' => 'wrona',
                'rodzaj_kursu' => '0',
                'telefon' => '111222333',
                'pesel' => '12345678901',
                'miejsce_urodzenia' => 'Łańcut',
                'adres_zamieszkania' => 'ala',
                'obywatelstwo' => 'Polskie',
                'uwagi' => 'Jakaś uwaga'
            );
        $cases = array();
        for($i = 0; $i < 4; $i++){
            $baseData['rodzaj_kursu'] = $i;
            $cases[$i] =  $baseData;
        }
        
        return $cases;
    }
   
    /** 
     * @dataProvider providerZapisy
     */
    public function testZapisy(
            $imie,
            $nazwisko,
            $rodzaj_kursu,
            $telefon,
            $pesel,
            $miejsce_urodzenia,
            $adres_zamieszkania,
            $obywatelstwo,
            $uwagi
    ) {
        $email = "marcin.wrona1@wp.pl";
        $this->deleteUser($email);
        $this->open("/zapisy");
        $this->waitForPageToLoad("30000");
        $this->type("id=imie", $imie);
        $this->type("id=nazwisko", $nazwisko);
        $this->select("id=rodzaj_kursu", "value=" . $rodzaj_kursu);
        $this->type("id=email", $email);
        $this->type("id=telefon", $telefon);
        $this->type("id=pesel", $pesel);
        $this->type("id=miejsce_urodzenia", $miejsce_urodzenia);
        $this->type("id=adres_zamieszkania", $adres_zamieszkania);
        $this->type("id=obywatelstwo", $obywatelstwo);
        $this->type("id=uwagi", $uwagi);
        $this->click("id=zapisz");
        $this->waitForPageToLoad("30000");
        $this->assertText('id=message-title','Zostałeś zapisany na kurs');
        $this->click("css=button.przycisk");
    }
    
    public function deleteUser($email)
    {
        $this->open("/zbardzodluganazwapaneluadministratora/index/login");
        $this->type("id=login", "franek");
        $this->type("id=password", "ala12345");
        $this->click("id=submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=a[title=\"Zapisy\"] > span");
        $this->waitForPageToLoad("30000");
        if ($this->isTextPresent($email)) 
        {
            $this->click("link=Usuń");
            $this->waitForPageToLoad("30000");
        }
        $this->click("link=Wyloguj się");
        $this->waitForPageToLoad("30000");
    }
    
}


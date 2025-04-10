<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of My_DateConverter
 *
 * @author Marcin
 */
class My_DateConverter {
    public static function FormToDatabaseFormat($date){
        $tab = explode('.', $date);
        $result = $tab[2].'-'.$tab[1].'-'.$tab[0].' 00:00:00';
        return $result;
 
    }
     
    public static function DatabaseToForm($date){
        return date("d.m.Y",strtotime($date));
    }
}



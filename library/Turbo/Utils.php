<?php

class Turbo_Utils {

    public static function makeDir($path) {
        return is_dir($path) || mkdir($path);
    }
    
    public static function generateUniqueFileName( $path, $fileName){
       // echo 'path: ' . $path;
      //  echo 'fileName:  ' . $fileName;
        if(!file_exists($path))
        {
            echo 'path not exist';
            exit();
           
        }
        
        if(file_exists($path . $fileName)){
           // echo 'tuta';
           
            $fileNameParts = explode('.', $fileName);
            
            $name = $fileNameParts[0];
           
            
            $ext = $fileNameParts[1];
            do{
                $name .= '_re';
                //echo $name;
                 
            }while(file_exists($path . $name . '.' . $ext ));
                
            $fileName = $name . '.' . $ext;
        }
       // echo $fileName;
        return $fileName;
    }

}

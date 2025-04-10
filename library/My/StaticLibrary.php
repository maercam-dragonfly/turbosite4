<?php

class My_StaticLibrary {

    static public function clearCaptcha() {
        $path = APPLICATION_PATH . '/../public/img/captcha/';
        $dh = opendir($path);
        while ($file = readdir($dh)) {
            if ($file == '.' || $file == '..' || is_dir($file)) {
                continue;
            }
            unlink($path . $file);
        }
    }
    
    static public function generateLink(){ 
        $salt2 = md( md5(time()).md5(time()) );
    }
    
    public static function generateGalery($path_img, $path_mini)
  {
      try
      {
           $dir = new DirectoryIterator($path_img);
           foreach($dir as $file)
           {
              // Pomiń pozycje "." oraz ".."
              if($file->isDot())
              {
                 continue;
              }
              if($file->isDir())
              {
                 //echo 'Katalog '.$file.'<br/>';
              }
              else
              {
                 //echo 'Plik '.$file.'<br/>';
                  
                 $name_roz =  explode('.', $file);
                 
                 $min_name = $name_roz[0].'.th.'.$name_roz[1];
                 //echo $min_name;
                 if(!file_exists($path_mini.$min_name))
                 {
                    
                     $mini_tmp = My_StaticLibrary::generateThumbnail($path_img.$file,96,96);
                     //echo '<br/>'.$mini_tmp;
                     rename($mini_tmp, $path_mini.$min_name);
                 }
                 else
                     continue;
              }
           }
        }

        catch(UnexpectedValueException $exception)
        {
           echo 'Błąd: '.$exception->getMessage();
        }
  }
  /* public static function displayGallery($path_img,$path_mini){
       
       $dir = new DirectoryIterator($path_img);
   foreach($dir as $file)
   {
      // Pomiń pozycje "." oraz ".."
      if($file->isDot())
      {
         continue;
      }
      if(!$file->isDir())
      {
         
         $name_roz =  explode('.', $file);
         $min_name = $name_roz[0].'.th.'.$name_roz[1];

         if(file_exists($path_mini.$min_name))
         {

            ?> <a class="grouped_elements" rel="group1" href="/img/obrazy/<?php echo  $file; ?>"><img border="0" src="/img/obrazy/mini/<?php echo  $min_name; ?>" alt="" /></a>
        <?php
         }
         else
             continue;
      }
   }
   }*/
   static function generateThumbnail($fname, $thwidth, $thheight)
   {
 
      $src = imagecreatefromjpeg($fname);
      list($imgwidth,$imgheight)=getimagesize($fname);
      $imgratio = $imgwidth / $imgheight;
      $thratio = $thwidth / $thheight;


      if ($imgratio < $thratio)
      {
         $newwidth = $thheight * $imgratio;
         $offsetx = ($thwidth - $newwidth) / 2;
         $newheight = $thheight;
         $offsety = 0;
      }
      else
      {
         $newwidth = $thwidth;
         $offsetx = 0;
         $newheight = $thwidth / $imgratio;
         $offsety = ($thheight - $newheight) / 2;
      }

      $tmp=imagecreatetruecolor($thwidth,$thheight);

      //$white = imagecolorallocate($tmp, 221, 221, 221);
      $white = imagecolorallocate($tmp, 255, 255, 255);
      imagefill($tmp, 0, 0, $white);

      imagecopyresampled($tmp,$src,$offsetx,$offsety,0,0,$newwidth,$newheight,$imgwidth,$imgheight);

      $p = strpos($_SERVER['REQUEST_URI'], 'index.php');
      
      $subpath = './img/gallery/mini/';
      $tmpfilename = $subpath.'temp.jpg';
 
      imagejpeg($tmp,$tmpfilename,100);
      imagedestroy($src);
      imagedestroy($tmp);

      return $tmpfilename;

   }
   

}
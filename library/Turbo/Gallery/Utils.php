<?php

/**
 *
 * @author Marcin
 */
class Turbo_Gallery_Utils {

    public static function gdThumbnailObj($AFileName, $AWidth, $AHeight) {
        $AImg = ImageCreateFromJPEG($AFileName);
        if (!$AImg) {
            die('gd_thumbnail_obj() - $AImg error');
        }
        $AImg_X = ImageSX($AImg);
        $AImg_Y = ImageSY($AImg);

        $tmp_Y = ($AWidth / $AImg_X) * $AImg_Y;
        $tmp_X = ($AHeight / $AImg_Y) * $AImg_X;

        if ($tmp_Y <= $AHeight) {
            $thumbnail_X = $AWidth;
            $thumbnail_Y = $tmp_Y;
        } else {
            $thumbnail_X = $tmp_X;
            $thumbnail_Y = $AHeight;
        }

        $thumbnail = ImageCreateTrueColor(
                $thumbnail_X, $thumbnail_Y
        );

        imageAlphaBlending($thumbnail, false);
        imageSaveAlpha($thumbnail, true);

        ImageCopyResized(
                $thumbnail, $AImg, //miejsce docelowe, źródło  
                0, 0, //gdzie ma trafić w miejscu docelowym  
                0, 0, //skąd ma pochodzić ze źródła  
                $thumbnail_X, $thumbnail_Y, //wymiary, jakie ma zająć w miejscu docelowym  
                $AImg_X, $AImg_Y            //wymiary pobierane ze źródła  
        );

        return $thumbnail;
    }

    public static function gdThumbnailFile(
    $AFileName, $AWidth, $AHeight, $destFilename, $isSquare = false, $quality = 95
    ) {
        $mini = null;
        if ($isSquare) {
            $mini = self::generateSquareThumbnail($AFileName, $AWidth, $AHeight);
        } else {
            $mini = self::gdThumbnailObjHighQuality($AFileName, $AWidth, $AHeight);
        }
        imagejpeg($mini, $destFilename, $quality);
    }

    public static function gdThumbnailObjHighQuality($fname, $thwidth, $thheight) {

        $src = imagecreatefromjpeg($fname);
        list($imgwidth, $imgheight) = getimagesize($fname);
        $imgratio = $imgwidth / $imgheight;
        $thratio = $thwidth / $thheight;


        if ($imgratio < $thratio) {
            $newwidth = $thheight * $imgratio;
            $offsetx = ($thwidth - $newwidth) / 2;
            $newheight = $thheight;
            $offsety = 0;
        } else {
            $newwidth = $thwidth;
            $offsetx = 0;
            $newheight = $thwidth / $imgratio;
            $offsety = ($thheight - $newheight) / 2;
        }

        $tmp = imagecreatetruecolor($thwidth, $thheight);
        $white = imagecolorallocate($tmp, 255, 255, 255);
        imagefill($tmp, 0, 0, $white);

        imagecopyresampled($tmp, $src, $offsetx, $offsety, 0, 0, $newwidth, $newheight
                , $imgwidth, $imgheight);
        return $tmp;
    }

    private static function generateSquareThumbnail($fname, $thwidth, $thheight) {

        list($orig_width, $orig_height) = getimagesize($fname);
        $newthumb_width = $thwidth;
        $newthumb_height = $thheight;



        $width_ratio = ($newthumb_width / $orig_width );
        $height_ratio = ($newthumb_height / $orig_height);

        if ($orig_width > $orig_height) {
            $crop_width = round($orig_width * $height_ratio);
            $crop_height = $newthumb_height;

            $offsetx = (int)(($orig_width - $orig_height)/ 2);
            $offsety = 0;
        } elseif ($orig_width < $orig_height) {
            $crop_height = round($orig_height * $width_ratio);
            $crop_width = $newthumb_width;

            $offsetx = 0;
            $offsety = (int) (($orig_height - $orig_width)/ 2);
        } else {
            $crop_width = $newthumb_width;
            $crop_height = $newthumb_height;

            $offsetx = 0;
            $offsety = 0;
        }

        $source_img = imagecreatefromjpeg($fname);
        $dest_img = imagecreatetruecolor($newthumb_width, $newthumb_height);

        imagecopyresampled($dest_img, $source_img, 0, 0, $offsetx, $offsety, $crop_width, $crop_height, $orig_width, $orig_height);
        return $dest_img;
    }

    public static function getUniqueId() {
        return uniqid();
    }

}


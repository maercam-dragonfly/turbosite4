<?php

/**
 * Description of Dir
 * @author Marcin
 */
class Turbo_IO_Dir {

    public static function deleteDirectory($dir) {

        if (!@rmdir($dir)) {
            $dirIt = new DirectoryIterator($dir);
            foreach ($dirIt as $file) {
                if ($file->isDot())
                    continue;
                $filePath = $file->getPathname();
                if ($file->isDir()) {
                    self::deleteDirectory($filePath);
                } else {
                    @unlink($filePath);
                }
            }
        }
        @rmdir($dir);
    }

    public static function makeDirectory($path) {
        return is_dir($path) || mkdir($path);
    }

}


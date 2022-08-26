<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Utils
{
    /**
     * @return int
     */
    static public function getStorageFilesSize()
    {
        $userFilesList = storage::disk('local')->allFiles('user_storage_' . Auth::user()->id);
        $totalFilesSize = 0;
        foreach ($userFilesList as $file) {
            $totalFilesSize += Storage::size($file);
        }
        return $totalFilesSize;
    }

    /**
     * @param $folderName
     * @return int
     */
    static public function getFolderFilesSize($folderName)
    {
        $userFilesList = storage::disk('local')->allFiles('user_storage_' . Auth::user()->id . '/' . $folderName);
        $totalFilesSize = 0;
        foreach ($userFilesList as $file) {
            $totalFilesSize += Storage::size($file);
        }
        return $totalFilesSize;
    }

    /**
     * @param $size
     * @return string|void
     */
    static public function convert_bytes($size)
    {

        $i = 0;

        while (floor($size / 1024) > 0) {
            ++$i;
            $size /= 1024;
        }

        $size = str_replace('.', ',', round($size, 1));

        switch ($i) {
            case 0:
                return $size .= ' байт';
            case 1:
                return $size .= ' КБ';
            case 2:
                return $size .= ' МБ';
        }
    }
}

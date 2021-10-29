<?php

namespace App;

use Exception;
use Illuminate\Support\Facades\File as FacadesFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class File
{
    public static function write($image, $path)
    {
        if($image != null)
        {
            try{
                $img = $image;
                $name = time().''.Str::lower(Str::random(8)).'.'.explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];
                Image::make($image)->save(public_path($path.'/'.$name));
                return $name;
            }catch(Exception $e)
            {
                return null;
            }
        }
        return null;
    }

    public static function newFile($file, $path, $newName=null)
    {

        if($file != null)
        {
            try{
                $data = $file;
                $name = $newName == null ? time().''.Str::lower(Str::random(8)).'.'.explode('/', explode(':', substr($data, 0, strpos($data, ';')))[1])[1] : $newName;
                $destinationPath = public_path($path) . '/'.$name;
                if(file_put_contents($destinationPath, file_get_contents($file))){
                    return $name;
                }
            }catch(Exception $e)
            {
                return null;
            }
        }
        return null;
    }

    public static function remove($path, $name)
    {
        $file_path = public_path($path.'/'.$name);
        if (FacadesFile::exists($file_path)){
            FacadesFile::delete($file_path);
        }
    }

    public static function image($data, $path=null)
    {
        return static::write($data, $path == null ? 'assets/images' : $path);
    }

    public static function thumnail($data, $type)
    {
        return static::write($data, 'assets/thumnails/'.$type);
    }

    public static function pdf($data)
    {
        return static::newFile($data, 'pdfs');
    }

    public static function audio($data)
    {
        return static::newFile($data, 'audios');
    }

    public static function video($data)
    {
        return static::newFile($data, 'assets/videos');
    }

    public static function comic($data)
    {
        return static::newFile($data, 'assets/comics');
    }

    public static function apps($data, $name=null)
    {
        return static::newFile($data, 'out/app', $name);
    }

    public static function deleteImage($name, $path=null)
    {
        return static::remove($path == null ? 'assets/images' : $path, $name);
    }

    public static function deleteVideo($name)
    {
        return static::remove('assets/videos', $name);
    }

    public static function deleteComic($name)
    {
        return static::remove('assets/comics', $name);
    }

    public static function deleteApp($name)
    {
        return static::remove('out/app', $name);
    }

    public static function deleteThumnail($name, $type)
    {
        return static::remove('assets/thumnails/'.$type, $name);
    }
}

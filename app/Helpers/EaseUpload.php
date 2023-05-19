<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Image;

class EaseUpload
{

    public static $baseuri = '';
    public static $defaultpath = '/data/default/';
    public static $uploadpath = '/data/uploaded/';
    public static $placeholderpath = '/data/placeholder/';

    public function __construct()
    {
        self::$baseuri = "http://{$_SERVER['HTTP_HOST']}";
    }

    /**
     * images
     *
     * @param Eloquent $model
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|\Symfony\Component\HttpFoundation\File\UploadedFile[] $files
     * @param mixed $options
     */
    public static function images($model, $files, $options = null)
    {
        if (!isset($model) || !isset($files) || is_null($files))
            return;

        $baseuri = self::$baseuri;
        $uploadpath = self::$uploadpath;
        $prefix = $model->getTable();
        $id = $model->id ? $model->id : 0;

        if (count($files) > 0) {
            foreach ($files as $name => $file) {
                if ($file == '')
                    continue;

                $basename = "{$prefix}-{$name}-{$id}";
                $basepath = public_path() . $uploadpath;
                //$extension = strtolower($file->getClientOriginalExtension());
                $extension = $file->guessExtension();

                if (isset($options) && isset($options->{$name})) {
                    $item = $options->{$name};
                    $file->move($basepath, "{$basename}.{$extension}");
                    $path = "{$basepath}{$basename}.{$extension}";
                    $time = time();


                    foreach ($item as $option) {
                        switch ($option->action) {
                            case 'grab':
                                $image = Image::make($path);
                                if (strtolower($extension) == 'jpeg' || strtolower($extension) == 'jpg')
                                    self::correctExif($image);

                                $image
                                    ->fit($option->width, $option->height)
                                    ->save($path);
                                $model->{$name} = "{$baseuri}{$uploadpath}{$basename}.{$extension}?v={$time}";
                                break;
                            case 'thumbnail':
                                $image = Image::make($path);
                                if (strtolower($extension) == 'jpeg' || strtolower($extension) == 'jpg')
                                    self::correctExif($image);

                                if (isset($option->ratio)) {
                                    $ratio = true;
                                    $width = $image->width() * $option->ratio;
                                    $height = $image->height() * $option->ratio;
                                } else {
                                    $ratio = false;
                                    $width = $option->width;
                                    $height = $option->height;
                                }


                                // save width and height of original image ******
                                if (isset($option->width_field))
                                    $model->{$option->width_field} = $image->width();

                                if (isset($option->height_field))
                                    $model->{$option->height_field} = $image->height();

                                $image
                                    ->resize($width, $height)
                                    ->save("{$basepath}{$basename}-thumb.{$extension}");

                                $model->{$option->target} = "{$baseuri}{$uploadpath}{$basename}-thumb.{$extension}?v={$time}";
                                break;
                            case 'itself':
                                $model->{$name} = "{$baseuri}{$uploadpath}{$basename}.{$extension}?v={$time}";
                                break;
                            case 'doc':
                                if (isset($option->file_name_field))
                                    $model->{$option->file_name_field} = $file->getClientOriginalName();


                                $model->{$name} = "{$baseuri}{$uploadpath}{$basename}.{$extension}?v={$time}";
                                break;
                        }
                    }
                } else {

                    $time = time();
                    $file->move($basepath, "{$basename}.{$extension}");
                    $model->{$name} = "{$baseuri}{$uploadpath}{$basename}.{$extension}?v={$time}";
                }
            }
        }
    }

    public static function placeholder(Eloquent $model, $prefix = null)
    {

        $candidates = array(
            'photo_1' => 'photo',
            'photo_1_thumb' => 'photo-thumb',
            'photo_2' => 'photo',
            'photo_2_thumb' => 'photo-thumb',
            'photo_3' => 'photo',
            'photo_3_thumb' => 'photo-thumb',
            'photo_4' => 'photo',
            'photo_5_thumb' => 'photo-thumb',
            'photo_5' => 'photo',
            'photo_5_thumb' => 'photo-thumb',
            'icon' => 'icon',
            'icon_thumb' => 'icon-thumb'
        );

        $prefix = !is_null($prefix) ? $prefix : $model->getTable();

        foreach ($candidates as $key => $candidate) {
            if (!isset($model->{$key})) {
                continue;
            } else if ($model->{$key} == '') {
                $basename = "{$prefix}-{$candidates[$key]}-default";
                $model->{$key} = self::$baseuri . self::$placeholderpath . "{$basename}.png";
            }
        }
    }

    public static function unlink($model)
    {
        $candidates = array(
            'photo_1', 'photo_2', 'photo_3', 'photo_4', 'photo_5', 'photo_6', 'photo_7', 'photo_8', 'photo_9', 'photo_10',
            'photo_1_thumb', 'photo_2_thumb', 'photo_3_thumb', 'photo_4_thumb', 'photo_5_thumb', 'photo_6_thumb', 'photo_7_thumb', 'photo_8_thumb', 'photo_9_thumb', 'photo_10_thumb',
            'icon', 'icon_thumb', 'banner', 'banner_thumb',
            'file_1', 'file_2', 'file_3', 'file_4', 'file_4', 'video_file_1',
            'file'
        );
        foreach ($candidates as $candidate) {
            if (is_null($model->{$candidate}) || $model->{$candidate} == '')
                continue;

            $parsedURL = parse_url($model->{$candidate});
            $basepath = $parsedURL['path'];
            $pathinfo = pathinfo($basepath);
            $dirname = $pathinfo['dirname'];

            if ("{$dirname}/" == self::$uploadpath) {
                $path = public_path() . $basepath;
                @unlink($path);
            }
        }
    }

    public static function unlinkImageByFieldName(\Illuminate\Database\Eloquent\Model $model, $candidates)
    {

        foreach ($candidates as $candidate) {
            if (is_null($model->{$candidate}) || $model->{$candidate} == '')
                continue;

            $parsedURL = parse_url($model->{$candidate});
            $basepath = $parsedURL['path'];
            $pathinfo = pathinfo($basepath);
            $dirname = $pathinfo['dirname'];

            if ("{$dirname}/" == self::$uploadpath) {
                $path = public_path() . $basepath;
                @unlink($path);
            }
        }
    }

    /**
     * get exif-corrected src from jpeg
     * @param string $path
     * @return resource
     */
    private static function correctExif($img)
    {
        $orientation = $img->exif('Orientation');

        if (!empty($orientation))
            return $img;

        switch ($orientation) {
            case 3:
                $img->rotate(180);
                break;
            case 6:
                $img->rotate(-90);
                break;
            case 8:
                $img->rotate(90);
                break;
        }
        return $img;
    }

    public static function save_image_from_base64($model, $file, $name)
    {
        $baseuri = self::$baseuri;
        $uploadpath = self::$uploadpath;
        $prefix = $model->getTable();
        $id = $model->id ? $model->id : 0;


        $basename = "{$prefix}-{$id}-{$name}";
        $basepath = public_path() . $uploadpath;
//$extension = strtolower($file->getClientOriginalExtension());
        $extension = self::getExtantionOfBase64($file);


        $time = time();

        Image::make(file_get_contents($file))->save("{$basepath}{$basename}.{$extension}");


        $file_path = "{$baseuri}{$uploadpath}{$basename}.{$extension}?v={$time}";
        $model->{$name} = "{$baseuri}{$uploadpath}{$basename}.{$extension}?v={$time}";
        return $file_path;
    }


    public static function getExtantionOfBase64($str)
    {

        // $str should start with 'data:' (= 5 characters long!)
        return substr($str, 11, strpos($str, ';') - 11);
    }

}

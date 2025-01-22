<?php

/**
 *
 *  MVC
 *  Model View Controller (MVC) design pattern for simple web applications.
 *
 *  @see     https://github.com/fabiodoppio/mvc
 *
 *  @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 *  @license https://opensource.org/license/mit/ MIT License
 *
 */


namespace MVC;

use MVC\App         as App;
use MVC\Exception   as Exception;

/**
 *
 *  Uploader Class
 *
 *  The Uploader class provides methods for uploading users avatars and attachments from contact forms.
 *
 */
class Uploader {

    /**
     *  @var    int     ATTACHMENT      Constant representing an attachment upload
     */
    public const ATTACHMENT = 1;

    /**
     *  @var    int     AVATAR          Constant representing an avatar upload
     */
    public const AVATAR = 2;


    /**
     *
     *  Uploads users avatars and attachments from contact forms.
     *
     *  @since  2.0
     *  @param  array   $file       File array from upload
     *  @param  int     $type       File type, Attachment or Avatar
     *  @return                     Generated name of the uploaded file
     *
     */
     public static function upload(array $file, int $type) {
        if (!$file["name"] || !$file["size"] || !$file["tmp_name"])
            return "";

        $dirname    = App::get("DIR_MEDIA");
        $basename   = pathinfo($file["name"], PATHINFO_BASENAME);
        $extension  = pathinfo($file["name"], PATHINFO_EXTENSION);
        $imagesize  = getimagesize($file["tmp_name"]);
        $filetype   = mime_content_type($file["tmp_name"]);

        switch($type) {
            case self::ATTACHMENT:
                if (!in_array($filetype, ["image/jpeg", "image/jpg", "image/png", "application/pdf", "text/plain"]))
                    throw new Exception(_("File has to be a jpeg, jpg, png, pdf or txt."), 1400);

                if ($file["size"] > 12582912)
                    throw new Exception(sprintf(_("File exceeds the maximum allowed file size of %s MB."), 12), 1401);

                $dirname  = $dirname."/uploads";
                $basename = "upload_".bin2hex(random_bytes(9));
                break;
            case self::AVATAR:
                if (!in_array($filetype, ["image/jpeg", "image/jpg", "image/png"]))
                    throw new Exception(_("File has to be a jpeg, jpg or png."), 1402);

                if ($file["size"] > 3145728)
                    throw new Exception(sprintf(_("File exceeds the maximum allowed file size of %s MB."), 3), 1403);

                if ($imagesize[0] != $imagesize[1])
                    throw new Exception(_("Avatar has to be squared."), 1404);

                if (in_array($filetype, ["image/jpeg", "image/jpg"]))
                    $img = imagecreatefromjpeg($file["tmp_name"]);
                elseif (in_array($filetype, ["image/png"]))
                    $img = imagecreatefrompng($file["tmp_name"]);

                $new_img = imagecreatetruecolor(150, 150);
                imagecopyresampled($new_img, $img, 0, 0, 0, 0, 150, 150, $imagesize[0], $imagesize[1]);
                imagewebp($new_img, $file["tmp_name"], 100);

                $dirname   = $dirname."/avatars";
                $basename  = "avatar_".bin2hex(random_bytes(9));
                $extension = "webp";
                break;
        }

        if (!is_dir($dir = App::get("DIR_ROOT").$dirname))
            mkdir($dir, 0777, true);

        if (file_exists(App::get("DIR_ROOT").$dirname."/".$basename.".".$extension))
            $basename .= "_copy";

        if (!move_uploaded_file($file["tmp_name"], App::get("DIR_ROOT").$dirname."/".$basename.".".$extension))
            throw new Exception(_("File could not be uploaded."), 1405);

        return $basename.".".$extension;
    }

}

?>
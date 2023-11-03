<?php

/**
 * mvc
 * Model View Controller (MVC) design pattern for simple web applications.
 *
 * @see     https://github.com/fabiodoppio/mvc
 *
 * @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 * @license https://opensource.org/license/mit/ MIT License
 */


namespace MVC;

/**
 * Upload Class
 *
 * The Upload class provides methods for handling file uploads, including validation and storing files.
 */
class Upload {

    /**
     * The filename of the uploaded file.
     *
     * @var string
     */
    private $filename;

    /**
     * Construct an Upload object for handling file uploads.
     *
     * @param   array   $file       The file information from the $_FILES array.
     * @param   string  $prefix     (Optional) Prefix to prepend to the generated filename.
     * @throws                      Exception If there is an issue with the file upload, such as an error, invalid file type, or file size exceeding the limit.
     */
    public function __construct(array $file, string $prefix = "") {
        if ($file['error'] !== 0)
            throw new Exception(_("There was a problem uploading your file."));
        
        if (!in_array(mime_content_type($file['tmp_name']), App::get("APP_UPLOAD_TYPES")))
            throw new Exception(_("Your file type is not allowed."));

        if ($file["size"] > App::get("APP_UPLOAD_SIZE"))
            throw new Exception(sprintf(_("Your file exceeds the maximum allowed file size of %s KB."), (App::get("APP_UPLOAD_SIZE")/1000)));

        $filename = $prefix."_".bin2hex(random_bytes(9));
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        move_uploaded_file($file['tmp_name'], App::get("DIR_ROOT").App::get("DIR_UPLOADS")."/".$filename.".".$ext);
        $this->filename = $filename.".".$ext;
    }

    /**
     * Get the URL of the uploaded file.
     *
     * @return  string  The URL of the uploaded file.
     */
    public function get_file_url() {
        return App::get("APP_URL").App::get("DIR_UPLOADS")."/".$this->filename;
    }

}

?>
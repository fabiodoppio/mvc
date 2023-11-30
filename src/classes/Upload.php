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

/**
 * 
 *  Upload Class
 *
 *  The Upload class provides methods for handling file uploads, including validation and storing files.
 * 
 */
class Upload {

    /**
     *  @var    string  $filename   The filename of the uploaded file.
     */
    private $filename;


    /**
     * 
     *  Construct an Upload object for handling file uploads.
     *
     *  @since  2.0
     *  @param  array   $file       The file information from the $_FILES array.
     *  @param  string  $prefix     (Optional) Prefix to prepend to the generated filename.
     * 
     */
    public function __construct(array $file, string $prefix = "") {
        $filename = $prefix."_".bin2hex(random_bytes(9));
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!move_uploaded_file($file['tmp_name'], App::get("DIR_ROOT").App::get("DIR_UPLOADS")."/".$filename.".".$ext))
            throw new Exception(_("File could not be uploaded."), 1036);
        $this->filename = $filename.".".$ext;
    }

    /**
     * 
     *  Get the name of the uploaded file.
     *
     *  @since  2.0
     *  @return string  The name of the uploaded file.
     * 
     */
    public function get_file_name() {
        return $this->filename;
    }

    /**
     * 
     *  Get the URL of the uploaded file.
     *
     *  @since  2.0
     *  @return string  The URL of the uploaded file.
     * 
     */
    public function get_file_url() {
        return App::get("APP_URL").App::get("DIR_UPLOADS")."/".$this->filename;
    }

    /**
     * 
     *  Delete a file of a given filename.
     *
     *  @since  2.0
     *  @param  string  $filename   The name of the uploaded file.
     * 
     */
    public static function delete(string $filename) {
        if (!unlink(App::get("DIR_ROOT").App::get("DIR_UPLOADS")."/".$filename))
            throw new Exception(_("File could not be deleted."), 1037);
    }

}

?>
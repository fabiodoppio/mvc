<?php

namespace Classes;

class Upload {

    private $filename;

    public function __construct(array $file, string $prefix = "") {
        if ($file['error'] !== 0)
            throw new Exception("Beim Upload ist ein Problem aufgetreten.");
        
        if (!in_array(mime_content_type($file['tmp_name']), App::get("APP_UPLOAD_TYPES")))
            throw new Exception("Du hast einen nicht erlaubten Dateitypen ausgewählt.");

        if ($file["size"] > App::get("APP_UPLOAD_SIZE"))
            throw new Exception("Deine Datei überschreitet die maximal erlaubte Dateigröße von ".(App::get("APP_UPLOAD_SIZE")/1000)." KB.");

        $filename = $prefix."_".bin2hex(random_bytes(9));
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        move_uploaded_file($file['tmp_name'], App::get("DIR_ROOT").App::get("DIR_UPLOADS")."/".$filename.".".$ext);
        $this->filename = $filename.".".$ext;
    }

    public function get_file_url() {
        return App::get("APP_URL").App::get("DIR_UPLOADS")."/".$this->filename;
    }

}

?>



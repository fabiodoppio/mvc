<?php

namespace Classes;

class Template {

    public static function get(string $file, ?array $vars = []) {
        ob_start();
        extract($vars, EXTR_OVERWRITE); 

        include self::cache_file($file);

        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    private static function cache_file(string $file) { 
        $cache = App::get("DIR_ROOT").App::get("DIR_CACHE").'/_'.hash_hmac('sha256', $file, hash_hmac('md5', $file, App::get("SALT_CACHE"))).'.php';

        if (App::get("APP_DEBUG") || !file_exists($cache) || filemtime($cache) < filemtime(App::get("DIR_ROOT").App::get("DIR_VIEWS").'/'.$file)) {
            $template = self::include_files($file);
            $template = self::compile_comments($template);
            $template = self::compile_echos($template);
            $template = self::compile_php($template);
            file_put_contents($cache, $template);
        }

        return $cache;
    }

    public static function clear_cache() {
        foreach(glob(App::get("DIR_ROOT").App::get("DIR_CACHE").'/*') as $file)
			unlink($file);
    }

    private static function include_files(string $file) {
        if (!file_exists(App::get("DIR_ROOT").App::get("DIR_VIEWS").'/'.$file))
            throw new Exception("Template '".$file."' not found.", 404);

        $template = file_get_contents(App::get("DIR_ROOT").App::get("DIR_VIEWS").'/'.$file);
		preg_match_all('/{% ?(include) ?\'?(.*?)\'? ?%}/i', $template, $matches, PREG_SET_ORDER);
		foreach ($matches as $value) 
			$template = str_replace($value[0], self::include_files($value[2]), $template);

        return $template;
    }

    private static function compile_comments(string $template) {
        return preg_replace('~\{\*\s*(.+?)\s*\\*}~is', '', $template);
    }

    private static function compile_echos(string $template) {
		return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?=htmlentities($1??\'\', ENT_QUOTES, \'UTF-8\')?>', $template);
    }

    private static function compile_php(string $template) {
		return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $template);
    }

}

?>
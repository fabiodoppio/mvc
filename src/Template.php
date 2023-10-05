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


namespace Classes;

/**
 * Template Class
 *
 * The Template class provides methods for working with templates, including rendering templates, caching, and clearing cache.
 */
class Template {

    /**
     * Render a template file with optional variables.
     *
     * @param   string      $file   The name of the template file to render.
     * @param   array|null  $vars   (Optional) Associative array of variables to pass to the template.
     * @return  string              The rendered template as a string.
     */
    public static function get(string $file, ?array $vars = []) {
        ob_start();
        extract($vars, EXTR_OVERWRITE); 

        include self::cache_file($file);

        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    /**
     * Cache a template file or retrieve it from the cache.
     *
     * @param   string  $file   The name of the template file to cache.
     * @return  string          The path to the cached template file.
     */
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

    /**
     * Clear the template cache by deleting cached files.
     */
    public static function clear_cache() {
        foreach(glob(App::get("DIR_ROOT").App::get("DIR_CACHE").'/*') as $file)
			unlink($file);
    }

    /**
     * Include and process template files recursively.
     *
     * @param   string  $file   The name of the template file to process.
     * @return  string          The processed template content.
     * @throws                  Exception If the template file is not found.
     */
    private static function include_files(string $file) {
        if (!file_exists(App::get("DIR_ROOT").App::get("DIR_VIEWS").'/'.$file))
            throw new Exception(sprintf(_("Template %s not found."), $file));

        $template = file_get_contents(App::get("DIR_ROOT").App::get("DIR_VIEWS").'/'.$file);
		preg_match_all('/{% ?(include) ?\'?(.*?)\'? ?%}/i', $template, $matches, PREG_SET_ORDER);
		foreach ($matches as $value) 
			$template = str_replace($value[0], self::include_files($value[2]), $template);

        return $template;
    }

    /**
     * Remove comments from the template.
     *
     * @param   string  $template   The template content to process.
     * @return  string              The template content with comments removed.
     */
    private static function compile_comments(string $template) {
        return preg_replace('~\{\*\s*(.+?)\s*\\*}~is', '', $template);
    }

     /**
     * Compile template echo statements.
     *
     * @param   string  $template   The template content to process.
     * @return  string              The template content with echo statements compiled.
     */
    private static function compile_echos(string $template) {
		return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?=htmlentities($1??\'\', ENT_QUOTES, \'UTF-8\')?>', $template);
    }

    /**
     * Compile template PHP code blocks.
     *
     * @param   string  $template   The template content to process.
     * @return  string              The template content with PHP code blocks compiled.
     */
    private static function compile_php(string $template) {
		return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $template);
    }

}

?>
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
 *  Cache Class
 *
 *  The Cache class provides methods for working with the template cache, including rendering or caching templates.
 * 
 */
class Cache {

    /**
     * 
     *  Get a template file with optional variables from the cache.
     *
     *  @since  2.0
     *  @param  string      $file   The name of the template file to compile.
     *  @param  array|null  $vars   (Optional) Associative array of variables to pass to the template.
     *  @return string              The compiled template as a string.
     * 
     */
    public static function get(string $file, ?array $vars = []) {
        ob_start();
        extract($vars, EXTR_OVERWRITE); 

        include self::compile($file);

        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    /**
     * 
     *  Cache a template file or retrieve it from the cache.
     *
     *  @since  2.0
     *  @param  string  $file   The name of the template file to cache.
     *  @return string          The path to the cached template file.
     * 
     */
    private static function compile(string $file) { 
        $cache = App::get("DIR_ROOT").App::get("DIR_CACHE").'/_'.hash_hmac('sha256', $file, hash_hmac('md5', $file, App::get("SALT_CACHE"))).'.php';

        if (App::get("APP_DEBUG") || !file_exists($cache)) {
            $template = self::compile_includes($file);
            $template = self::compile_comments($template);
            $template = self::compile_echos($template);
            $template = self::compile_php($template);
            file_put_contents($cache, $template);
        }

        return $cache;
    }

    /**
     * 
     *  Include and process template files recursively.
     *
     *  @since  2.3             Keep spaces and line breaks if debug mode is active.
     *  @since  2.0
     *  @param  string  $file   The name of the template file to process.
     *  @param  string  $dir    The directory which contains the template file.
     *  @return string          The processed template content.
     * 
     */
    private static function compile_includes(string $file) {
        if (!is_file($path = App::get("DIR_ROOT").App::get("DIR_VIEWS").$file))
            if (!is_file($path = App::get("DIR_ROOT").App::get("DIR_VENDOR")."/".App::get("SRC_PACKAGE")."/src/views".$file))
                throw new Exception(sprintf(_("Template %s not found."), $file), 1005);
            
        $template = file_get_contents($path);

		preg_match_all('/{% ?(include) ?\'?(.*?)\'? ?%}/i', $template, $matches, PREG_SET_ORDER);
		foreach ($matches as $value) 
			$template = str_replace($value[0], self::compile_includes($value[2]), $template);

        return App::get("APP_DEBUG") ? $template : preg_replace(['/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s'], ['>','<','\\1'], $template);
    }

    /**
     * 
     *  Remove comments from the template.
     *
     *  @since  2.0
     *  @param  string  $template   The template content to process.
     *  @return string              The template content with comments removed.
     * 
     */
    private static function compile_comments(string $template) {
        return preg_replace('~\{\*\s*(.+?)\s*\\*}~is', '', $template);
    }

    /**
     * 
     *  Compile template echo statements.
     *
     *  @since  2.0
     *  @param  string  $template   The template content to process.
     *  @return string              The template content with echo statements compiled.
     * 
     */
    private static function compile_echos(string $template) {
		return preg_replace(
            [
                '~\{{\s*([\'\"])((?:(?!\1}}).)+?)\1\s*,\s*(.+?)\s*}}~is', 
                '~\{{\s*([\'\"])((?:(?!\1}}).)+?)\1\s*}}~is',
                '~\{{\s*(.+?)\s*}}~is'
            ], 
            [
                '<?=sprintf(_($1$2$1), htmlentities($3??$1$1, ENT_QUOTES, \'UTF-8\'));?>',
                '<?=_($1$2$1);?>', 
                '<?=htmlentities($1??\'\', ENT_QUOTES, \'UTF-8\');?>'
            ], 
            $template
        );
    }

    /**
     * 
     *  Compile template PHP code blocks.
     *
     *  @since  2.0
     *  @param  string  $template   The template content to process.
     *  @return string              The template content with PHP code blocks compiled.
     * 
     */
    private static function compile_php(string $template) {
		return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $template);
    }

    /**
     * 
     *  Clear the template cache by deleting cached files.
     * 
     *  @since 2.0
     * 
     */
    public static function clear() {
        foreach(glob(App::get("DIR_ROOT").App::get("DIR_CACHE").'/*') as $file)
			unlink($file);
    }

}

?>
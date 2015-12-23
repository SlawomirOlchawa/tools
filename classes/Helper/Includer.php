<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Helper_Includer
 */
class Helper_Includer
{
    /**
     * @var string
     */
    public static $buildVersion = '1.0';

    /**
     * @var array
     */
    protected static $_css = array();

    /**
     * @var array
     */
    protected static $_cssNoMerge = array();

    /**
     * @var array
     */
    protected static $_js = array();

    /**
     * Add CSS link
     *
     * @param string $src
     * @param bool $noMerge
     */
    public static function addCSS($src, $noMerge = false)
    {
        if (in_array($src, static::$_css)) return;

        static::$_css[] = $src;

        if ($noMerge)
        {
            static::$_cssNoMerge[] = $src;
        }
    }

    /**
     * Add JS script
     *
     * @param string $src
     * @param string $group
     */
    public static function addJS($src, $group='default')
    {
        if (!isset(static::$_js[$group]))
        {
            static::$_js[$group] = array();
        }

        if (in_array($src, static::$_js[$group])) return;

        static::$_js[$group][] = $src;
    }

    /**
     * Render CSS links
     *
     * @return string
     */
    public static function renderCSS()
    {
        return static::_renderCSS(static::$_css);
    }

    /**
     * Render JS scripts links
     *
     * @param string $group
     * @return string
     */
    public static function renderJS($group='default')
    {
        $output = PHP_EOL;

        if (!empty(static::$_js[$group]))
        {
            foreach (static::$_js[$group] as $src)
            {
                $output .= HTML::script($src).PHP_EOL;
            }
        }

        return $output;
    }

    /**
     * Merge all CSS files and return link to one big file
     * If there are files which should not be merged, they are linked at the end
     *
     * @return string
     */
    public static function mergeCSS()
    {
        if ((class_exists('Kohana') AND Kohana::$environment !== Kohana::PRODUCTION) OR
            (!file_exists('cache'.DIRECTORY_SEPARATOR.'css')))
        {
            return static::renderCSS();
        }

        $content = '';
        $files = '';

        sort(static::$_css);

        foreach (static::$_css as $src)
        {
            if (in_array($src, static::$_cssNoMerge)) continue;

            $files .= $src.PHP_EOL;
        }

        $fileName = 'style'.md5($files).'.css';
        $path = 'cache'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$fileName;

        if (!file_exists($path))
        {
            foreach (static::$_css as $src)
            {
                if (in_array($src, static::$_cssNoMerge)) continue;

                try
                {
                    $content .= file_get_contents($src).PHP_EOL.PHP_EOL.'/* --- */'.PHP_EOL.PHP_EOL;
                }
                catch (Exception $e) {}
            }

            file_put_contents($path, $content);
        }

        //return PHP_EOL.HTML::style(URL::site('cache/css/'.$fileName)).static::_renderCSS(static::$_cssNoMerge);
        //return PHP_EOL.HTML::style(URL::site('cache/css/'.$fileName.'?'.date('Y-m-d'))).static::_renderCSS(static::$_cssNoMerge);
        return PHP_EOL.HTML::style(URL::site('cache/css/'.$fileName.'?'.static::$buildVersion)).static::_renderCSS(static::$_cssNoMerge);
    }

    /**
     * @param string[] $files
     * @return string
     */
    protected static function _renderCSS($files)
    {
        $output = PHP_EOL;

        foreach ($files as $src)
        {
            //$output .= HTML::style($src).PHP_EOL;
            //$output .= HTML::style($src.'?'.date('Y-m-d')).PHP_EOL;
            $output .= HTML::style($src.'?'.static::$buildVersion).PHP_EOL;
        }

        return $output;
    }
}

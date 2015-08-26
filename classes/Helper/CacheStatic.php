<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Helper_CacheStatic
 */
class Helper_CacheStatic
{
    /**
     * @var string
     */
    protected static $_cachePath = 'cache/html';

    /**
     * @var int
     */
    protected static $_lifetime = 600;

    /**
     * @var int
     */
    protected static $_deleteInterval = 300;

    /**
     * Disable cache by setting cookie
     */
    public static function disableCache()
    {
        if (!static::_production()) return;

        Cookie::set('nocache', '1', 0);
    }

    /**
     * Enable cache (delete cookie)
     */
    public static function enableCache()
    {
        if (!static::_production()) return;

        Cookie::delete('nocache');
    }

    /**
     * Check if cache is enabled
     *
     * @return bool
     */
    public static function enabled()
    {
        $result = true;

        if (Cookie::get('nocache'))
        {
            $result = false;
        }

        return $result;
    }

    /**
     * Write cache of full page to static html file
     *
     * @param string $content
     */
    public static function write($content)
    {
        if (!static::_production()) return;

        $uri = str_replace(array('?','.'), '', $_SERVER['REQUEST_URI']);

        // long uris become long file paths and maybe can make problems
        if (strlen($uri)>200) return;

        $dirs = explode('/', $uri);
        $path = static::$_cachePath;

        if (!is_dir($path))
        {
            mkdir($path, 0777);
            chmod($path, 0777);
        }

        $filename = array_pop($dirs);

        foreach ($dirs as $dir)
        {
            $path .= '/'.$dir;

            if (!is_dir($path))
            {
                mkdir($path, 0777);
                chmod($path, 0777);
            }
        }

        $cacheFile = $path.'/'.$filename.'_cache.html';

        if (!file_exists($cacheFile))
        {
            file_put_contents($cacheFile, '');
            chmod($cacheFile, 0666);
        }

        file_put_contents($cacheFile, $content);
    }

    /**
     * Delete old cache
     */
    public static function delete()
    {
        if (!static::_production()) return;

        $lastCacheClear = 0;

        if (file_exists(static::$_cachePath.'/timer'))
        {
            $lastCacheClear = filemtime(static::$_cachePath.'/timer');
        }

        if (time() - $lastCacheClear > static::$_deleteInterval)
        {
            static::_clearDirectory(static::$_cachePath);
            fopen(static::$_cachePath.'/timer', 'w');
        }
    }

    /**
     * Check if production environment
     *
     * @return bool
     */
    protected static function _production()
    {
        return (Kohana::$environment === Kohana::PRODUCTION);
    }

    /**
     * Delete old cache files and empty subdirectories
     *
     * @param string $dir
     */
    protected static function _clearDirectory($dir)
    {
        $handle = opendir($dir);

        while ($contents = readdir($handle))
        {
            if (($contents != '.') AND ($contents != '..'))
            {
                $path = $dir.'/'.$contents;

                if (is_dir($path))
                {
                    static::_clearDirectory($path);

                    if (static::_isEmpty($path))
                    {
                        rmdir($path);
                    }
                }
                else
                {
                    static::_deleteFile($path);
                }
            }
        }

        closedir($handle);
    }

    /**
     * Delete file if expired
     *
     * @param string $file
     */
    protected static function _deleteFile($file)
    {
        if (!file_exists($file) OR strstr($file, '.keep')) return;

        $time1 = filemtime($file);
        $time2 = strtotime('-'.static::$_lifetime.' second');

        if ($time2 > $time1)
        {
            @unlink($file);
        }
    }

    /**
     * Check if directory contains any files
     *
     * @param string $dir
     * @return bool
     */
    protected static function _isEmpty($dir)
    {
        return (count(glob("$dir/*")) === 0);
    }
}

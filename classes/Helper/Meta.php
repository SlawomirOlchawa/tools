<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Helper_Meta
 */
class Helper_Meta
{
    /**
     * @var array
     */
    protected static $_title = array();

    /**
     * @var array
     */
    protected static $_meta = array();

    /**
     * @var array
     */
    protected static $_freeTags = array();

    /**
     * @param string $title
     */
    public static function setTitle($title)
    {
        static::$_title = $title;
    }

    /**
     * @param string $title
     */
    public static function addTitle($title)
    {
        if (!is_array(static::$_title))
        {
            static::$_title = array(static::$_title);
        }

        static::$_title[] = $title;
    }

    /**
     * @return string
     */
    public static function renderTitle()
    {
        $title = static::$_title;

        if (is_array(static::$_title))
        {
            $title = implode(' - ', $title);
        }

        if (!empty($title))
        {
            $title = '<title>'.$title.'</title>';
        }

        return $title;
    }

    /**
     * @param string $content
     * @param null|string $name
     * @param null|string $httpEquiv
     */
    public static function addMeta($content, $name = null, $httpEquiv = null)
    {
        static::$_meta[] = array('content' => $content, 'name' => $name, 'http-equiv' => $httpEquiv);
    }

    /**
     * @param string $content
     */
    public static function addFreeTag($content)
    {
        static::$_freeTags[] = $content;
    }

    /**
     * @return string
     */
    public static function renderMeta()
    {
        $result = PHP_EOL;

        if (isset(static::$_meta) AND is_array(static::$_meta))
        {
            foreach (static::$_meta as $meta)
            {
                $result .= '<meta';

                foreach ($meta as $key => $value)
                {
                    if (empty($value)) continue;

                    $result .= ' '.$key.'="'.$value.'"';
                }

                $result .= '>'.PHP_EOL;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public static function renderFreeTags()
    {
        $result = PHP_EOL;

        if (isset(static::$_freeTags) AND is_array(static::$_freeTags))
        {
            $result = implode(PHP_EOL, static::$_freeTags) . PHP_EOL;
        }

        return $result;
    }
}

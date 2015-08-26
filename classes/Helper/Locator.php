<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Helper_Locator
 */
class Helper_Locator
{
    /**
     * @var array
     */
    protected static $_locations = array();

    /**
     * @param string $name
     * @param string|null $url
     */
    public static function add($name, $url = null)
    {
        if (!empty($url))
        {
            $url = URL::site($url);
        }

        static::$_locations[] = array('name' => $name , 'url' => $url);
    }

    /**
     * @return string
     */
    public static function render()
    {
        $result = array();
        $counter = 0;

        foreach (static::$_locations as $location)
        {
            $counter++;

            if (empty($location['url']) OR ($counter == count(static::$_locations)))
            {
                $result[] = $location['name'];
            }
            else
            {
                $result[] = HTML::anchor($location['url'], $location['name']);
            }
        }

        return implode(' &raquo; ', $result);
    }
}

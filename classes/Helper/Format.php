<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Helper_Format
 */
class Helper_Format
{
    /**
     * @var array
     */
    protected static $_months = Array('stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca',
        'sierpnia', 'września', 'października', 'listopada', 'grudnia');

    /**
     * @param string $time
     * @return string
     */
    public static function friendlyTime($time)
    {
        $t = strtotime($time);

        return static::formatDate($t).', '.date('G:i', $t);
    }

    /**
     * @param string $date
     * @return string
     */
    public static function friendlyDate($date)
    {
        return static::formatDate(strtotime($date));
    }

    /**
     * @param int $time
     * @return string
     */
    protected static function formatDate($time)
    {
        $date = date('j ',$time).static::$_months[date('n',$time)-1].date(' Y',$time);

        if (date('Ymd',$time) === date('Ymd'))
        {
            $date = 'Dzisiaj';
        }

        if (date('Ymd',$time) === date('Ymd', strtotime('now - 1 days')))
        {
            $date = 'Wczoraj';
        }

        return $date;
    }

    /**
     * @param string $text
     * @return string
     */
    public static function removeNewLineChars($text)
    {
        return str_replace(array("\n", "\n\r", "\r\n", "\r"), ' ', $text);
    }
}

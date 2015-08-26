<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Helper_Inflector
 */
class Helper_Inflector
{
    /**
     * @var array
     */
    protected static $_plural = array
    (
        'osoba' => array('osoby', 'osób'),
        'kort' => array('korty', 'kortów'),
    );

    /**
     * @var array
     */
    protected static $_genitive = array
    (
        'tenis ziemny' => 'tenisa ziemnego',
        'tenis stołowy' => 'tenisa stołowego',
        'badminton' => 'badmintona',
        'squash' => 'squasha',
        'wielobój' => 'sporty wielu dyscyplin',
        'siatkówka' => 'siatkówkę',
        'koszykówka' => 'koszykówkę',
        'piłka nożna' => 'piłkę nożną',
        'piłka ręczna' => 'piłkę ręczną',
        'kręgle' => 'kręgle',
        'szachy' => 'szachy',
        'bilard' => 'bilard',
    );

    /**
     * @var array
     */
    protected static $_locative = array
    (
        'styczeń' => 'w styczniu',
        'luty' => 'w lutym',
        'marzec' => 'w marcu',
        'kwiecień' => 'w kwietniu',
        'maj' => 'w maju',
        'czerwiec' => 'w czerwcu',
        'lipiec' => 'w lipcu',
        'sierpień' => 'w sierpniu',
        'wrzesień' => 'we wrześniu',
        'październik' => 'w październiku',
        'listopad' => 'w listopadzie',
        'grudzień' => 'w grudniu',
    );

    /**
     * @param string $word
     * @param string $count
     * @return string
     */
    public static function plural($word, $count)
    {
        if ($count == 1) return $word;

        $digit = $count % 10;
        $digit2 = ($count % 100 - $digit) / 10;

        $result = static::$_plural[$word][1];

        if (($digit2 != 1) AND ($digit >= 2) AND ($digit <= 4))
        {
            $result = static::$_plural[$word][0];
        }

        return $result;
    }

    /**
     * @param string $word
     * @return string
     */
    public static function genitive($word)
    {
        $result = $word;

        if (isset(static::$_genitive[strtolower($word)]))
        {
            $result = static::$_genitive[strtolower($word)];
        }

        return $result;
    }

    /**
     * @param string $word
     * @return string
     */
    public static function locative($word)
    {
        $result = $word;

        if (isset(static::$_locative[strtolower($word)]))
        {
            $result = static::$_locative[strtolower($word)];
        }

        return $result;
    }
}

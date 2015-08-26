<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Helper_Token
 */
class Helper_Token
{
    /**
     * @var string|null
     */
    protected static $_token = null;

    /**
     * @return string
     */
    public static function get()
    {
        if (!empty(static::$_token))
        {
            return static::$_token;
        }

        return static::generate();
    }

    /**
     * @return string
     */
    public static function generate()
    {
        $token = md5(openssl_random_pseudo_bytes(20));
        static::$_token = $token;
        Session::instance()->set('token', $token);

        return $token;
    }

    /**
     * @param string $token
     * @return bool
     */
    public static function valid($token)
    {
        $result = false;

        if ($token === Session::instance()->get('token'))
        {
            $result = true;
            static::$_token = null;
            Session::instance()->delete('token');
        }

        return $result;
    }
}

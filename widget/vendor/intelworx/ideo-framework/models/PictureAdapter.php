<?php

class TwitpicAdapter implements TwitterPictureAdapter
{

    public static function match($url)
    {
        return preg_match('/twitpic\.com/', $url);
    }

    public function src($url, $size = 'large')
    {
        $parsed = parse_url($url);
        $id = $parsed['path'];
        return "http://twitpic.com/show/{$size}/{$id}.jpg";
    }

}

class YfrogAdapter implements TwitterPictureAdapter
{

    public static function match($url)
    {
        return preg_match('/yfrog\.com/', $url);
    }

    public function src($url, $size = 'medium')
    {
        $parsed = parse_url($url);
        $id = $parsed['path'];
        return "http://yfrog.com{$id}:{$size}";
    }

}

class PlixiAdapter implements TwitterPictureAdapter
{

    public static function match($url)
    {
        return preg_match('/(plixi\.com)|(lockerz\.com)/', $url);
    }

    public function src($url, $size = 'medium')
    {
        $url = urlencode($url);
        return "http://api.plixi.com/api/tpapi.svc/imagefromurl?size={$size}&url={$url}";
    }


}


class UnknownPictureAdapter implements TwitterPictureAdapter
{
    public static function match($url)
    {
        return true;;
    }

    public function src($url, $size = 'medium')
    {
        return $url;
    }

}

interface TwitterPictureAdapter
{

    public static function match($url);

    public function src($url, $size = 'medium');
}

class PictureAdapterFactory
{
    protected static $adapters = array('TwitpicAdapter', 'YfrogAdapter', 'PlixiAdapter');

    /**
     *
     * @param string $url
     * @return TwitterPictureAdapter
     */
    public static function getPictureAdapter($url)
    {
        foreach (self::$adapters as $adapter) {
            if (call_user_func(array($adapter, 'match'), $url)) {
                return new $adapter();
            }
        }
        error_log("Unknown picture Adapted for: {$url}", E_WARNING);
        return new UnknownPictureAdapter();
    }
}
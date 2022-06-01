<?php

class YoutubeAdapter implements VideoAdapter
{

    public static function match($url)
    {
        return preg_match('/(youtube\.com)|(youtu\.be)/', $url);
    }

    public function embedUrl($url)
    {
        $parsed = parse_url($url);
        if (preg_match('/embed/', $url)) {
            return $url;
        } elseif (preg_match('/youtube\.com/', $url)) {
            parse_str($parsed['query'], $params);
            $id = $params['v'];
        } else {
            $id = trim($parsed['path'], '/');
        }
        return "http://www.youtube.com/embed/{$id}";
    }

}

class TwitvidAdapter implements VideoAdapter
{

    public static function match($url)
    {
        return preg_match('/twitvid\.com/', $url);
    }

    public function embedUrl($url)
    {
        $parsed = parse_url($url);
        $id = trim($parsed['path'], '/');
        return "http://www.twitvid.com/embed.php?guid={$id}&autoplay=1&width=480&height=360";
    }

}

class VimeoAdapter implements VideoAdapter
{

    public static function match($url)
    {
        return preg_match('/vimeo\.com/', $url);
    }

    public function embedUrl($url)
    {
        $parsed = parse_url($url);
        $id = trim($parsed['path'], '/');
        return "http://player.vimeo.com/video/{$id}";
    }

}

class UnknownVideoAdapter implements VideoAdapter
{

    public static function match($url)
    {
        return true;
    }

    public function embedUrl($url)
    {
        return $url . "#";
    }

}

interface VideoAdapter
{

    public static function match($url);

    public function embedUrl($url);
}

class VideoAdapterFactory
{

    protected static $adapters = array(
        'YoutubeAdapter',
        'TwitvidAdapter',
        'VimeoAdapter',
    );

    /**
     *
     * @param string $url
     * @return VideoAdapter
     */
    public static function getVideoAdapter($url)
    {
        foreach (self::$adapters as $adapter) {
            if (call_user_func(array($adapter, 'match'), $url)) {
                return new $adapter();
            }
        }

        error_log("Unknown video Adapted for: {$url}");
        return new UnknownVideoAdapter();
    }

    public static function getEmbedUrl($url)
    {
        return self::getVideoAdapter($url)->embedUrl($url);
    }

    public static function addAdapter($adapter)
    {
        self::$adapters[] = $adapter;
    }

}


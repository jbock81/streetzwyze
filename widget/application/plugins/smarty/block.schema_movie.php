<?php
include_once 'schema_common.php';
/**
 * 
 * @param type $params
 * @param type $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return type
 */
function smarty_block_schema_movie($params, $content, $template, &$repeat) {
    $fullMode = (bool) $params['full_mode'];
    /*@var $movie \movies\models\entity\Movie*/
    $movie = $params['movie'];
    $strip = is_null($params['strip']) || (bool) $params['strip'];
    if (!$movie || !($movie instanceof \movies\models\entity\Movie)) {
        throw new SmartyException("Movie was not specified", null, null);
    }

    if (!$repeat) {
        $return = "<div itemscope itemtype='http://schema.org/Movie'>"
                . $content;
        $genres = join(', ', $movie->getGenre()->getMetadataValue());
        $return .= <<<SCH
        <meta itemprop="name" content="{$movie->title}"/>
        <meta itemprop="description" content="{$movie->description}"/>
        <meta itemprop="genre" content="{$genres}"/>
        <meta itemprop="contentRating" content="{$movie->getRated()}"/>
        <meta itemprop="duration" content="P{$movie->runtime['hours']}H{$movie->runtime['minutes']}M"/>
        <meta itemprop="image" content="{$movie->poster}"/>
        <meta itemprop="url" content="{$movie->buildUrl()}"/>
        <div itemprop="trailer" itemtype="http://schema.org/VideoObject" itemscope>
        <meta itemprop="url" content="{$movie->getTrailerUrl()}">
        </div>
SCH;
        if ($fullMode) {
            foreach ($movie->getDirector()->getMetadataValue() as $director) {
                $return .= '<div itemprop = "director" itemscope itemtype = "http://schema.org/Person">';
                $return .= "<meta itemprop = \"name\" content = \"{$director}\"/>\n";
                $return.='</div>';
            }

            foreach ($movie->getActor()->getMetadataValue() as $actor) {
                $return .= '<div itemprop = "actor" itemscope itemtype = "http://schema.org/Person">' . PHP_EOL;
                $return .= "<meta itemprop = \"name\" content = \"{$actor}\"/>\n";
                $return.='</div>';
            }
        }

        $return .= "</div>";
        if ($strip) {
            $return = strip_ws($return);
        }

        if ($params['assign']) {
            $template->assign($params['assign'], $return);
        } else {
            return $return;
        }
    }
}

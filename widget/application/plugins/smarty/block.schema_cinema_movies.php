<?php

include_once 'schema_common.php';

/**
 * 
 * This block function wraps cinema movies
 * @param type $params
 * @param type $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return type
 */
function smarty_block_schema_cinema_movies($params, $content, $template, &$repeat) {
    /* @var $cinema \movies\models\entity\Cinema */
    $cinema = $params['cinema'];
    /* @var $firstShowtime movies\models\entity\Showtime */
    $firstShowtime = $params['showtime'];
    $strip = is_null($params['strip']) || (bool) $params['strip'];
    $itemProp = $params['itemprop'];

    if (!$cinema || !$firstShowtime) {
        throw new SmartyException("One of Cinema or Showtime was not specified", null, null);
    }
    /* @var $movie \movies\models\entity\Movie */
    $movie = $firstShowtime->getMovie();
    $itemPropAttr = $itemProp ? "itemprop='{$itemProp}'" : "";
    $appConfig = AppConfig::getInstance();
    $cinemaTicketUrl = $cinema->getTicketUrl($movie, $firstShowtime->type);
    if (!$repeat) {
        $return = "<div itemscope itemtype='http://schema.org/Event' {$itemPropAttr}>";
        //Showtimes for `$movie->title` at `$cinema->centre_name`
        $startDate = date('c', strtotime($firstShowtime->event_date));
        $return .= <<<SCH
        <meta itemprop="name" content="Showtimes for {$movie->title} at {$cinema->centre_name}"/>
        <meta itemprop="description" content="Movie showtimes for {$movie->title} from {$cinema->centre_name} in {$cinema->state->location_name}, {$appConfig->siteCountry} on {$appConfig->siteName}"/>
        <meta itemprop="duration" content="P{$movie->runtime['hours']}H{$movie->runtime['minutes']}M"/>
        <meta itemprop="image" content="{$movie->poster}"/>
        <meta itemprop="url" content="{$cinemaTicketUrl}"/>
        <meta itemprop="startDate" content="{$startDate}"/>
SCH;
        $return.= "<div itemprop='location' itemscope itemtype='http://schema.org/Place'>";
        $return .="<meta itemprop='name' content='{$cinema->centre_name}'>\n";
        $return .="<div itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'>\n";
        $return.="<meta itemprop='telephone' content='{$cinema->contact_phone}'>";
        $return.="<meta itemprop='addressLocality' content='{$cinema->address_city}'>";
        $return.="<meta itemprop='streetAddress' content='{$cinema->address_line_1}'>";
        $return.="<meta itemprop='streetAddress' content='{$cinema->address_line_2}'>";
        $return.="<meta itemprop='addressRegion' content='{$cinema->state->location_name}'>";
        $return.="<meta itemprop='addressCountry' content='{$appConfig->siteCountry}'>";
        $return .="</div>\n";
        if ($cinema->place) {
            $return .="<div itemprop='geo' itemscope itemtype='http://schema.org/GeoCoordinates'>\n";
            $return.="<meta itemprop='latitude' content='{$cinema->place->lat}'>";
            $return.="<meta itemprop='longitude' content='{$cinema->place->long}'>";

            $return.= "</div>";
            $return.="<meta itemprop='map' content='{$cinema->place->getGMapAddress()}'>";
        }

        $return.= "</div>";

        $return .= $content . "</div>";
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

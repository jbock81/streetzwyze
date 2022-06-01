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
function smarty_block_schema_showtime($params, $content, $template, &$repeat) {
    /* @var $showtime movies\models\entity\Showtime */
    $showtime = $params['showtime'];
    $strip = is_null($params['strip']) || (bool) $params['strip'];
    $itemProp = $params['itemprop'];

    if (!$showtime) {
        throw new SmartyException("One of Cinema or Showtime was not specified", null, null);
    }
    /* @var $movie \movies\models\entity\Movie */
    $movie = $showtime->getMovie();
    $cinema = $showtime->getCinema();

    $itemPropAttr = $itemProp ? "itemprop='{$itemProp}'" : "";
    //$appConfig = AppConfig::getInstance();
    if (!$repeat) {
        $return = "<div itemscope itemtype='http://schema.org/Event' {$itemPropAttr}>";
        $return .= <<<SCH
        <meta itemprop="name" content="{$movie->title} ({$showtime->showtime_type}) at {$cinema->centre_name}, {$showtime->getFormattedDate('M d, g:ia')}"/>
        <meta itemprop="description" content="{$showtime->label}"/>
        <meta itemprop="startDate" content="{$showtime->getFormattedDate('c')}"/>
SCH;
        //offers

        $return .= "<div itemscope  itemtype='http://schema.org/AggregateOffer' itemprop='offers'>\n";
        $return.= "<meta itemprop='url' content='{$showtime->buildUrl()}'/>";
        $return.= "<meta itemprop='category' content='{$showtime->showtime_type}'/>";
        $return.= "<meta itemprop='offerCount' content='{$showtime->available_tickets}'/>";
        $return.= "<meta itemprop='highPrice' content='{$showtime->getHighestPrice()->getFormatted(true)}'/>";
        $return.= "<meta itemprop='lowPrice' content='{$showtime->getLowestPrice()->getFormatted(true)}'/>";
        if ($showtime->canBeBought()) {
            $return.= "<meta itemprop='availability' content='http://schema.org/LimitedAvailability'/>";
        } else {
            $return.='<meta itemprop="availability" content="http://schema.org/SoldOut">';
        }
        $return .= "</div>";


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

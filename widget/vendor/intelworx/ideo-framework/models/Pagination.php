<?php

class Pagination extends IdeoObject
{

    public $items_per_page;
    public $items_total;
    public $current_page;
    public $num_pages;
    public $mid_range = 7;
    public $low;
    public $high;
    public $limit;
    public $return;
    public $default_ipp = 25;
    public $querystring;
    public $urlPage;
    public $ignoreAll = true;
    public $pagination_max_pages = 8;
    protected $allowIppOverride = true;
    static protected $compatibilityMode = true;

    /**
     *
     * @var ClientHttpRequest
     */
    private $requestInstance;

    public function __construct($mainUrl = "", $itt = 0, $allowIppOverride = true)
    {

        if (AppConfig::isInitialized() && ($appConfig = AppConfig::getInstance())) {
            if ($appConfig->paginationDefaultIpp) {
                $this->default_ipp = $appConfig->paginationDefaultIpp;
            }

            if ($appConfig->paginationMidRange) {
                $this->mid_range = $appConfig->paginationMidRange;
            }

            if ($appConfig->paginationMaxPages) {
                $this->pagination_max_pages = AppConfig::getInstance()->paginationMaxPages;
            }
        }

        $this->requestInstance = ClientHttpRequest::getCurrent();

        $this->current_page = null;
        $this->allowIppOverride = $allowIppOverride;
        $this->items_per_page = $allowIppOverride ? $this->requestInstance->getRequest('ipp', true, $this->default_ipp) : $this->default_ipp;
        $this->urlPage = ($mainUrl) ? $mainUrl : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->items_total = $itt;
    }

    public static function setCompatibilityMode($bool = true)
    {
        self::$compatibilityMode = $bool;
    }

    protected function paginate()
    {
        $ippString = $this->allowIppOverride ? "&ipp={$this->items_per_page}" : "";
        $displayAll = false;
        if ($this->items_per_page == 'All' && !$this->ignoreAll) {
            $this->num_pages = 1; //ceil($this->items_total / $this->default_ipp);
            $this->items_per_page = $this->items_total; //$this->default_ipp;
            $displayAll = true;
        } else {
            if (!is_numeric($this->items_per_page) || $this->items_per_page <= 0) {
                $this->items_per_page = $this->default_ipp;
            }
            $this->num_pages = ceil($this->items_total / $this->items_per_page);
        }

        if ($this->current_page === null) {
            $this->current_page = (int)$this->requestInstance->getRequest('page', true, 1);
        } // must be numeric > 0
        if ($this->current_page < 1 or !is_numeric($this->current_page)) {
            $this->current_page = 1;
        }

        if ($this->current_page > $this->num_pages) {
            $this->current_page = $this->num_pages;
        }

        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;

        if (count($this->requestInstance->getQueryParam()) > 0) {
            $args = explode("&", $_SERVER['QUERY_STRING']);
            foreach ($args as $arg) {
                $keyval = explode("=", $arg);
                if ($keyval[0] != "page" and $keyval[0] != "ipp") {
                    $this->querystring .= "&" . $arg;
                }
            }
        }

        if ($this->requestInstance->isPost()) {
            foreach ($this->requestInstance->getPostData() as $key => $val) {
                if ($key != "page" and $key != "ipp") {
                    $this->querystring .= "&$key=$val";
                }
            }
        }

        $urlAppend = "{$ippString}{$this->querystring}";
        $this->return = ($this->current_page != 1 and $this->num_pages >= 2) ?
            "<li><a  href=\"{$this->urlPage}?page={$prev_page}{$urlAppend}\"> &laquo; Previous</a> </li>" :
            "<li class=\"disabled\"><span class='paginate_prev'>&laquo; Previous</span></li>";

        if ($this->num_pages > $this->pagination_max_pages) {

            $this->start_range = $this->current_page - floor($this->mid_range / 2);
            $this->end_range = $this->current_page + floor($this->mid_range / 2);

            if ($this->start_range <= 0) {
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }
            if ($this->end_range > $this->num_pages) {
                $this->start_range -= $this->end_range - $this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range, $this->end_range);

            for ($i = 1; $i <= $this->num_pages; $i++) {
                if ($this->range[0] > 2 && $i == $this->range[0]) {
                    $this->return .= "<li> <span class='disabled'> ... </span></li>";
                }
                // loop through all pages. if first, last, or in range, display
                if ($i == 1 or $i == $this->num_pages or in_array($i, $this->range)) {
                    $this->return .= ($i == $this->current_page && !$displayAll) ?
                        "<li class=\"active\"><a title=\"Go to page $i of $this->num_pages\" href=\"#\">{$i}</a></li>" :
                        "<li><a title=\"Go to page {$i} of {$this->num_pages}\" href=\"{$this->urlPage}?page={$i}{$urlAppend}\">{$i}</a> </li>";
                }
                if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 and $i == $this->
                    range[$this->mid_range - 1]
                ) {
                    $this->return .= "<li> <span class='disabled'> ... </span></li>";
                }
            }
        } else {
            for ($i = 1; $i <= $this->num_pages; $i++) {
                $this->return .= ($i == $this->current_page) ?
                    "<li class=\"active\"><span>{$i}</span></li> " :
                    "<li><a href=\"{$this->urlPage}?page={$i}{$urlAppend}\">{$i}</a></li>";
            }
        }

        if (!$this->ignoreAll) {
            $this->return .= ($displayAll) ?
                "<li class=\"active all\"><a href=\"#\">All</a> </li>\n" :
                "<li><a href=\"{$this->urlPage}?page=1&ipp=All{$this->querystring}\">All</a></li>\n";
        }

        $this->return .= (($this->current_page != $this->num_pages && $this->num_pages >= 2) && !$displayAll) ?
            "<li>
                    <a  href=\"{$this->urlPage}?page={$next_page}{$urlAppend}\">Next &raquo;</a>
                 </li>\n" :
            "<li class=\"disabled\"><span class='paginate_next'>Next&raquo; </span></li>\n";


        if (self::$compatibilityMode) {
            $this->return = "<ul>{$this->return}</ul>";
        }

        $this->low = ($this->current_page - 1) * $this->items_per_page;
        $this->high = $displayAll ? $this->items_total : ($this->
                current_page * $this->items_per_page) - 1;
        $this->limit = $displayAll ? "" : " LIMIT $this->low,$this->items_per_page";
    }

    public function display_items_per_page()
    {
        $items = '';
        $ipp_array = array(10, 25, 50, 75, 100, 150, 200, 500, 1000);
        if (!$this->ignoreAll) {
            $ipp_array[] = 'All';
        }
        foreach ($ipp_array as $ipp_opt) {
            $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n" :
                "<option value=\"$ipp_opt\">$ipp_opt</option>\n";
        }
        return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='{$this->urlPage}?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n";
    }

    public function display_jump_menu()
    {
        for ($i = 1; $i <= $this->num_pages; $i++) {
            $option .= ($i == $this->current_page) ? "<option value=\"$i\" selected>$i</option>\n" :
                "<option value=\"$i\">$i</option>\n";
        }
        return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='{$this->urlPage}?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select>\n";
    }

    public function display_pages()
    {
        return $this->return;
    }

    /**
     * Use ::paginateList() instead
     * @deprecated since version 1.0
     */
    public function doPagination()
    {
        $smarty = TemplateEngine::getInstance();
        $this->ignoreAll = false;
        $this->paginate();
        $this->low = $this->low < 0 ? 0 : $this->low;
        $high = ($this->high > ($this->items_total - 1)) ? ($this->items_total - 1) : $this->high;
        $pagesCount = $this->num_pages;
        $smarty->assign('startApp', $this->low + 1);
        $smarty->assign('low', $this->low);
        $smarty->assign('endApp', $high + 1);
        $smarty->assign('totalItems', $this->items_total);
        $smarty->assign('pages', ($pagesCount < 2) ? '' : $this->display_pages());
        $smarty->assign('pages_ipp', $this->display_items_per_page());
    }

    /**
     * Performs pagination and returns an array of pagination configuration
     * @return array
     */
    public function paginateList()
    {
        $this->paginate();
        $this->low = $this->low < 0 ? 0 : $this->low;
        $this->high = ($this->high > ($this->items_total - 1)) ? ($this->items_total - 1) : $this->high;
        $pagesCount = $this->num_pages;

        return array(
            'low' => $this->low,
            'high' => $this->high,
            'total' => $this->items_total,
            'per_page' => $this->items_per_page,
            'display' => ($pagesCount < 2) ? '' : $this->display_pages(),
            'ipp_menu' => $this->display_items_per_page(),
            'pages_count' => $pagesCount,
            'current_page' => $this->current_page,
            'jump_menu' => $this->display_jump_menu(),
        );
    }

    public function setIgnoreAll($bool = true)
    {
        $this->ignoreAll = $bool;
    }

}

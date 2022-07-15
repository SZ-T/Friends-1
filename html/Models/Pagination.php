<?php

class Pagination{

    protected $current, $pages, $next, $previous, $url;

    // Setup variables used in pagination
    public function __construct() {
        if (isset($_GET["page"])) {
            $this->current = (int)$_GET["page"];
        } else {
            $this->current = 1;
        }
        // Default next and previous page
        $this->previous = 1;
        $this->next = $this->current + 1;
    }

    public function set($count, $height) {
        // Set number of pages for pagination
        if ($height == "All") {
            $this->pages = $count;
        } else {
            $this->pages = ceil(((float) $count / (float) $height));
        }
        if ($this->current > 1) {
            $this->previous = $this->current - 1;
        }
        if ($this->current >= $this->pages) {
            $this->next = $this->pages;
        }
        // Set URL for pagination buttons
        $this->url = $_SERVER['PHP_SELF'].'?';
        foreach ($_GET as $key => $value) {
            if ($key === 'page') {
                continue;
            }
            $this->url = $this->url.$key.'='.$value.'&';
        }
        $this->url = $this->url.'page=';
    }

    // Get current page number
    public function getCurrent(): int
    {
        return $this->current;
    }

    // Get next page number
    public function getNext(): int
    {
        return $this->next;
    }

    // Get previous page number
    public function getPrevious(): int
    {
        return $this->previous;
    }

    // Get number of pages
    public function getPages()
    {
        return $this->pages;
    }

    // Get URL for pagination buttons
    public function getUrl()
    {
        return $this->url;
    }
}



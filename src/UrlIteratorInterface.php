<?php

namespace Icamys\SitemapGenerator;

interface UrlIteratorInterface {
    /**
     * Return current url object
     * @return \SplFixedArray|null
     */
    function current(): ?\SplFixedArray;

    /**
     * Move to next url
     */
    function next(): void;

    /**
     * Rewind iterator back to the start
     */
    function rewind(): void;
}
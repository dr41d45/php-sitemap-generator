<?php

namespace Icamys\SitemapGenerator;

interface UrlIteratorInterface {
    /**
     * Returns current url object
     * @return \SplFixedArray|null
     */
    function current(): ?\SplFixedArray;

    /**
     * Returns current url object index
     * @return int
     */
    function key(): int;

    /**
     * Move to next url
     */
    function next(): void;
}
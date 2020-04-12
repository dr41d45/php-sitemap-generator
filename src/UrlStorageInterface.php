<?php

namespace Icamys\SitemapGenerator;

/**
 * Interface UrlStorageInterface
 */
interface UrlStorageInterface
{
    const URL_KEY_LOC = 0;
    const URL_KEY_LASTMOD = 1;
    const URL_KEY_CHANGEFREQ = 2;
    const URL_KEY_PRIORITY = 3;
    const URL_KEY_ALTERNATES = 4;

    /**
     * Add array to storage
     * @param string $loc
     * @param \DateTime|null $lastModified
     * @param string|null $changeFrequency
     * @param float|null $priority
     * @param array|null $alternates
     * @return UrlStorageInterface
     */
    function add(
        string $loc = '',
        \DateTime $lastModified = null,
        string $changeFrequency = null,
        float $priority = null,
        array $alternates = null
    ): UrlStorageInterface;

    /**
     * Get array size
     * @return int
     */
    function count(): int;
}
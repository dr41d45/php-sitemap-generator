<?php

namespace Icamys\SitemapGenerator;

use SplFixedArray;

class MemoryUrlStorage implements UrlStorageInterface, UrlIteratorInterface
{
    /**
     * @var SplFixedArray Dynamic array with urls
     */
    private $urls;
    /**
     * @var integer number of currently added urls
     */
    private $urlsCount = 0;
    /**
     * @var int
     */
    private $cursor = 0;

    public function __construct()
    {
        $this->urls = new SplFixedArray();
    }

    /**
     * @inheritDoc
     */
    public function add(
        string $loc = '',
        \DateTime $lastModified = null,
        string $changeFrequency = null,
        float $priority = null,
        array $alternates = null
    ): UrlStorageInterface
    {
        $this->doubleSizeArrayIfNoSpaceLeft();
        $item = $this->prepareArrayItem($loc, $lastModified, $changeFrequency, $priority, $alternates);
        $this->urls[$this->urls->key()] = $item;
        $this->urls->next();
        $this->urlsCount++;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->urlsCount;
    }

    /**
     * @inheritDoc
     */
    public function current(): ?\SplFixedArray
    {
        if ($this->cursor >= $this->urlsCount) {
            return null;
        }
        return $this->urls[$this->cursor];
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->cursor = $this->cursor + 1;
    }

    public function key(): int
    {
        return $this->cursor;
    }

    private function doubleSizeArrayIfNoSpaceLeft()
    {
        if ($this->urlsCount === 0) {
            $this->urls->setSize(1);
        } else {
            if ($this->urlsCount === $this->urls->key()) {
                $this->urls->setSize($this->urlsCount * 2);
            }
        }
    }

    private function prepareArrayItem(
        string $loc = '',
        \DateTime $lastModified = null,
        string $changeFrequency = null,
        float $priority = null,
        array $alternates = null
    ): \SplFixedArray
    {
        $item = new \SplFixedArray(1);

        $item[self::ATTR_KEY_LOC] = $loc;

        if (isset($lastModified)) {
            $item->setSize(2);
            $item[self::ATTR_KEY_LASTMOD] = $lastModified->format(\DateTime::ATOM);
        }

        if (isset($changeFrequency)) {
            $item->setSize(3);
            $item[self::ATTR_KEY_CHANGEFREQ] = $changeFrequency;
        }

        if (isset($priority)) {
            $item->setSize(4);
            $item[self::ATTR_KEY_PRIORITY] = $priority;
        }

        if (isset($alternates)) {
            $item->setSize(5);
            $item[self::ATTR_KEY_ALTERNATES] = $alternates;
        }

        return $item;
    }
}
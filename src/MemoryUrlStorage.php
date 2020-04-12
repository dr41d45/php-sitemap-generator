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
    private $count = 0;

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
        $item = $this->createItem($loc, $lastModified, $changeFrequency, $priority, $alternates);
        $this->urls[$this->count] = $item;
        $this->count++;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    public function current(): ?\SplFixedArray
    {
        return $this->urls->current();
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->urls->next();
    }

    public function rewind(): void
    {
        $this->urls->rewind();
    }

    private function doubleSizeArrayIfNoSpaceLeft()
    {
        if ($this->count === 0) {
            $this->urls->setSize(1);
        } else {
            if ($this->count === $this->urls->getSize()) {
                $this->urls->setSize($this->count * 2);
            }
        }
    }

    private function createItem(
        string $loc = '',
        \DateTime $lastModified = null,
        string $changeFrequency = null,
        float $priority = null,
        array $alternates = null
    ): \SplFixedArray
    {
        $item = new \SplFixedArray(1);

        $item[self::URL_KEY_LOC] = $loc;

        if (isset($lastModified)) {
            $item->setSize(2);
            $item[self::URL_KEY_LASTMOD] = $lastModified->format(\DateTime::ATOM);
        }

        if (isset($changeFrequency)) {
            $item->setSize(3);
            $item[self::URL_KEY_CHANGEFREQ] = $changeFrequency;
        }

        if (isset($priority)) {
            $item->setSize(4);
            $item[self::URL_KEY_PRIORITY] = $priority;
        }

        if (isset($alternates)) {
            $item->setSize(5);
            $item[self::URL_KEY_ALTERNATES] = $alternates;
        }

        return $item;
    }
}
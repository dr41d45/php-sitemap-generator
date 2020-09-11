<?php

namespace Icamys\SitemapGenerator;

use DateTime;
use RuntimeException;
use SplFixedArray;

class FileSystemUrlStorage implements UrlStorageInterface, UrlIteratorInterface
{
    /**
     * @var integer number of currently added urls
     */
    private $urlsCount = 0;

    private $fs;

    private $filepath;

    private $readFh;
    private $writeFh;

    private $urlArr;

    private $current;
    private $isEofReached = false;

    const DEFAULT_STORAGE_PATH = ".url-storage";

    public function __construct($basePath = "", $filename = self::DEFAULT_STORAGE_PATH, $fs = null)
    {
        if (strlen($basePath) > 0 && substr($basePath, -1) != DIRECTORY_SEPARATOR) {
            $basePath = $basePath . DIRECTORY_SEPARATOR;
        }

        $this->filepath = $basePath . $filename;

        $this->fs = $fs ?? new FileSystem();
        $this->writeFh = $this->fs->fopen($this->filepath, 'w');
        $this->readFh = $this->fs->fopen($this->filepath, 'r');
        $this->urlArr = [];

        if ($this->readFh === false or $this->writeFh === false) {
            throw new RuntimeException('failed to open temporary url storage file on path: ' . $this->filepath);
        }
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
        $this->urlArr[self::URL_KEY_LOC] = $loc;
        $this->urlArr[self::URL_KEY_LASTMOD] = isset($lastModified) ? $lastModified->format(DateTime::ATOM) : null;
        $this->urlArr[self::URL_KEY_CHANGEFREQ] = $changeFrequency;
        $this->urlArr[self::URL_KEY_PRIORITY] = $priority;
        $this->urlArr[self::URL_KEY_ALTERNATES] = isset($alternates) ? serialize($alternates) : null;
        $this->fs->fputcsv($this->writeFh, $this->urlArr);
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
    public function current(): ?SplFixedArray
    {
        while ($this->current === null && !$this->isEofReached) {
            $this->current = $this->readLineToUrlObj();
        }

        if ($this->current === null) {
            throw new \OutOfBoundsException('Invalid index or out of range');
        }

        return $this->current;
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->current = $this->readLineToUrlObj();
    }

    public function rewind(): void
    {
        $this->fs->rewind($this->readFh);
    }

    private function readLineToUrlObj() : ?SplFixedArray {
        $url = $this->fs->fgetcsv($this->readFh);

        if ($url === null) {
            throw new RuntimeException('Failed to read line from url storage file: ' . $this->filepath);
        }

        if ($url === false) {
            $this->isEofReached = true;
            return null;
        }

        if (strlen($url[self::URL_KEY_LASTMOD]) === 0) {
            $url[self::URL_KEY_LASTMOD] = null;
        }

        if (strlen($url[self::URL_KEY_CHANGEFREQ]) === 0) {
            $url[self::URL_KEY_CHANGEFREQ] = null;
        }

        if (strlen($url[self::URL_KEY_PRIORITY]) === 0) {
            $url[self::URL_KEY_PRIORITY] = null;
        }

        if (strlen($url[self::URL_KEY_ALTERNATES]) === 0) {
            $url[self::URL_KEY_ALTERNATES] = [];
        } else {
            $url[self::URL_KEY_ALTERNATES] = unserialize($url[self::URL_KEY_ALTERNATES]);
        }

        return SplFixedArray::fromArray($url);
    }
}
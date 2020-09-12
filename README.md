# PHP Sitemap Generator

[![Build Status](https://travis-ci.org/icamys/php-sitemap-generator.svg?branch=master)](https://travis-ci.org/icamys/php-sitemap-generator)
[![codecov.io](https://codecov.io/github/icamys/php-sitemap-generator/coverage.svg?branch=master)](https://codecov.io/github/icamys/php-sitemap-generator?branch=master)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://poser.pugx.org/icamys/php-sitemap-generator/v/stable.png)](https://packagist.org/packages/icamys/php-sitemap-generator)
[![Total Downloads](https://poser.pugx.org/icamys/php-sitemap-generator/downloads)](https://packagist.org/packages/icamys/php-sitemap-generator)

Library for sitemap generation and submission.

Internally uses SplFixedArrays, thus is faster and uses less memory then alternatives.

Features:
* Follows [sitemaps.org](https://sitemaps.org/) protocol
* Supports alternative links for multi-language pages (see [google docs](https://webmasters.googleblog.com/2012/05/multilingual-and-multinational-site.html))

Usage example:

```php
<?php

include "vendor/autoload.php";

// Setting the current working directory to be output directory
// for generated sitemaps (and, if needed, robots.txt)
// The output directory setting is optional and provided for demonstration purpose.
// By default output is written to current directory. 
$outputDir = getcwd();

$generator = new \Icamys\SitemapGenerator\SitemapGenerator('example.com', $outputDir);

// will create also compressed (gzipped) sitemap
$generator->toggleGZipFileCreation();

// determine how many urls should be put into one file;
// this feature is useful in case if you have too large urls
// and your sitemap is out of allowed size (50Mb)
// according to the standard protocol 50000 is maximum value (see http://www.sitemaps.org/protocol.html)
$generator->setMaxURLsPerSitemap(50000);

// sitemap file name
$generator->setSitemapFileName("sitemap.xml");

// sitemap index file name
$generator->setSitemapIndexFileName("sitemap-index.xml");

// alternate languages
$alternates = [
    ['hreflang' => 'de', 'href' => "http://www.example.com/de"],
    ['hreflang' => 'fr', 'href' => "http://www.example.com/fr"],
];

// adding url `loc`, `lastmodified`, `changefreq`, `priority`, `alternates`
$generator->addURL('http://example.com/url/path/', new DateTime(), 'always', 0.5, $alternates);

// generate internally a sitemap
$generator->createSitemap();

// write early generated sitemap to file(s)
$generator->writeSitemap();

// update robots.txt file in output directory or create a new one
$generator->updateRobots();

// submit your sitemaps to Google, Yahoo, Bing and Ask.com
$generator->submitSitemap();
```

### Advanced usage

#### Dealing with insufficient RAM memory 

If you get memory exhaustion errors such as:

```
PHP Fatal error: Allowed memory size of 134217728 bytes exhausted...
```

and you can't modify your php.ini file to use more memory, 
there's an option to use your file system instead of RAM memory in order to store urls 
before they will get written to sitemap.

To use the file system instead of the memory just pass `SitemapGenerator::STORAGE_TYPE_FS` 
as storage type parameter to the sitemap generator constructor and use this as you normally would:

```php
use \Icamys\SitemapGenerator\SitemapGenerator as SitemapGenerator;

$generator = new SitemapGenerator('example.com', 'current-dir/', SitemapGenerator::STORAGE_TYPE_FS);

// add urls, generate sitemap, write it to file...
```

### Testing

Run tests with command (tests may take some time):

```bash
$ ./vendor/bin/phpunit
```

Run code coverage:

```bash
$ ./vendor/bin/phpunit --coverage-html ./coverage
```

### Changelog

New in 2.0.0:
* Major code rework
* No more public properties in generator, using only methods
* Removed `addUrls` method in favor of `addUrl`
* Fixed bug with robots.txt update
* Fixed bug in addURL method (empty loc)
* Unit tests added for quality assurance
* Updated limits according to [sitemaps spec](https://www.sitemaps.org/protocol.html)
* Updated search engines urls
* Added change frequency validation

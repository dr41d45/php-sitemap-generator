<?php

include "src/SitemapGenerator.php";
include "database0.php";
include "validator.php";

/* // Setting the current working directory to be output directory
// for generated sitemaps (and, if needed, robots.txt)
// The output directory setting is optional and provided for demonstration purpose.
// By default output is written to current directory.
$outputDir = getcwd();

$generator = new \Icamys\SitemapGenerator\SitemapGenerator('', $outputDir);

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
// $generator->setSitemapIndexFileName("sitemap-index.xml");

// alternate languages
// $alternates = [
//     ['hreflang' => 'de', 'href' => "http://www.example.com/de"],
//     ['hreflang' => 'fr', 'href' => "http://www.example.com/fr"],
// ];

// adding url `loc`, `lastmodified`, `changefreq`, `priority`, `alternates`
$generator->addURL('http://example.com/url/path/', new DateTime(), 'always', 0.5, $alternates);
$generator->addURL('http://example.com/url/path/1', new DateTime(), 'always', 0.5, $alternates);

// generate internally a sitemap
$generator->createSitemap();

// write early generated sitemap to file(s)
$generator->writeSitemap();

// update robots.txt file in output directory or create a new one
$generator->updateRobots();

// submit your sitemaps to Google, Yahoo, Bing and Ask.com
// $generator->submitSitemap();
 */

// $outputDir = getcwd();
$outputDir = 'traficero';

$generator = new \Icamys\SitemapGenerator\SitemapGenerator('', $outputDir);

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
// $generator->setSitemapIndexFileName("sitemap-index.xml");

$alternates = [];

// adding url `loc`, `lastmodified`, `changefreq`, `priority`, `alternates`
// $generator->addURL('http://example.com/url/path/', new DateTime(), 'always', 0.5, $alternates);

// generate internally a sitemap

// submit your sitemaps to Google, Yahoo, Bing and Ask.com
// $generator->submitSitemap();

$db = new db(HOST, USERNAME, PASSWORD, DATABASE);
$q = "SELECT `Code` as c  FROM `prefix_list` WHERE `carrier_id` IS NOT NULL LIMIT 1";
$u = $db->query($q)->fetchAll();
// var_dump($u);


// $generator->addURL('https://www.traficero.com', new DateTime(), 'monthly', 0.5, $alternates);
// https://www.sitemaps.org/protocol.html#changefreqdef

foreach ($u as $k => $v) {
    echo "\n $k prefix: " . $v['c'] . "  ";
    $v = strtolower($v['c']);
    for ($x = 0; $x < 10; $x++) {
echo " $x, ";
        // $generator->addURL('https://www.traficero/sitemap/' . $v . "_{$x}_sitemap.xml", new DateTime(), 'yearly', 0.5, $alternates);
        container_function($v, $x);
    }
}
// $generator->createSitemap();

// write early generated sitemap to file(s)
// $generator->writeSitemap();

// update robots.txt file in output directory or create a new one
// $generator->updateRobots();

function container_function($v, $mm)
{
    $starttime = microtime(true); // Top of page
    $outputDir = 'traficero';
    $min = $mm * 100000;
    $max = $mm * 100000 + 99999;
    $generator = new \Icamys\SitemapGenerator\SitemapGenerator('', $outputDir);
    $validator = new Validator\Validator;
    // $generator->toggleGZipFileCreation();
    $generator->setMaxURLsPerSitemap(50000);
    $generator->setSitemapFileName($v . "_{$mm}_containers.xml");
    $alternates = [];
    for ($x = $min; $x < $max; $x++) {
        $c = $validator->generate(substr($v, 0, 3), 'U', $x, $x + 1);
        // var_dump($c);
        $generator->addURL('https://traficero.com/track/container/' . $c[$x], new DateTime(), 'yearly', 0.5, $alternates);
    }
    $generator->createSitemap();
    $generator->writeSitemap();
    $endtime = microtime(true); // Bottom of page
    printf("loaded in %f seconds.\n", $endtime - $starttime );

}
echo "FIN";
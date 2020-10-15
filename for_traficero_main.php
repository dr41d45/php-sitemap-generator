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
$outputDir = 'main';

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



$generator->addURL('https://www.traficero.com', new DateTime(), 'monthly', 1, $alternates);
$generator->addURL('https://traficero.com/track/carriers/', new DateTime(), 'monthly', 0.5, $alternates);
$generator->addURL('https://traficero.com/track/about/', new DateTime(), 'monthly', 0.5, $alternates);
$generator->addURL('https://traficero.com/track/contact/', new DateTime(), 'monthly', 0.5, $alternates);
// https://www.sitemaps.org/protocol.html#changefreqdef



$db = new db(HOST, USERNAME, PASSWORD, DATABASE);
$q = "SELECT `url_slogan` FROM `bic_holder_list` LIMIT 50";
$u = $db->query($q)->fetchAll();
// var_dump($u);
foreach ($u as $k => $v) {
        $generator->addURL('https://www.traficero.com/track/carrier/' . $v['url_slogan'] . "/", new DateTime(), 'yearly', 0.65, $alternates);
    }


    /**sitemap to sitemaps of prefixes*/
   /*  $q = "SELECT `Code` as c  FROM `prefix_list` WHERE `carrier_id` IS NOT NULL LIMIT 10000";
    $q = "SELECT `Code` as c  FROM `prefix_list` WHERE `Code` IN ('MSKU','MRKU','OOLU','PONU','TCLU','CMAU','TGHU','MNBU','MEDU','MSCU','TEMU','SUDU','TCNU','FCIU','YMLU','BMOU','CBHU','GESU','EMCU','MWCU','ECMU','OVZU','CAIU','SEGU','FSCU','OPDU','GLDU','TRLU','EISU','CCLU','DFSU','HLXU','TCKU','MAEU','SANU','EITU','CAXU','MAGU','HASU','TTNU','CLHU','CRXU','BSIU','MSWU','DRYU','TRHU','MRSU','CSLU','CNEU','CGMU','INKU','HJCU','TLLU','NYKU','TRIU','WECU','MWMU','OOCU','APZU','GATU','IPXU','UESU','EGSU') AND `carrier_id` IS NOT NULL";
    $u = $db->query($q)->fetchAll();
    // var_dump($u);
    foreach ($u as $k => $v) {
        $v = strtolower($v['c']);
        $generator->addURL('https://www.traficero.com/sitemap/' . $v . "_sitemap.xml.gz", new DateTime(), 'yearly', 0.75, $alternates);
        
    } */
    
    /**sitemap to sitemaps of containers*/
    
   /*  $u = $db->query($q)->fetchAll();
    // var_dump($u);
    foreach ($u as $k => $v) {
        $v = strtolower($v['c']);
        for ($x = 0; $x < 10; $x++) {
            $generator->addURL('https://www.traficero.com/sitemap/' . $v . "_" .$x. "_sitemap1.xml.gz", new DateTime(), 'yearly', 0.85, $alternates);
            $generator->addURL('https://www.traficero.com/sitemap/' . $v . "_" .$x. "_sitemap2.xml.gz", new DateTime(), 'yearly', 0.85, $alternates);
        }

    } */





    $generator->createSitemap();
// write early generated sitemap to file(s)
$generator->writeSitemap();
// update robots.txt file in output directory or create a new one
$generator->updateRobots();
echo "FIN";
<?php

include "../src/SitemapGenerator.php";
// include "database0.php";
include "../validator.php";

mkdir('xml_4_container');
$u = array(
['c' => 'EGHU'],
['c' => 'EGSU'],
['c' => 'EISU'],
['c' => 'EMCU'],
['c' => 'HMCU'],

['c' => 'HDMU'],
['c' => 'HMMU'],
['c' => 'KOCU'],

['c' => 'PCIU'],
['c' => 'PILU'],

['c' => 'ZCLU'],
['c' => 'ZCSU'],
['c' => 'ZIMU'],
['c' => 'ZMOU'],
);
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
    $outputDir = 'xml_4_container';
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
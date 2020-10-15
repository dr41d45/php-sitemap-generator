<?php

include "src/SitemapGenerator.php";
include "database0.php";
include "validator.php";


$db = new db(HOST, USERNAME, PASSWORD, DATABASE);
$q = "SELECT `Code` as c  FROM `prefix_list` WHERE `carrier_id` IS NOT NULL LIMIT 10000";
$u = $db->query($q)->fetchAll();
// var_dump($u);


// $generator->addURL('https://www.traficero.com', new DateTime(), 'monthly', 0.5, $alternates);
// https://www.sitemaps.org/protocol.html#changefreqdef


foreach ($u as $k => $v) {
    echo "\n $k prefix: " . $v['c'] . " ";
    // $v = strtolower($v['c']);
    container_function($v['c']);
    
}

function container_function($v)
{
    $starttime = microtime(true); // Top of page
    $outputDir = 'du';
    $generator = new \Icamys\SitemapGenerator\SitemapGenerator('', $outputDir);
    $validator = new Validator\Validator;
    // $generator->toggleGZipFileCreation();
    $generator->setMaxURLsPerSitemap(50000);
    $generator->setSitemapFileName(strtolower($v) . "_sitemap.xml");
    $alternates = [];
    for ($x = 0; $x <= 9999; $x++) {
        $y = $x *100;
        // echo " $x,\n ";
        $generator->addURL('https://traficero.com/track/list/'. $v .'/'.$y , new DateTime(), 'yearly', 0.5, $alternates);
    }
    $generator->createSitemap();
    $generator->writeSitemap();
    $endtime = microtime(true); // Bottom of page
    printf("loaded in %f seconds.\n", $endtime - $starttime );



}

echo "FIN";
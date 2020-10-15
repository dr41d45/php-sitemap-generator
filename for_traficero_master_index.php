<?php

include "src/SitemapGenerator.php";
include "database0.php";
include "validator.php";

$db = new db(HOST, USERNAME, PASSWORD, DATABASE);

$s = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<sitemapindex xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
$s = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

    /**sitemap to sitemaps of prefixes*/
    $q = "SELECT `Code` as c  FROM `prefix_list` WHERE `carrier_id` IS NOT NULL LIMIT 10000";
    $q = "SELECT `Code` as c  FROM `prefix_list` WHERE `Code` IN ('MSKU','MRKU','OOLU','PONU','TCLU','CMAU','TGHU','MNBU','MEDU','MSCU','TEMU','SUDU','TCNU','FCIU','YMLU','BMOU','CBHU','GESU','EMCU','MWCU','ECMU','OVZU','CAIU','SEGU','FSCU','OPDU','GLDU','TRLU','EISU','CCLU','DFSU','HLXU','TCKU','MAEU','SANU','EITU','CAXU','MAGU','HASU','TTNU','CLHU','CRXU','BSIU','MSWU','DRYU','TRHU','MRSU','CSLU','CNEU','CGMU','INKU','HJCU','TLLU','NYKU','TRIU','WECU','MWMU','OOCU','APZU','GATU','IPXU','UESU','EGSU') AND `carrier_id` IS NOT NULL";
    $u = $db->query($q)->fetchAll();
    // var_dump($u);
    foreach ($u as $k => $v) {
        $v = strtolower($v['c']);
        $s .= '<sitemap>'.PHP_EOL.'<loc>https://traficero.com/sitemaps/' . $v . "_sitemap.xml</loc>".PHP_EOL.'</sitemap>'.PHP_EOL;
        
    }
    
    /**sitemap to sitemaps of containers*/
    
    $u = $db->query($q)->fetchAll();
    // var_dump($u);
    foreach ($u as $k => $v) {
        $v = strtolower($v['c']);
        for ($x = 0; $x < 10; $x++) {
            // $s .= '<sitemap>'.PHP_EOL.'<loc>https://traficero.com/sitemaps/' . $v . "_" .$x. "_containers1.xml</loc>".PHP_EOL.'</sitemap>'.PHP_EOL;
           // $s .= '<sitemap>'.PHP_EOL.'<loc>https://traficero.com/sitemaps/' . $v . "_" .$x. "_containers2.xml</loc>".PHP_EOL.'</sitemap>'.PHP_EOL;
        }

    }
    $s .= '</sitemapindex>'.PHP_EOL;
// echo "FIN";

echo $s;
// > site.txt
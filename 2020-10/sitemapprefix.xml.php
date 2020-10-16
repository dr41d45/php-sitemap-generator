<?php

$s = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
$s = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

/**sitemap to sitemaps of prefixes*/
$q = array('MSKU', 'MRKU', 'OOLU', 'PONU', 'TCLU', 'CMAU', 'TGHU', 'MNBU', 'MEDU', 'MSCU', 'TEMU', 'SUDU', 'TCNU', 'FCIU', 'YMLU', 'BMOU', 'CBHU', 'GESU', 'EMCU', 'MWCU', 'ECMU', 'OVZU', 'CAIU', 'SEGU', 'FSCU', 'OPDU', 'GLDU', 'TRLU', 'EISU', 'CCLU', 'DFSU', 'HLXU', 'TCKU', 'MAEU', 'SANU', 'EITU', 'CAXU', 'MAGU', 'HASU', 'TTNU', 'CLHU', 'CRXU', 'BSIU', 'MSWU', 'DRYU', 'TRHU', 'MRSU', 'CSLU', 'CNEU', 'CGMU', 'INKU', 'HJCU', 'TLLU', 'NYKU', 'TRIU', 'WECU', 'MWMU', 'OOCU', 'APZU', 'GATU', 'IPXU', 'UESU', 'EGSU', 'EGHU', 'EISU', 'EMCU', 'HMCU', 'HDMU', 'HMMU', 'KOCU', 'PCIU', 'PILU', 'ZCLU', 'ZCSU', 'ZIMU', 'ZMOU');

// var_dump($u);
foreach ($q as $k => $v) {
    $s .= '<sitemap>' . PHP_EOL . '<loc>https://traficero.com/sitemaps/' . strtolower($v) . "_sitemap.xml</loc>" . PHP_EOL . '</sitemap>' . PHP_EOL;
}


foreach ($q as $k => $v) {
    $v = strtolower($v);
    for ($x = 0; $x < 10; $x++) {
        $s .= '<sitemap>' . PHP_EOL . '<loc>https://www.traficero.com/sitemaps/' . $v . "_" .$x. "_containers1.xml</loc>" . PHP_EOL . '</sitemap>' . PHP_EOL;
        $s .= '<sitemap>' . PHP_EOL . '<loc>https://www.traficero.com/sitemaps/' . $v . "_" .$x. "_containers2.xml</loc>" . PHP_EOL . '</sitemap>' . PHP_EOL;
    }

} 



$s .= '</sitemapindex>' . PHP_EOL;
echo "FIN";
// echo $s;
file_put_contents('sitemapprefix.xml', $s);

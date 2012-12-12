<?php

define(GALLERY, "http://stockcharts.com/freecharts/gallery.html?");
define(STOCKTXT, "data/stocks.json");

$mcadNyse = 'http://stockcharts.com/def/servlet/SC.scan?s=TCDE[BA0]L[T.T_EQ_S]![T.E_EQ_Y]![AS0,20,TV_GT_40000]![BA1_LT_0]![BA0_GE_BB0]![BA1_LT_BB1]![BA2_LT_BB2]![BA3_LT_BB3]![BC3_LT_BB3*0.25]';
$mcadNas = 'http://stockcharts.com/def/servlet/SC.scan?s=TCDE[BA0]L[T.T_EQ_S]![T.E_EQ_N]![AS0,20,TV_GT_40000]![BA1_LT_0]![BA0_GE_BB0]![BA1_LT_BB1]![BA2_LT_BB2]![BA3_LT_BB3]![BC3_LT_BB3*0.25]';

$links = array(
    "http://stockcharts.com/def/servlet/SC.scan?s=TCDE[BA0]L[T.T_EQ_S]![T.E_EQ_Y]![AS0,20,TV_GT_40000]![BA1_LT_0]![BA0_GE_BB0]![BA1_LT_BB1]![BA2_LT_BB2]![BA3_LT_BB3]![BC3_LT_BB3*0.25]",
    "http://stockcharts.com/def/servlet/SC.scan?s=TCDE[BA0]L[T.T_EQ_S]![T.E_EQ_N]![AS0,20,TV_GT_40000]![BA1_LT_0]![BA0_GE_BB0]![BA1_LT_BB1]![BA2_LT_BB2]![BA3_LT_BB3]![BC3_LT_BB3*0.25]",
    "http://stockcharts.com/def/servlet/SC.scan?s=TVDE[BU0,20][T.T_EQ_S]![T.E_EQ_N]![AS0,20,TV_GT_40000]![BU0,20_GT_100]![BU1,20_LE_100]![BU2,20_LT_100]",
);
$stocks = array();
$now = date('M-d-Y');

$contentFile = __DIR__ . "/" . STOCKTXT;
if (file_exists($contentFile)) {
    $stocks = json_decode(file_get_contents($contentFile));
}

if ($stocks != null && isset($stocks->$now)) {
    foreach ($stocks->$now as $stockInfo) {
        $stock = $stockInfo[0];
        openStockChart($stock);
    }
} else {
    $todayStocks = array();
    foreach ($links as $link) {
        $matches = getStocks($link);
        getStockInfo($matches[1][1], $todayStocks);
        getStockInfo($matches[1][2], $todayStocks);
    }
    $todayAll = array($now => $todayStocks);
    $all = $todayAll;
//    $all = array_merge($todayAll, $stocks);
    $allStr = json_encode($all);
    file_put_contents(STOCKTXT, $allStr);
}

function getStockInfo($stock, &$totalStocks)
{
    $url = 'http://www.google.com/finance?client=ig&q=' . $stock;
    $content = file_get_contents($url);
    preg_match_all('/<td class="val">([\d- ,\/M\.]+)/', $content, $matches);

    if ((int)$matches[1][2] - 10 > 0) {
        $totalStocks[] = array($stock, $matches[1]);
        return array($stock, $matches[1]);
    }
    return null;
}

function getStocks($url)
{
    $mcadStr = file_get_contents($url);
    //ui?s=ACC"
    preg_match_all('/ui\?s=([a-zA-Z]*)/', $mcadStr, $matches);
    return $matches;
}

function openStockChart($stock)
{
    $url = GALLERY . $stock;
    $openStr = "open $url";
    shell_exec($openStr);
}
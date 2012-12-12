<?php

define(GALLERY, "http://stockcharts.com/freecharts/gallery.html?");
define(STOCKTXT, "stocks.json");

$mcadNyse = 'http://stockcharts.com/def/servlet/SC.scan?s=TCDE[BA0]L[T.T_EQ_S]![T.E_EQ_Y]![AS0,20,TV_GT_40000]![BA1_LT_0]![BA0_GE_BB0]![BA1_LT_BB1]![BA2_LT_BB2]![BA3_LT_BB3]![BC3_LT_BB3*0.25]';
$mcadNas = 'http://stockcharts.com/def/servlet/SC.scan?s=TCDE[BA0]L[T.T_EQ_S]![T.E_EQ_N]![AS0,20,TV_GT_40000]![BA1_LT_0]![BA0_GE_BB0]![BA1_LT_BB1]![BA2_LT_BB2]![BA3_LT_BB3]![BC3_LT_BB3*0.25]';

$stocks = array();
$now = date('M-d-Y');

if (file_exists(STOCKTXT)) {
    $stocks = json_decode(file_get_contents(STOCKTXT));
}

if (!isset($stocks->$now)) {
    $todayStocks = array();
    $matches = getStocks($mcadNyse);
    $todayStocks[] = addStockInfo($matches[1][1]);
    $todayStocks[] = addStockInfo($matches[1][2]);

    $matches = getStocks($mcadNas);
    $todayStocks[] = addStockInfo($matches[1][1]);
    $todayStocks[] = addStockInfo($matches[1][2]);

    $todayAll = array($now => $todayStocks);
    $all = array_merge($todayAll, $stocks);
    $allStr = json_encode($all);
    file_put_contents(STOCKTXT, $allStr);
}

function addStockInfo($stock)
{
    global $stocks;
    $url = 'http://www.google.com/finance?client=ig&q=' . $stock;
    $content = file_get_contents($url);
    preg_match_all('/<td class="val">([\d- ,\/M\.]+)/', $content, $matches);

    $stockInfo = array($stock, $matches[1]);
    $stocks[] = $stockInfo;
}

function getStocks($url)
{

    $mcadStr = file_get_contents($url);
    //ui?s=ACC"
    preg_match_all('/ui\?s=([a-zA-Z]*)/', $mcadStr, $matches);
    $stock0 = $matches[1][1];
    $stock1 = $matches[1][2];

    openStockChart($stock0);
    openStockChart($stock1);

    return $matches;
}

function openStockChart($stock)
{
    $url = GALLERY . $stock;
    $openStr = "open $url";
//    shell_exec($openStr);
}

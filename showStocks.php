<?php

define(GALLERY, "http://stockcharts.com/freecharts/gallery.html?");
define(STOCKTXT, __DIR__ . "/data/stocks.json");

$stocks = array();
date_default_timezone_set('UTC');
$now = date('M-d-Y');

$contentFile =   STOCKTXT;

$allInfo = array();

if (file_exists($contentFile)) {
    $stocks = json_decode(file_get_contents($contentFile), true);
    foreach ($stocks as $dayInfo) {
        array_push($allInfo, $dayInfo);
    }
}

if ($stocks != null) {
    foreach ($stocks as  $dayInfo) {
        foreach ($dayInfo as $dayKey => $info) {
            if ($dayKey == $now) {
                foreach($info as $s) {
                    $stock = $s[0];
                    openStockChart($stock);
                }
            }
        }
    }
}

function openStockChart($stock)
{
    $url = GALLERY . $stock;
    $openStr = "open $url";
    shell_exec($openStr);
}

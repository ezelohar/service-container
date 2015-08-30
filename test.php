<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 8/30/15
 * Time: 9:50 PM
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);


$p = 1;
$d = 2;

$data['t'] = function () use ($p, $d) {
	return $p+$d;
};


echo $data['t']();
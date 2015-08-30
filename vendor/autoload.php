<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 8/30/15
 * Time: 7:36 PM
 */

spl_autoload_register(function ($class) {
	$class = str_replace("\\", "/", $class);
	include $class . '.php';
});
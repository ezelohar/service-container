<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 8/30/15
 * Time: 9:39 PM
 */

namespace Service;

interface ServiceInterfaceProvider {
	public function register(Container $container);
}
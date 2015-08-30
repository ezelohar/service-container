<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 8/30/15
 * Time: 9:44 PM
 */

namespace Service\User;

use Service\ServiceInterfaceProvider;
use Service\Container;

class UserServiceProvider implements ServiceInterfaceProvider
{
	public function register(Container $container)
	{
		$db = $container->get("db");
		$container->set("UserService", function () use ($db) {
			return new UserService($db);
		});
		$container->set("UserApplicationService", function () use ($db) {
			return new UserApplicationService($db);
		});
	}
}
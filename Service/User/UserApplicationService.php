<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 8/30/15
 * Time: 9:45 PM
 */

namespace Service\User;

use Service\User\UserServiceBase;

class UserApplicationService extends UserServiceBase{
	public function getUserApplications($userId) {
		return $userId;
	}
}
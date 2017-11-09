<?php

namespace app\Services\Permission;

class PermissionService
{
	/**
	 * The constructor. Assign the user attribute.
	 *
	 * @param \App\Models\User $user The user model.
	 */
	public function __construct($user = null)
	{
		$this->user = $user;
	}

	/**
	 * This method downgraded current users permission
	 * @return bool
	 */
	public function downgradeCurrentPermission()
	{
		$permission = $this->getCurrentPermission();
		$this->user->userPermissions()->delete($permission);
		return true;
	}

	/**
	 *  This method will returning all users permissions
	 * @return mixed
	 */
	public function getAllPermissions()
	{
		return $this->user->userPermissions()->get();
	}

	/**
	 * This method will returning current permission
	 * @return mixed
	 */
	public function getCurrentPermission()
	{
		return $this->user->subscriptions()->orderBy('last_used_at', 'desc')
			->first();
	}
}
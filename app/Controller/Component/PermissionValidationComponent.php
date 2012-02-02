<?php
/*
 * This file is part of BeePublished which is based on CakePHP.
 * BeePublished is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3
 * of the License, or any later version.
 * BeePublished is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public
 * License along with BeePublished. If not, see
 * http://www.gnu.org/licenses/.
 *
 * @copyright 2012 Duale Hochschule Baden-Württemberg Mannheim
 * @author Philipp Scholl
 *
 * @description Check user rights for specified functionality
 */

class PermissionValidationComponent extends Component {
	var $components = array('Auth');
	
	public function getUserRoleId(){
		$this->Role = ClassRegistry::init('Role');
		
		//get currently logged in user and his role
		$user = $this->Auth->user();
		if($user == null){
			$guestRole = $this->Role->findByName('Guest');
			$userRoleId = $guestRole['Role']['id'];
		} else{
			$userRoleId = (int)$user['role_id'];
		}
		return $userRoleId;
	}
	
	public function getPermissions($pluginId = null){
		$this->Permission = ClassRegistry::init('Permission');
		//get currently logged in user and his role
		$user = $this->Auth->user();
		var_dump($user);
		if($user == null){
			$guestRole = $this->Role->findByName('Guest');
			$userRoleId = $guestRole['Role']['id'];
		} else{
			$userRoleId = (int)$user['role_id'];
		}
		
		$allPermissionsOfPlugin = $this->Permission->find('all', array('conditions' => array('plugin_id' => $pluginId)));
		$allActions = array();
		foreach ($allPermissionsOfPlugin as $aPermission){
			$action = $aPermission['Permission']['action'];
			$actionAllowed = $this->internalActionAllowed($userRoleId, $aPermission);
			$allActions[$action] = $actionAllowed;
		}
		
		return $allActions;
	}
	
	public function actionAllowed($pluginId = null, $action = null, $throwException = false){
		$this->Permission = ClassRegistry::init('Permission');
		$this->Role = ClassRegistry::init('Role');
		
		//get currently logged in user and his role
		$user = $this->Auth->user();
		if($user == null){
			$guestRole = $this->Role->findByName('Guest');
			$userRoleId = $guestRole['Role']['id'];
		} else{
			$userRoleId = (int)$user['role_id'];
		}
		

		//get required permission for given plugin and action
		$permissionQueryOptions = array('conditions' => array('plugin_id' => $pluginId, 'action' => $action));
		$permissionEntry = $this->Permission->find('first', $permissionQueryOptions);
		
		$actionAllowed = false;
		//read currentRoleId -- initial value equals minimum required role id
		$currentRoleId = (int)$permissionEntry['Permission']['role_id'];
		//read parentRole -- initial value equals minimum required role
		$parentRole = $this->Role->findById($currentRoleId);
		
		while (true) {
			if ($currentRoleId == $userRoleId){
				//user is allowed to perform the action
				$actionAllowed = true;
				break;
			} else{
				if($parentRole['Role']['parent_id'] != null){
					//get parent role and set currentRoleId
					$parentRole = $this->Role->findById($parentRole['Role']['parent_id']);
					$currentRoleId = (int)$parentRole['Role']['id'];
				} else{
					//there is no more parent role and user is not allowed to perform the action
					break;
				}
			}
		}
        if ($throwException && !$actionAllowed) {
            throw new ForbiddenException('You are not allowed to access this page.',401);
        }
		return $actionAllowed;
	}
	
	private function internalActionAllowed($userRoleId = null, $permissionEntry = null){
		$this->Role = ClassRegistry::init('Role');
		$actionAllowed = false;
		//read currentRoleId -- initial value equals minimum required role id
		$currentRoleId = (int)$permissionEntry['Permission']['role_id'];
		//read parentRole -- initial value equals minimum required role
		$parentRole = $this->Role->findById($currentRoleId);
		
		while (true) {
			if ($currentRoleId == $userRoleId){
				//user is allowed to perform the action
				$actionAllowed = true;
				break;
			} else{
				if($parentRole['Role']['parent_id'] != null){
					//get parent role and set currentRoleId
					$parentRole = $this->Role->findById($parentRole['Role']['parent_id']);
					$currentRoleId = (int)$parentRole['Role']['id'];
				} else{
					//there is no more parent role and user is not allowed to perform the action
					break;
				}
			}
		}
		return $actionAllowed;
	}
}

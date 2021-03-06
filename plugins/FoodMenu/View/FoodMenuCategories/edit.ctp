<?php
	echo $this->element('admin_menu');
	
	$createAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'create');
	$editAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'edit');
	$deleteAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'delete');
	
	echo $this->Session->flash();
	echo $this->Form->create('FoodMenuAddEntries', array('url' => array('plugin' => 'FoodMenu', 'controller' => 'FoodMenuCategoriesFoodMenuEntries', 'action' => 'index', $category['FoodMenuCategory']['name'], $category['FoodMenuCategory']['id'])));
	$editAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'edit');
	if($editAllowed){
		echo '<h1>'.(__('Add Entries to category')).'</h1>';
		echo $this->Form->end(__('Add entries'));
		echo '<br /><hr /><br />';
		echo '<h1>'.(__('Edit Category')).'</h1>';
		echo $this->Form->create('FoodMenuCategory', array('url' => array('plugin' => 'FoodMenu', 'controller' => 'FoodMenuCategories', 'action' => 'edit')));
		echo $this->Form->hidden('id', array('value' => $category['FoodMenuCategory']['id']));
		echo $this->Form->input('name', array('value' => $category['FoodMenuCategory']['name'], 'label' => (__('Name:'))));
		echo $this->Form->end(__('Save'));
	}
?>
<?php
$this->Html->css('/gallery/css/galleries', NULL, array('inline' => false));

echo $this->element('admin_menu',array("ContentId" => $ContentId, "mContext" => $mContext));
	
	$createAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'create');
	$editAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'edit');
	$deleteAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'delete');
	
	
echo "<h1> ".__('Manage your galleries')."</h1>";
echo $this->Session->flash('GalleryNotification');
echo '<div class="galleryinfo">'.__('Here you can edit, delete your galleries or assign pictures to them.').'</div>';

echo '<table>';
	echo '<thead>';
		echo '<tr>';
			echo '<th></th>';
			echo '<th>'.__('Title').'</th>';
			echo '<th>'.__('Description').'</th>';
			echo '<th></th>';
			echo '<th></th>';
			echo '<th></th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	echo $this->Form->create('selectGalleries', array(
				'url' => array(
				'plugin' => 'Gallery',
				'controller' => 'ManageGalleries',
				'action' => 'deleteSelected',$ContentId),
				'onsubmit'=>'return confirm(\''.__('Do you really want to delete the selected galleries?').'\');'));
foreach ($data['AllGalleries'] as $gallery){
	echo "<tr>";
		echo '<td>';
			echo $this->Form->checkbox($gallery['GalleryEntry']['id']);
		echo '</td>'; 
		echo "<td>".$gallery['GalleryEntry']['title']."</td>";
		echo "<td>".$gallery['GalleryEntry']['description']."</td>";
	
		echo '<td>';
		if($editAllowed){
			echo $this->Html->link($this->Html->image('edit.png', array(
							'height' => 20, 
							'width' => 20, 
							'alt' => __('[x]Edit'))),
							array(
								'plugin' => 'Gallery', 
								'controller' => 'ManageGalleries', 
								'action' => 'edit', $gallery['GalleryEntry']['id'],$ContentId),
							array(
								'escape' => false, 
								'title' => __('Edit Gallery')));
		};
		echo '</td>';
	
		echo '<td>';
		
			if($deleteAllowed){
			echo $this->Html->link($this->Html->image('delete.png', array(
							'height' => 20, 
							'width' => 20, 
							'alt' => __('[x]Delete'))),
							array(
								'plugin' => 'Gallery', 
								'controller' => 'ManageGalleries', 
								'action' => 'delete',$gallery['GalleryEntry']['id'],$ContentId),
							array(
								'escape' => false, 
								'title' => __('Delete Gallery')),
								__('Do you really want to delete this Gallery?'));
			}
		echo '</td>';
	
		echo '<td>';
			if($editAllowed){
				echo $this->Html->image('add2.png',array('style' => 'float: left', 'width' => '20px', 'alt' => '[]Assign', 'url' => array('plugin' => 'Gallery', 'controller' => 'ManageGalleries', 'action' => 'assignImages',$gallery['GalleryEntry']['id'],$ContentId)));
			}		
		echo '</td>';
		
	echo "</tr>";
}
	echo '</tbody>';
	echo '<tfoot>';	
			echo '<tr>';
				echo '<td>';
				echo $this->Html->image('arrow.png', array(
						'height' => 20,
						'width' => 20));
				echo '</td>';
				echo '<td>';
					echo $this->Form->submit(__('Delete'), array(
							'height' => 20,
							'width' => 20,
							'alt' => __('[x]Delete')));
					echo $this->Form->end();
				echo '</td>';
			echo '</tr>';
		echo '</tfoot>';
echo "</table>";
?>
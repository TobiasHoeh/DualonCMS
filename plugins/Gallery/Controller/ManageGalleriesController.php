<?php
class ManageGalleriesController  extends GalleryAppController{
	public $layout = 'overlay';
	
	public $components = array('Gallery.Gallery','Gallery.GalleryPictureComp');
	
	function beforeRender()
    {
        parent::beforeRender();

        //Get PluginId for PermissionsValidation Helper
        $pluginId = $this->getPluginId();
        $this->set('pluginId', $pluginId);
    }
	
	
	/**
	 * Index function, to list all available Galleries
	 * @param int $contentId
	 */
	public function index($contentId, $menue_context){
			
				
		$allGalls= $this->Gallery->getAllGalleries($this);
		
		$data = array(	'AllGalleries' => $allGalls);
		$pic_array = array();
		$index = 0;
		foreach( $this->GalleryPictureComp->getAllPictures($this) as $picture){
			
			$pic_array[$index] = array($picture['id'] =>  $picture['id']);
			$index++;
		}
		
		$this->set('data',$data);
		$this->set('pictures', $pic_array);
		$this->set('mContext',$menue_context);
		$this->set('ContentId',$contentId);
	}	
	/**
	 * Function related the create View (to create a new Gallery)
	 * @param int $contentId
	 */
	public function create($contentId, $menue_context){

		$pluginId = $this->getPluginId();
		$createAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'create', true);
		

		if (! $this->request->is('post')) {
		$data = array( 'ContentId' => $contentId );
		$pic_array = array();
		$index = 0;
		foreach( $this->GalleryPictureComp->getAllPictures($this) as $picture){
			
			$pic_array[$picture['id']] = $picture['title'];
			$index++;
		}
		
		$this->set('data',$data);
		$this->set('pictures', $pic_array);
		$this->set('mContext',$menue_context);
		} else {
			$this->loadModel('Gallery.GalleryEntry');
			if (!empty($this->request->data)) {
				//check whether title picture isset
				if($this->request->data['GalleryEntry']['gallery_picture_id'] == null){
					
					$this->Session->setFlash(__('Your Gallery was not saved. You have to assign a title picture'), 'default', array('class' => 'flash_failure'));
					$this->redirect(array(	'action' => 'create', $contentId));
				}else {
					//check if parameters are set

					if(!empty($this->request->data['GalleryEntry']['title']) || !empty($this->request->data['GalleryEntry']['description'])){
						if($this->GalleryEntry->save($this->request->data)) {
							
							$this->Session->setFlash(__('Your Gallery was saved.'), 'default', array('class' => 'flash_success'));
							$this->redirect(array('action' => 'index', $contentId));	
						} else {
							$this->Session->setFlash(__('Your Gallery was not saved.'), 'default', array('class' => 'flash_failure'));
							$this->redirect(array(	'action' => 'index', $contentId));		
						}	
					} else {
						$this->Session->setFlash(__('Your Gallery was not saved. You have to assign a title and a description to your gallery.'), 'default', array('class' => 'flash_failure'));
						$this->redirect(array(	'action' => 'create', $contentId));		
					}//check data
				}		
    		}//data empty
		}// is post
	}//function
	
	/**
	 * deletes a gallery
	 * @param int $galleryId
	 * @param int $contentId
	 */
	public function delete($galleryId, $contentId, $menue_context){
	
	
		$pluginId = $this->getPluginId();
		$deleteAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'delete', true);
		
		$picture = $this->Gallery->delete($this,$galleryId);
		$this->set('mContext',$menue_context);
		$this->redirect($this->referer());
	}
	
	/**
	 * deletes a List of Galleries
	 * @param int $contentId
	 */
	public function deleteSelected($contentId, $menue_context){
		$pluginId = $this->getPluginId();
		$deleteAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'delete', true);
		$deleteditems = -1;
		$this->set('mContext',$menue_context);
		if ($this->request->is('post')){
		
			$galleries=  $this->Gallery->getAllGalleries($this);
			if(isset($this->data['selectGalleries'])) {
				$selectedGalleries = $this->data['selectGalleries'];
			
				
				foreach($galleries as $gallery){
					$id = $gallery['GalleryEntry']['id'];
					if ($selectedGalleries[$id] == 1){
						$this->Gallery->delete($this,$id);
						$deleteditems++;
					}
				}
				if(! ($deleteditems <=0)){
					$this->Session->setFlash(__('Deleted sucessfully'), 'default', array('class' => 'flash_success'),'GalleryNotification');
				}	
			} else {
					$this->Session->setFlash(__('Nothing selected.'), 'default', array('class' => 'flash_failure'),'GalleryNotification');
			}
			
			$this->redirect($this->referer());
		}
	
	}//function delete selected
	
	/**
	 * Allows to Edit Galleries, related to Edit view
	 * @param id $galleryId
	 * @param id $contentId
	 */
	public function edit($galleryId,$contentId, $menue_context){
		$pluginId = $this->getPluginId();
		$editAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'edit', true);
		
		if ($this->request->is('post')) {
				if($this->Gallery->save($this,$this->request->data)) {
					$this->Session->setFlash(__('Your changes were saved!'), 'default', array('class' => 'flash_success'));
					//redirect 
					$this->redirect(array('action' => 'index', $contentId));	
				} else {
					$this->Session->setFlash(__('Your Gallery was not saved'), 'default', array('class' => 'flash_failure'));
					$this->redirect(array(	'action' => 'index', $contentId));		
				}
				//$this->redirect($this->referer());
		}
		//if no post data isset
		
		$gallery = $this->Gallery->getGallery($this,$galleryId);
		$this->set('data',$gallery);
		$this->set('ContentId',$contentId);	
		$this->set('mContext',$menue_context);	
	}
	
	/**
	 * Method related to the View for assigning images to a gallery
	 * @param int $galleryId
	 * @param int $contentId
	 */
	public function assignImages($galleryId,$contentId, $menue_context){
			$pluginId = $this->getPluginId();
			$editAllowed = $this->PermissionValidation->actionAllowed($pluginId, 'delete', true);
		
			$this->set('available_pictures',$this->GalleryPictureComp->getAllUnAssignedPictures($this));
			$this->set('gallery_pictures',$this->GalleryPictureComp->getAllPicturesGallery($this, $galleryId));
			$this->set('galleryId', $galleryId);
			$this->set('ContentId',$contentId);
			$this->set('mContext',$menue_context);
		
	}
	
	/**
	 * Assigns an image to the specified Gallery
	 * @param int $galleryId
	 * @param int $pictureId
	 */
	public function assignImage($galleryId,$pictureId, $menue_context){
		$picture = $this->GalleryPictureComp->getPicture($this, $pictureId);
		
		if($picture['gallery_entry_id'] == null){
			$picture['gallery_entry_id'] = $galleryId;
		}else{
			$picture['gallery_entry_id'] = null;
		}

		$this->GalleryPictureComp->save($this,$picture);
		$this->set('mContext',$menue_context);
		$this->redirect($this->referer());
	}
	
	/**
	 * Ressigns an image to the specified Gallery
	 * @param int $galleryId
	 * @param int $pictureId
	 */
	public function unassignImage($galleryId,$pictureId,$menue_context){
		$picture = $this->GalleryPictureComp->getPicture($this, $pictureId);
		
		$picture['gallery_entry_id'] = null;
		
		$this->GalleryPictureComp->save($this,$picture);
		$this->set('mContext',$menue_context);
		$this->redirect($this->referer());
	}

}
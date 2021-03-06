<?php 
	$this->Html->script('/newsblog/js/displayFullNews', false);
	$this->Html->css('/newsblog/css/displayFullNews', null, array('inline' => false));
	$DateTimeHelper = $this->Helpers->load('Time');
	$this->Helpers->load('BBCode');
	$this->Helpers->load('SocialNetwork');
	
	//set meta tags for social network buttons
	//Facebook
	echo $this->Html->meta(null, null, array('property' => 'og:title', 'content' => $data['NewsEntry']['title'], 'inline' => false));
	echo $this->Html->meta(null, null, array('property' => 'og:description', 'content' => substr($this->BBCode->removeBBCode($data['NewsEntry']['text']), 0, 100), 'inline' => false));
	echo $this->Html->meta(null, null, array('property' => 'og:site_name', 'content' => env('SERVER_NAME'), 'inline' => false));
	//Google Plus
	echo $this->Html->meta(null, null, array('itemprop' => 'description', 'content' => substr($this->BBCode->removeBBCode($data['NewsEntry']['text']), 0, 100), 'inline' => false));
	echo $this->Html->meta(null, null, array('itemprop' => 'name', 'content' => $data['NewsEntry']['title'], 'inline' => false));
	
	$this->set('title_for_layout', $data['NewsEntry']['title']);
?>

<div class='showFullNewsContainer'>
	<div class='showFullNewsTitle'>
		<?php echo $data['NewsEntry']['title'];?>
	</div>
	<?php if($data['NewsEntry']['subtitle'] != null & $data['NewsEntry']['subtitle'] != ''){
		echo '<div class="showFullNewsSubtitle">'.$data['NewsEntry']['subtitle'].'</div>';
	}?>
	<div class='showFullNewsInfo'>
		<?php 
			$createdOnDate = $DateTimeHelper->format('m-d-Y', $data['NewsEntry']['createdOn']);
			$createdOnTime = $DateTimeHelper->format('H:i', $data['NewsEntry']['createdOn']);
			if($data['NewsEntry']['lastModifiedOn'] != null){
				$modifiedOnDate = $DateTimeHelper->format('m-d-Y', $data['NewsEntry']['lastModifiedOn']);
				$modifiedOnTime = $DateTimeHelper->format('H:i', $data['NewsEntry']['lastModifiedOn']);
			}
			
			$createdBy = $data['Author'];
		?>
		<?php echo $createdBy;?>
		on
		<?php echo $createdOnDate;?>
		at
		<?php echo $createdOnTime;
			
			if(isset($modifiedOnDate) & isset($modifiedOnTime)){
				echo "&nbsp;&nbsp;(modified on ".$modifiedOnDate." at ".$modifiedOnTime.")";
			}
		?>
	</div>
	<div class='showFullNewsBody'>
		<?php echo $this->BBCode->transformBBCode($data['NewsEntry']['text']);?>
	</div>
	<div class='showFullNewsSocial'>
		<?php
			//Facebook
			echo $this->SocialNetwork->insertFacebookShare();
			//Google+
			echo $this->SocialNetwork->insertGoogleShare();
			//Twitter
			echo $this->SocialNetwork->insertTwitterShare();
		?>
	</div>
	<div class='showFullNewsOptions'>
	
	</div>
</div>
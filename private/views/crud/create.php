<?php
$this->breadcrumbs=array(
	$this->pluralName=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ' . $this->pluralName, 'url'=>array('index')),
	array('label'=>'Manage ' . $this->pluralName, 'url'=>array('admin')),
);
?>

<h1>Create <?php echo $this->singularName; ?></h1>

<?php echo $this->renderPartial('/crud/_form', array('model'=>$model)); ?>
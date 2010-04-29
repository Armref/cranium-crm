<?php
$this->breadcrumbs=array(
	$this->pluralName=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ' . $this->pluralName, 'url'=>array('index')),
	array('label'=>'Create ' . $this->singularName, 'url'=>array('create')),
	array('label'=>'View ' . $this->singularName, 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ' . $this->pluralName, 'url'=>array('admin')),
);
?>

<h1>Update <?php echo $this->singularName; ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('/crud/_form', array('model'=>$model)); ?>
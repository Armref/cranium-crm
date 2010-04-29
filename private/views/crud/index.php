<?php
$this->breadcrumbs=array(
	$this->pluralName,
);

$this->menu=array(
	array('label'=>'Create ' . $this->singularName, 'url'=>array('create')),
	array('label'=>'Manage ' . $this->pluralName, 'url'=>array('admin')),
);
?>

<h1><?php echo $this->pluralName; ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'/crud/_view',
)); ?>
<?php
$this->breadcrumbs=array(
	$this->pluralName=>array('index'),
	$model->displayLabel(),
);

$this->menu=array(
	array('label'=>'List ' . $this->pluralName, 'url'=>array('index')),
	array('label'=>'Create ' . $this->singularName, 'url'=>array('create')),
	array('label'=>'Update ' . $this->singularName, 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ' . $this->singularName, 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ' . $this->pluralName, 'url'=>array('admin')),
);
?>

<h1>View <?php echo $this->singularName; ?> #<?php echo $model->id; ?></h1>

<?php
$columns = array();
foreach($this->modelUtil()->columns AS $column=>$colAttr) {
	$columns[] = $column;
}

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>$columns,
));
?>
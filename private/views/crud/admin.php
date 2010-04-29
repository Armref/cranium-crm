<?php
$this->breadcrumbs=array(
	$this->pluralName=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ' . $this->pluralName, 'url'=>array('index')),
	array('label'=>'Create ' . $this->singularName, 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('" . $this->id . "-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage <?php echo $this->pluralName; ?></h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('/crud/_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$columns = array();
foreach($this->modelUtil()->columns AS $column=>$colAttr) {
	$columns[] = $column;
}
$columns[] = array('class'=>'CButtonColumn');

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>$this->id . '-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>$columns,
));
?>
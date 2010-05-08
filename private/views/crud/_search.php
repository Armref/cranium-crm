<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php
	foreach($model::model()->columns AS $column)
	{
		?>
	<div class="row">
		<?php echo $this->generateField($model, $column, $form, 'search'); ?>
	</div>
		<?php
	}
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
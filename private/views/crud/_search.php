<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php
	foreach($model::model()->columns AS $column)
	{
		$embedded = false;
		$row = $this->generateField($model, $column, $form, 'search', $embedded);

		if(empty($embedded))
		{
		?>
	<div class="row">
		<?php echo $row; ?>
	</div>
		<?php
		}else
		{
			echo $row;
		}
	}
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
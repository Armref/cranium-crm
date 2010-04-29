<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'account-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
	foreach($this->modelUtil()->columns AS $column)
	{
		if($column->isPrimaryKey)
		{
			continue;
		}
		?>
	<div class="row">
		<?php echo $this->generateField($model, $column, $form); ?>
	</div>
		<?php
	}
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>$this->id . '-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $this->renderPartial('/crud/_formElements', array('model'=>$model, 'form'=>$form)); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
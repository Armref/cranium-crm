<?php
/**
 * @todo Add support for generating elements w/out $form variable
 */
foreach($model::model()->columns AS $column)
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
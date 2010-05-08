<div class="view">

	<?php
	$model = $data;
	$pkLabel = array();
	$pkValue = array();
	$pkRoute = array('view');
	if(is_array($model::model()->primaryKeyCol))
	{
		foreach($model::model()->primaryKeyCol AS $pk)
		{
			$pkLabel[] = $data->getAttributeLabel($pk);
			$pkValue[] = $data->$pk;
			$pkRoute[$pk] = $data->$pk;
		}
	}else{
		$pk = $model::model()->primaryKeyCol;
		$pkLabel[] = $data->getAttributeLabel($pk);
		$pkValue[] = $data->$pk;
		$pkRoute[$model::model()->primaryKeyCol] = $data->$pk;
	}
	$pkLabel = implode('/', $pkLabel);
	$pkValue = implode('/', $pkValue);
	?>
	<b><?php echo CHtml::encode($pkLabel); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($pkValue), $pkRoute); ?>
	<br />
	<?php
	?>

	<?php
	foreach($model::model()->columns AS $column)
	{
		if($column->isPrimaryKey)
		{
			continue;
		}
		?>
	<b><?php echo CHtml::encode($data->getAttributeLabel($column->name)); ?>:</b>
	<?php echo CHtml::encode($data->{$column->name}); ?>
	<br />
		<?php
	}
	?>

</div>
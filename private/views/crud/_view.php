<div class="view">

	<?php
	$pkLabel = array();
	$pkValue = array();
	$pkRoute = array('view');
	if(is_array($this->modelUtil()->primaryKeyCol))
	{
		foreach($this->modelUtil()->primaryKeyCol AS $pk)
		{
			$pkLabel[] = $data->getAttributeLabel($pk);
			$pkValue[] = $data->$pk;
			$pkRoute[$pk] = $data->$pk;
		}
	}else{
		$pk = $this->modelUtil()->primaryKeyCol;
		$pkLabel[] = $data->getAttributeLabel($pk);
		$pkValue[] = $data->$pk;
		$pkRoute[$this->modelUtil()->primaryKeyCol] = $data->$pk;
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
	foreach($this->modelUtil()->columns AS $column)
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
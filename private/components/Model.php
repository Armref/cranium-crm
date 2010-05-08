<?php
/**
 * Extending CActiveRecord to add common functionality to all models
 * @todo Clean up get* functions, so they don't interfere with model objects
 */
class Model extends CActiveRecord
{
	public $modelDisplayField;
	private $_oldAttributes = array();

	public function displayFields()
	{
		return array();
	}

	public function getForeignKeys()
	{
		return $this->tableSchema->foreignKeys;
	}

	public function getColumns()
	{
		return $this->tableSchema->columns;
	}

	public function getPrimaryKeyCol()
	{
		return $this->tableSchema->primaryKey;
	}

	public function formRelations()
	{
		return array();
	}

	public function displayLabel()
	{
		$displayCols = $this->displayColumns();

		$return = array();
		foreach($displayCols AS $column)
		{
			$return[] = $this->$column;
		}

		return implode(' ', $return);
	}

	public function displayColumns($fallback = 'id')
	{
		$displayFields = $this->displayFields();
		if(!empty($displayFields))
		{
			return $this->displayFields();
		}
		
		foreach($this->columns as $column)
		{
			if(stripos($column->name,'name')!==false)
				return array($column->name);
		}
		foreach($this->columns as $column)
		{
			if(stripos($column->name,'title')!==false)
				return array($column->name);
		}
		foreach($this->columns as $column)
		{
			if($column->type === 'string')
			{
				if(!in_array($this->determineRealDbType($column), array('integer','double')))
					return array($column->name);
			}
		}
		foreach($this->columns as $column)
		{
			if($column->isPrimaryKey)
				return array($column->name);
		}
		return array($fallback);
	}

	/**
	 * This is MySQL specific
	 *
	 * @todo Make compatible w/ other DB types
	 * @param object $column
	 */
	public static function determineRealDbType($column)
	{
		$dbType = $column->dbType;
		if(strncmp($dbType,'enum',4)===0)
			$type='string';
		else if(strpos($dbType,'bigint')!==false || strpos($dbType,'float')!==false || strpos($dbType,'double')!==false)
			$type='double';
		else if(strpos($dbType,'bool')!==false)
			$type='boolean';
		else if(strpos($dbType,'int')!==false || strpos($dbType,'bit')!==false)
			$type='integer';
		else
			$type='string';

		return $type;
	}

	/**
	 * $model->behaviors
	 *
	 * Defines behaviors that Yii should apply to the ActiveRecord class
	 *
	 * @todo Place IF statements around each behavior, to ensure needed dependencies (columns) exist before loading
	 * @todo Maybe replace CTimestampBehavior with smarter behavior
	 * @return array
	 */
	public function behaviors()
	{
		$return = array(
			'SystemLogBehavior'=>array(
				'class'=>'application.components.behaviors.SystemLogBehavior',
			),
		);

		$columns = array_keys($this->getColumns());
		if(in_array('created', $columns) && in_array('modified', $columns))
		{
			$return['CTimestampBehavior'] = array(
				'class'=>'zii.behaviors.CTimestampBehavior',
				'createAttribute'=>'created',
				'updateAttribute'=>'modified',
				'setUpdateOnCreate'=>true,
			);
		}

		return $return;
	}

	public function safeAttributes()
	{
		return array('created','modified','lft','rgt');
	}

	public function getSafeAttributeNames()
	{
		return array_unique(array_merge(parent::getSafeAttributeNames(), $this->safeAttributes()));
	}

	protected function afterFind()
	{
		// Save old values
		$this->setOldAttributes($this->getOwner()->getAttributes());

		return parent::afterFind();
	}

	public function getOldAttributes()
	{
		return $this->_oldAttributes;
	}

	public function setOldAttributes($value)
	{
		$this->_oldAttributes = $value;
	}

	/**
	 * Returns list of elements that should not be editable
	 *
	 * @return array
	 */
	public function lockedElements()
	{
		return array(
			'lft'=>array(
				'display'=>false,
			),
			'rgt'=>array(
				'display'=>false,
			),
			'created'=>array(
				'display'=>true,
			),
			'modified'=>array(
				'display'=>true,
			),
		);
	}
}
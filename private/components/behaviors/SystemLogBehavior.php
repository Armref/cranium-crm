<?php

/**
 * @todo Add support for composite primary key
 * @todo Replace hard-coded "web" source w/ accurate source
 */
class SystemLogBehavior extends CActiveRecordBehavior
{
	public $systemLog = 'SystemLog';
	public $systemLogChange = 'SystemLogChange';
	public $systemModel = 'SystemModel';
	public $systemController = 'SystemController';
	private $_oldAttributes = array();
	private $_model;
	private $_controller;

	private function _notSelf($modelName=null)
	{
		if(empty($this->model))
		{
			$modelName = get_class($this->getOwner());
		}

		return !in_array($modelName, array($this->logModel,$this->logChangeModel));
	}

	public function getModel()
	{
		if(empty($this->_model))
		{
			$this->_model = get_class($this->getOwner());
		}

		return $this->_model;
	}

	public function getController()
	{
		if(empty($this->_controller))
		{
			$this->_controller = get_class(Yii::app()->controller);
		}

		return $this->_controller;
	}

	public function afterSave($event)
	{
		$modelKey = $this->getOwner()->getPrimaryKey();

		if(!is_array($modelKey) && $this->_notSelf())
		{
			$newAttributes = $this->getOwner()->getAttributes();
			$oldAttributes = $this->getOldAttributes();

			$diffAttributes = array_diff_assoc($newAttributes, $oldAttributes);

			$systemController = $this->systemController;
			$systemModel = $this->systemModel;

			$log = new $this->logModel;
			$log->controller_id = $systemController::model()->findByAttributes(array('controller'=>$this->controller))->getPrimaryKey();
			$log->model_id = $systemModel::model()->findByAttributes(array('model'=>$this->model))->getPrimaryKey();
			$log->user_id = Yii::app()->user->id;
			$log->model_pk_id = $modelKey;
			$log->event = ($this->getOwner()->isNewRecord ? 'create' : 'update');
			$log->source = 'web';
			$log->save();

			if(!empty($diffAttributes))
			{
				foreach($diffAttributes AS $k=>$v)
				{
					$change = new $this->logModelChange;
					$change->action_log_id = $log->id;
					$change->field_name = $k;
					$change->field_value = $v;
					$change->save();
				}
			}
		}
	}

	public function afterDelete($event)
	{
		$modelKey = $this->getOwner()->getPrimaryKey();

		if(!is_array($modelKey) && $this->_notSelf())
		{
			$systemController = $this->systemController;
			$systemModel = $this->systemModel;

			$log = new $this->logModel;
			$log->controller_id = $systemController::model()->findByAttributes(array('controller'=>$this->controller))->getPrimaryKey();
			$log->model_id = $systemModel::model()->findByAttributes(array('model'=>$this->model))->getPrimaryKey();
			$log->user_id = Yii::app()->user->id;
			$log->model_pk_id = $modelKey;
			$log->event = 'delete';
			$log->source = 'web';
			$log->save();
		}
	}

	public function afterFind($event)
	{
		// Save old values
		$this->setOldAttributes($this->getOwner()->getAttributes());
	}

	public function getOldAttributes()
	{
		return $this->_oldAttributes;
	}

	public function setOldAttributes($value)
	{
		$this->_oldAttributes = $value;
	}
}
<?php

class CrudController extends Controller
{
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	public $relatedModels;
	private $_persistentModels = array();

	public function init()
	{
		parent::init();
		if(empty($this->modelName))
		{
			throw new CHttpException(404,'The model for this action does not exist.');
		}
		$this->relatedModels();
	}

	public function modelUtil()
	{
		$_model = $this->modelName;
		return $_model::model();
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @todo Secure the "list" action so that it only works for ajax requests
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','list'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				#'users'=>array('admin'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$this->render('/crud/view',array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = $this->loadPersistentModel($this->modelName);
		$this->performAjaxValidation($model);

		if(isset($_POST[$this->modelName]))
		{
			$model->attributes=$_POST[$this->modelName];

			$transaction=$model->dbConnection->beginTransaction();
			try
			{
				$this->saveModel($model);

				$transaction->commit();
				$this->redirect(array('view',$this->modelUtil()->primaryKeyCol => $model->primaryKey));
			}
			catch(Exception $e)
			{
				$transaction->rollBack();
				echo 'error transaction';
				print_r($e);
			}
		}

		$this->render('/crud/create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @todo modify to work similar to Create method
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		if(isset($_POST[$this->modelName]))
		{
			$model->attributes=$_POST[$this->modelName];

			$transaction=$model->dbConnection->beginTransaction();
			try
			{
				if($model->save())
				{
					$transaction->commit();
					$this->redirect(array('view',$this->modelUtil()->primaryKeyCol => $model->primaryKey));
				}
			}
			catch(Exception $e)
			{
				$transaction->rollBack();
				echo 'error transaction';
			}
		}

		$this->render('/crud/update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_POST['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider($this->modelName, array(
				'pagination'=>array(
					'pageSize'=>$this->pageSize,
				),
		));

		$this->render('/crud/index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new $this->modelName('search');
		if(isset($_GET[$this->modelName]))
			$model->attributes=$_GET[$this->modelName];

		$this->render('/crud/admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Add support for multiple PKs
	 * @todo add security method to avoid listing passwords, for instance
	 * @todo implement extension for filtering, found here: http://www.yiiframework.com/extension/jquery-cascade/
	 */
	public function actionList()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$objects = $this->modelUtil()->findAllByAttributes($_GET);
			$return = array();

			foreach($objects as $object)
			{
				$return[] = array('label' => $object->displayLabel(), 'value' => $object->primaryKey);
			}

			echo json_encode($return);
			Yii::app()->end();
		}else
		{
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			$pk = $this->modelUtil()->primaryKeyCol;
			if(isset($_GET[$pk]))
				$this->_model=$this->modelUtil()->findbyPk($_GET[$pk]);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	public function loadPersistentModel($modelName)
	{
		if(!in_array($modelName, $this->_persistentModels))
		{
			$this->_persistentModels[$modelName] = new $modelName;
		}

		return $this->_persistentModels[$modelName];
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		$formName = $this->modelName . '-form';
		if(isset($_POST['ajax']) && $_POST['ajax']===$formName)
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * @todo Remove if possible, once form generation is restructured
	 */
	public function relatedModels()
	{
		if(!isset($this->relatedModels))
		{
			$this->relatedModels = array();
			$relations = self::modelRelations($this->modelName);
			foreach($relations AS $relKey=>$relArray)
			{
				if(!empty($relArray['embed']))
				{
					$this->relatedModels[$relArray[1]] = array('model'=>null, 'fKey'=>$relArray[2], 'relKey'=>$relKey, 'joinType'=>$relArray[0]);
				}
			}
		}

		return $this->relatedModels;
	}

	public static function modelRelations($modelName)
	{
		return array_merge_recursive($modelName::model()->relations(), $modelName::model()->formRelations());
	}

	/**
	 * @todo Ensure models are able to persist for validation & data purposes
	 */
	public function saveModel(&$model, &$relationships = array(), &$valid = true)
	{
		$hasChildren = $successful = false;
		$modelName = get_class($model);
		$modelRelations = self::modelRelations($modelName);
		if(!empty($modelRelations))
		{
			$modelFKeys = $modelName::model()->foreignKeys;
			foreach($modelRelations AS $relKey=>$relArray)
			{
				$joinType = $relArray[0];
				$subModelName = $relArray[1];
				$modelFKey = $relArray[2];

				/**
				 * Track "relationships" to ensure no endless loops
				 */
				$tmp = array($modelName, $subModelName);
				sort($tmp);
				if(!empty($_POST[$subModelName]) && !in_array($tmp, $relationships))
				{
					switch($joinType)
					{
						case $modelName::BELONGS_TO:
							array_push($relationships, $tmp);

							$subModel = $this->loadPersistentModel($subModelName);
							$subModel->attributes = $_POST[$subModelName];
							if($this->saveModel($subModel, $relationships, $valid))
							{
								$subModelFKey = $modelFKeys[$modelFKey][1];
								$model->{$modelFKey} = $subModel->{$subModelFKey};
							}
							break;
						case $modelName::HAS_ONE:
						case $modelName::HAS_MANY:
						case $modelName::MANY_MANY:
							$hasChildren = true;
							continue;
							break;
					}
				}
			}
		}
		$valid = $model->validate() && $valid;

		if($valid)
		{
			$successful = $model->save(false);

			if($successful && $hasChildren)
			{
				foreach($modelRelations AS $relKey=>$relArray)
				{
					$joinType = $relArray[0];
					$subModelName = $relArray[1];
					$modelFKey = $relArray[2];

					/**
					 * Track "relationships" to ensure no endless loops
					 */
					$tmp = array($modelName, $subModelName);
					sort($tmp);
					if(!empty($_POST[$subModelName]) && !in_array($tmp, $relationships))
					{
						switch($joinType)
						{
							case $modelName::BELONGS_TO:
								continue;
								break;
							case $modelName::HAS_ONE:
							case $modelName::HAS_MANY:
								array_push($relationships, $tmp);

								$subModel = $this->loadPersistentModel($subModelName);
								$subModel->attributes = $_POST[$subModelName];
								$subModel->{$subModelFKey} = $model->{$modelFKey};
								$this->saveModel($subModel, $relationships, $valid);
								break;
							case $modelName::MANY_MANY:
								array_push($relationships, $tmp);

								$subModel = $this->loadPersistentModel($subModelName);
								$subModel->attributes = $_POST[$subModelName];
								if($this->saveModel($subModel, $relationships, $valid))
								{
									if(preg_match('/^(\w+)\((\w+),(\w+)\)$/', $modelFKey, $match))
									{
										$tableName = $match[1];
										$modelFKey = $match[2];
										$subModelFKey = $match[3];

										if($valid)
										{
											Yii::app()->db->commandBuilder->createInsertCommand($tableName,
												array(
												$modelFKey=>$model->$modelFKey,
												$subModelFKey=>$subModel->$subModelFKey
												)
												)->query();
										}
									}
									// else toss error
								}
								break;
						}
					}
				}
			}
		}

		return $successful;
	}

	public static function renderField($type, $model, $columnName, $form=null, $err=true, $label=true, $attributes=null, $data=null)
	{
		$return = '';
		if($label)
		{
			$return .= self::generateFieldLabel($model, $columnName, $form);
		}
		$params = array($model, $columnName);

		switch($type)
		{
			case 'dropDownList':
				array_push($params, $data);
				break;
			case 'textArea':
				if(empty($attributes))
				{
					$attributes = array('rows'=>6, 'cols'=>50);
				}
				array_push($params, $attributes);
				break;
			case 'checkBox':
			case 'passwordField':
			case 'textField':
			case 'hiddenField':
				break;
		}

		$classRef = !empty($form) ? $form : 'CHtml';
		$type = !empty($form) ? $type : 'active' . ucfirst($type);
		$return .= call_user_func_array(array($classRef, $type), $params);

		if(!empty($err))
		{
			$return .= self::generateFieldError($model, $columnName, $form);
		}

		return $return;
	}

	public static function generateFieldLabel($model, $columnName, $form=null)
	{
		if(!empty($form))
		{
			return $form->label($model, $columnName);
		}else
		{
			return CHtml::activeLabel($model, $columnName);
		}
	}

	public static function generateFieldError($model, $columnName, $form=null)
	{
		if(!empty($form))
		{
			return $form->error($model, $columnName);
		}else
		{
			return CHtml::activeError($model, $columnName);
		}
	}

	public function buildForm(&$model, $form = null, $displayField = null, $skipPrimary = true)
	{
		if(empty($displayField))
		{
			$displayField = function($content)
				{
					return $content;
				};
		}

		$hasChildren = $successful = false;
		$modelName = get_class($model);
		$modelRelations = self::modelRelations($modelName);
		if(!empty($modelRelations))
		{
			$modelFKeys = $modelName::model()->foreignKeys;
		}

		$output = '';
		foreach($model::model()->columns AS $column)
		{
			if($column->isPrimaryKey)
			{
				continue;
			}

			$embedded = false;
			$_output = $this->generateField($model, $column, $form, null, $embedded);

			/**
			 * @todo check for column as foreign key, if so, render dropdown or subform
			 * @todo add loop after columns to render other subforms
			 */
			if(!empty($embedded))
			{
				$output .= $_output;
			}else
			{
				$output .= $displayField($_output);
			}
		}

		return $output;
	}

	public function detailColumns($model, $relation = null)
	{
		return $this->viewColumns('detail', $model, $relation);
	}

	public function gridColumns($model, $relation = null)
	{
		return $this->viewColumns('grid', $model, $relation);
	}

	public function viewColumns($type='grid', $model, $relation = null)
	{
		$columns = array();
		foreach($model->getColumns() AS $column)
		{
			/**
			 * @todo Implement column display control by model
			 */
			if(preg_match('/password/i', $column->name))
			{
				continue;
			}

			/**
			 * If is a nested model
			 */
			if(!empty($relation))
			{
				if($column->isPrimaryKey || array_key_exists($column->name, $model->lockedElements()))
				{
					continue;
				}
			}

			$nestedColumn = true;
			if($column->isForeignKey)
			{
				$relations = self::modelRelations($model->modelId);
				foreach($relations AS $relKey=>$relArray)
				{
					if(strcmp($relArray[2], $column->name)==0)
					{
						$relatedModel = $relArray[1];
						if(!empty($relatedModel))
						{
							$nestedColumn = false;
							if(empty($relArray['embed']))
							{
								$_col = array(
									'name'=>$relKey,
								);

								switch($type)
								{
									case 'detail':
										$_col['value'] = $model->{$relKey}->displayLabel();
										break;
									case 'grid':
										$_col['value'] = '$data->' . $relKey . '->displayLabel()';
										break;
								}

								$columns[] = $_col;
							}else
							{
								$columns = array_merge($columns, $this->viewColumns($type, $relatedModel::model(), $relKey));
							}
						}
						break;
					}
				}
			}

			if(!empty($nestedColumn))
			{
				$columns[] = (!empty($relation) ? $relation . '.' : '') . $column->name;
			}
		}

		return $columns;
	}

	/**
	 * @todo Make this method MUCH MUCH MUCH more elegant
	 * @todo Restructure to be similar to saveModel method, add support for all relationship types
	 */
	public function generateField($model, $column, $form = null, $type = 'form', &$embedded = false)
	{
		$error = true;
		if($type == 'search')
		{
			$error = false;
		}

		$locked = $model->lockedElements();
		if(array_key_exists($column->name, $locked))
		{
			if(!empty($locked[$column->name]['display']) && isset($model->{$column->name}) && !is_null($model->{$column->name}))
			{
				return self::generateFieldLabel($model, $column->name, $form) . ': ' . $model->{$column->name} . self::generateFieldError($model, $column->name, $form);
			}else
			{
				return self::renderField('hiddenField', $model, $column->name, $form, $error, false);
			}
		}

		if($column->isForeignKey)
		{
			/**
			 * ForeignKey stuff here
			 */
			$embedForm = false;
			$relatedModel = '';
			$relations = self::modelRelations($model->modelId);
			foreach($relations AS $relKey=>$relArray)
			{
				if(strcmp($relArray[2], $column->name)==0)
				{
					$relatedModel = $relArray[1];
					$embedForm = isset($relArray['embed']) ? $relArray['embed'] : false;
					break;
				}
			}

			if(empty($embedForm))
			{
				$listData = array();
				if(!empty($relatedModel))
				{
					$foreignColumns = $relatedModel::model()->getColumns();
					$fkId = $model->foreignKeys[$column->name][1];
					$fkName = 'CONCAT(`' . implode('`, " ", `', $relatedModel::model()->displayColumns($fkId)) . '`) AS modelDisplayField';
					$cond = array('select'=>array($fkId, $fkName));
					$listData = CHtml::listData($relatedModel::model()->findAll($cond), $fkId, 'modelDisplayField');
				}

				return self::renderField('dropDownList', $model, $column->name, $form, $error, true, null, $listData);
			}else
			{
				$embedded = true;

				/**
				 * Build sub-form
				 */
				return $this->renderPartial('/crud/_formElements', array('model'=>$relatedModel::model(), 'form'=>$form));
			}
		}else
		{
			if($column->type==='boolean' || ($column->type == 'integer' && $column->size == 1))
			{
				return self::renderField('checkBox', $model, $column->name, $form, $error, true);
			}
			else if(stripos($column->dbType,'text')!==false)
			{
				return self::renderField('textArea', $model, $column->name, $form, $error, true);
			}
			else
			{
				if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
				{
					$inputField='passwordField';
					$model->{$column->name} = '';
				}else
				{
					$inputField='textField';
				}

				if($column->type!=='string' || $column->size===null)
				{
					return self::renderField($inputField, $model, $column->name, $form, $error, true);
				}
				else
				{
					switch($column->dbType)
					{
						case 'date':
						case 'timestamp':
						case 'datetime':
						case 'time':
							break;
						case 'year':
							$listData = range(date('Y'), 1900);
							$listData = array_combine($listData, $listData);

							return self::renderField('dropDownList', $model, $column->name, $form, $error, true, null, $listData);

							break;
						default:
							if(preg_match('/^enum\((.+)\)$/', $column->dbType, $match))
							{
								$listData = str_getcsv($match[1], ',', "'");
								$listData = array_combine($listData, $listData);

								return self::renderField('dropDownList', $model, $column->name, $form, $error, true, null, $listData);
							}
							break;
					}

					if(($size=$maxLength=$column->size)>60)
						$size=60;
					return self::renderField($inputField, $model, $column->name, $form, $error, true, array('size'=>$size,'maxlength'=>$maxLength));
				}
			}
		}
	}
}
<?php

class CrudController extends Controller
{
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public function init()
	{
		parent::init();
		if(empty($this->modelName))
		{
			throw new CHttpException(404,'The model for this action does not exist.');
		}#var_dump($this->modelUtil()->getForeignKeys());exit;
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
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
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
		$model=new $this->modelName();
		$this->performAjaxValidation($model);

		if(isset($_POST[$this->modelName]))
		{
			$model->attributes=$_POST[$this->modelName];

			$transaction=$model->dbConnection->beginTransaction();
			try
			{
				if($model->save())
				{
					$transaction->commit();
					$this->redirect(array('view','id'=>$model->id));
				}
			}
			catch(Exception $e)
			{
				$transaction->rollBack();
				echo 'error transaction';
				print_r($e);exit;
			}
		}

		$this->render('/crud/create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
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
					$this->redirect(array('view','id'=>$model->id));
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=$this->modelUtil()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
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
	 * @todo Make this method MUCH MUCH MUCH more elegant
	 */
	public function generateField($model, $column, $form = null, $type = 'form')
	{
		$label = '';
		$error = '';
		switch($type)
		{
			case 'form':
				if(!empty($form))
				{
					$label = $form->labelEx($model, $column->name);
					$error = $form->error($model, $column->name);
				}else
				{
					$label = CHtml::activeLabelEx($model, $column->name);
					$error = CHtml::activeError($model, $column->name);
				}
				break;
			case 'search':
				if(!empty($form))
				{
					$label = $form->label($model, $column->name);
				}else
				{
					$label = CHtml::activeLabel($model, $column->name);
				}
				break;
		}

		$locked = $model->lockedElements();

		if(array_key_exists($column->name, $locked))
		{
			if(!empty($locked[$column->name]['display']) && isset($model->{$column->name}) && !is_null($model->{$column->name}))
			{
				return $label . ': ' . $model->{$column->name} . $error;
			}else
			{
				if(!empty($form))
				{
					return $form->hiddenField($model, $column->name);
				}else
				{
					return CHtml::activeHiddenField($model, $column->name);
				}
			}
		}

		if($column->isForeignKey)
		{
			/**
			 * ForeignKey stuff here
			 */
			$foreignModel = '';
			foreach($model->relations() AS $relKey=>$relArray)
			{
				if(strcmp($relArray[2], $column->name)==0)
				{
					$foreignModel = $relArray[1];
					break;
				}
			}
			$listData = array();
			if(!empty($foreignModel))
			{
				$foreignColumns = $foreignModel::model()->getColumns();
				$fkId = $model->foreignKeys[$column->name][1];
				$fkName = 'CONCAT(`' . implode('`, " ", `', $foreignModel::model()->displayColumns($fkId)) . '`) AS modelDisplayField';
				$cond = array('select'=>array($fkId, $fkName));
				$listData = CHtml::listData($foreignModel::model()->findAll($cond), $fkId, 'modelDisplayField');
			}

			if(!empty($form))
			{
				return $label . $form->dropDownList($model, $column->name, $listData) . $error;
			}else
			{
				return $label . CHtml::activeDropDownList($model, $column->name, $listData) . $error;
			}
		}else
		{
			if($column->type==='boolean' || ($column->type == 'integer' && $column->size == 1))
			{
				if(!empty($form))
				{
					return $label . $form->checkBox($model, $column->name) . $error;
				}else
				{
					return $label . CHtml::activeCheckBox($model, $column->name) . $error;
				}
			}
			else if(stripos($column->dbType,'text')!==false)
			{
				if(!empty($form))
				{
					return $label . $form->textArea($model, $column->name, array('rows'=>6, 'cols'=>50)) . $error;
				}else
				{
					return $label . CHtml::activeTextArea($model, $column->name, array('rows'=>6, 'cols'=>50)) . $error;
				}
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
					if(!empty($form))
					{
						return $label . $form->{$inputField}($model, $column->name) . $error;
					}else
					{
						$inputField = 'active' . ucfirst($inputField);
						return $label . CHtml::$inputField($model, $column->name) . $error;
					}
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

							if(!empty($form))
							{
								return $label . $form->dropDownList($model, $column->name, $listData) . $error;
							}else
							{
								return $label . CHtml::activeDropDownList($model, $column->name, $listData) . $error;
							}

							break;
						default:
							if(preg_match('/^enum\((.+)\)$/', $column->dbType, $match))
							{
								$listData = str_getcsv($match[1], ',', "'");
								$listData = array_combine($listData, $listData);

								if(!empty($form))
								{
									return $label . $form->dropDownList($model, $column->name, $listData) . $error;
								}else
								{
									return $label . CHtml::activeDropDownList($model, $column->name, $listData) . $error;
								}
							}
							break;
					}

					if(($size=$maxLength=$column->size)>60)
						$size=60;
					if(!empty($form))
					{
						return $label . $form->{$inputField}($model, $column->name, array('size'=>$size,'maxlength'=>$maxLength)) . $error;
					}else
					{
						$inputField = 'active' . ucfirst($inputField);
						return $label . CHtml::$inputField($model, $column->name, array('size'=>$size,'maxlength'=>$maxLength)) . $error;
					}
				}
			}
		}
	}
}
<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'application.views.layouts.column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='application.views.layouts.column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	
	/**
	 * @var string Name of the CActiveRecord model currently in use
	 */
	private $_modelName;
	
	/**
	 * @var string String of the controller currently in use
	 */
	private $_controllerName;
	
	/**
	 * @var integer Alternative to using PAGESIZE const, as constants don't extend
	 */
	protected $_pageSize;
	
	public function init()
	{
		Yii::import('application.vendors.cake_libs.*');

		$this->_controllerName = get_class($this);
		if($this->issetClassConst('PAGESIZE'))
		{
			$this->_pageSize = $this->getClassConst('PAGESIZE');
		}
		
		$modelName = preg_replace('/Controller$/', '', $this->_controllerName);
		$modelFile = Yii::getPathOfAlias('application.models.'.$modelName).'.php';
		if(!empty($modelName) && file_exists($modelFile) && class_exists($modelName))
		{
			$this->_modelName = $modelName;
		}
	}
	
	/**
	 * Detects if a specified constant is set for the specified class
	 */
	public function issetClassConst($const, $class=null)
	{
		if(!isset($class))
		{
			$class = $this->getControllerName();
		}
		$constant = sprintf('%s::%s', $class, $const);
		return defined($constant);
	}
	
	/**
	 * Returns the value of a specified constant for a specified class
	 */
	public function getClassConst($const, $class=null)
	{
		if(!isset($class))
		{
			$class = $this->controllerName;
		}
		$constant = sprintf('%s::%s', $class, $const);
		if(defined($constant))
		{
			return constant($constant);
		}
	}
	
	/**
	 * Returns the pagesize var
	 */
	public function getPageSize()
	{
		return $this->_pageSize;
	}
	
	/**
	 * Returns the current controller name
	 */
	public function getControllerName()
	{
		return $this->_controllerName ?: false;
	}
	
	/**
	 * Returns the current model name
	 */
	public function getModelName()
	{
		return $this->_modelName ?: false;
	}

	public function getSingularName()
	{
		return $this->singularize(ucwords($this->filenameToWords($this->id)));
	}

	public function getPluralName()
	{
		return $this->pluralize(ucwords($this->filenameToWords($this->id)));
	}

	public function filenameToArray($string)
	{
		$string = str_replace('Controller.php', '', $string);
		$string = str_replace('.php', '', $string);
		return preg_split('/(?<=[a-z])(?=[A-Z])/', $string);
	}

	public function filenameToWords($string)
	{
		return implode(' ', $this->filenameToArray($string));
	}

	public function pluralize($string) {
		return Inflector::pluralize($string);
	}

	public function singularize($string) {
		return Inflector::singularize($string);
	}
}
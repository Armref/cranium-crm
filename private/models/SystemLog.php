<?php

/**
 * This is the model class for table "system_log".
 */
class SystemLog extends Model
{
	/**
	 * The followings are the available columns in table 'system_log':
	 * @var string $id
	 * @var string $controller_id
	 * @var string $model_id
	 * @var string $user_id
	 * @var string $model_pk_id
	 * @var string $event
	 * @var string $source
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return SystemLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'system_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('controller_id, model_id, user_id, model_pk_id, event, source', 'required'),
			array('controller_id, model_id, user_id, model_pk_id', 'length', 'max'=>20),
			array('event', 'length', 'max'=>6),
			array('source', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, controller_id, model_id, user_id, model_pk_id, event, source, created, modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'model' => array(self::BELONGS_TO, 'SystemModel', 'model_id'),
			'controller' => array(self::BELONGS_TO, 'SystemController', 'controller_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'systemLogChanges' => array(self::HAS_MANY, 'SystemLogChange', 'system_log_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'controller_id' => 'Controller',
			'model_id' => 'Model',
			'user_id' => 'User',
			'model_pk_id' => 'Model Pk',
			'event' => 'Event',
			'source' => 'Source',
			'created' => 'Created',
			'modified' => 'Modified',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('controller_id',$this->controller_id,true);

		$criteria->compare('model_id',$this->model_id,true);

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('model_pk_id',$this->model_pk_id,true);

		$criteria->compare('event',$this->event,true);

		$criteria->compare('source',$this->source,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
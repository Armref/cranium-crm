<?php

/**
 * This is the model class for table "system_log_change".
 */
class SystemLogChange extends Model
{
	/**
	 * The followings are the available columns in table 'system_log_change':
	 * @var string $id
	 * @var string $system_log_id
	 * @var string $column
	 * @var string $value
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return SystemLogChange the static model class
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
		return 'system_log_change';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('system_log_id, column', 'required'),
			array('system_log_id', 'length', 'max'=>20),
			array('column', 'length', 'max'=>64),
			array('value', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, system_log_id, column, value', 'safe', 'on'=>'search'),
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
			'systemLog' => array(self::BELONGS_TO, 'SystemLog', 'system_log_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'system_log_id' => 'System Log',
			'column' => 'Column',
			'value' => 'Value',
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

		$criteria->compare('system_log_id',$this->system_log_id,true);

		$criteria->compare('column',$this->column,true);

		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
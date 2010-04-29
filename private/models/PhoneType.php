<?php

/**
 * This is the model class for table "phone_type".
 */
class PhoneType extends Model
{
	/**
	 * The followings are the available columns in table 'phone_type':
	 * @var string $id
	 * @var string $phone_type
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return PhoneType the static model class
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
		return 'phone_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone_type', 'required'),
			array('phone_type', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, phone_type, created, modified', 'safe', 'on'=>'search'),
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
			'phones' => array(self::HAS_MANY, 'Phone', 'phone_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone_type' => 'Phone Type',
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

		$criteria->compare('phone_type',$this->phone_type,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
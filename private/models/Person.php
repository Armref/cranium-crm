<?php

/**
 * This is the model class for table "person".
 */
class Person extends Model
{
	/**
	 * The followings are the available columns in table 'person':
	 * @var string $id
	 * @var string $first_name
	 * @var string $middle_name
	 * @var string $last_name
	 * @var string $title
	 * @var string $gender
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Person the static model class
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
		return 'person';
	}

	public function displayFields()
	{
		return array('first_name', 'last_name');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, last_name', 'required'),
			array('first_name, middle_name, last_name', 'length', 'max'=>50),
			array('title', 'length', 'max'=>4),
			array('gender', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, first_name, middle_name, last_name, title, gender, created, modified', 'safe', 'on'=>'search'),
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
			'companyEmployees' => array(self::HAS_MANY, 'CompanyEmployee', 'person_id'),
			'addresses' => array(self::MANY_MANY, 'Address', 'person_address(person_id, address_id)'),
			'phones' => array(self::MANY_MANY, 'Phone', 'person_phone(person_id, phone_id)'),
			'users' => array(self::HAS_MANY, 'User', 'person_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'first_name' => 'First Name',
			'middle_name' => 'Middle Name',
			'last_name' => 'Last Name',
			'title' => 'Title',
			'gender' => 'Gender',
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

		$criteria->compare('first_name',$this->first_name,true);

		$criteria->compare('middle_name',$this->middle_name,true);

		$criteria->compare('last_name',$this->last_name,true);

		$criteria->compare('title',$this->title,true);

		$criteria->compare('gender',$this->gender,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
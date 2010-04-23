<?php

/**
 * This is the model class for table "phone".
 */
class Phone extends Model
{
	/**
	 * The followings are the available columns in table 'phone':
	 * @var string $id
	 * @var string $phone_type_id
	 * @var integer $area_code
	 * @var string $phone_number
	 * @var string $extension
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Phone the static model class
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
		return 'phone';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone_type_id, area_code, phone_number, created, modified', 'required'),
			array('area_code', 'numerical', 'integerOnly'=>true),
			array('phone_type_id', 'length', 'max'=>20),
			array('phone_number', 'length', 'max'=>7),
			array('extension', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, phone_type_id, area_code, phone_number, extension, created, modified', 'safe', 'on'=>'search'),
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
			'companys' => array(self::MANY_MANY, 'Company', 'company_phone(company_id, phone_id)'),
			'persons' => array(self::MANY_MANY, 'Person', 'person_phone(person_id, phone_id)'),
			'phoneType' => array(self::BELONGS_TO, 'PhoneType', 'phone_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone_type_id' => 'Phone Type',
			'area_code' => 'Area Code',
			'phone_number' => 'Phone Number',
			'extension' => 'Extension',
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

		$criteria->compare('phone_type_id',$this->phone_type_id,true);

		$criteria->compare('area_code',$this->area_code);

		$criteria->compare('phone_number',$this->phone_number,true);

		$criteria->compare('extension',$this->extension,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
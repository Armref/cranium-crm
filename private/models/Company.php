<?php

/**
 * This is the model class for table "company".
 */
class Company extends Model
{
	/**
	 * The followings are the available columns in table 'company':
	 * @var string $id
	 * @var string $company_name
	 * @var string $company_code
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Company the static model class
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
		return 'company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_name', 'required'),
			array('company_name', 'length', 'max'=>128),
			array('company_code', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, company_name, company_code, created, modified', 'safe', 'on'=>'search'),
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
			'accounts' => array(self::HAS_MANY, 'Account', 'company_id'),
			'addresses' => array(self::MANY_MANY, 'Address', 'company_address(company_id, address_id)'),
			'companyEmployees' => array(self::HAS_MANY, 'CompanyEmployee', 'company_id'),
			'phones' => array(self::MANY_MANY, 'Phone', 'company_phone(company_id, phone_id)'),
			'webAddresses' => array(self::MANY_MANY, 'WebAddress', 'company_website(company_id, web_address_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company_name' => 'Company Name',
			'company_code' => 'Company Code',
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

		$criteria->compare('company_name',$this->company_name,true);

		$criteria->compare('company_code',$this->company_code,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
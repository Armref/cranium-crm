<?php

/**
 * This is the model class for table "address".
 */
class Address extends Model
{
	/**
	 * The followings are the available columns in table 'address':
	 * @var string $id
	 * @var string $address_type_id
	 * @var string $street_num
	 * @var string $street
	 * @var string $street2
	 * @var string $country_id
	 * @var string $state_id
	 * @var string $county_id
	 * @var string $city_id
	 * @var string $postal_code_id
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Address the static model class
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
		return 'address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('address_type_id, street, country_id, state_id, county_id, city_id, postal_code_id', 'required'),
			array('address_type_id, country_id, state_id, county_id, city_id, postal_code_id', 'length', 'max'=>20),
			array('street_num', 'length', 'max'=>16),
			array('street, street2', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, address_type_id, street_num, street, street2, country_id, state_id, county_id, city_id, postal_code_id, created, modified', 'safe', 'on'=>'search'),
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
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'county' => array(self::BELONGS_TO, 'County', 'county_id'),
			'postalCode' => array(self::BELONGS_TO, 'PostalCode', 'postal_code_id'),
			'state' => array(self::BELONGS_TO, 'State', 'state_id'),
			'addressType' => array(self::BELONGS_TO, 'AddressType', 'address_type_id'),
			'companys' => array(self::MANY_MANY, 'Company', 'company_address(company_id, address_id)'),
			'persons' => array(self::MANY_MANY, 'Person', 'person_address(person_id, address_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'address_type_id' => 'Address Type',
			'street_num' => 'Street Num',
			'street' => 'Street',
			'street2' => 'Street2',
			'country_id' => 'Country',
			'state_id' => 'State',
			'county_id' => 'County',
			'city_id' => 'City',
			'postal_code_id' => 'Postal Code',
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

		$criteria->compare('address_type_id',$this->address_type_id,true);

		$criteria->compare('street_num',$this->street_num,true);

		$criteria->compare('street',$this->street,true);

		$criteria->compare('street2',$this->street2,true);

		$criteria->compare('country_id',$this->country_id,true);

		$criteria->compare('state_id',$this->state_id,true);

		$criteria->compare('county_id',$this->county_id,true);

		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('postal_code_id',$this->postal_code_id,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
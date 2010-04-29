<?php

/**
 * This is the model class for table "postal_code".
 */
class PostalCode extends Model
{
	/**
	 * The followings are the available columns in table 'postal_code':
	 * @var string $id
	 * @var string $country_id
	 * @var string $state_id
	 * @var string $county_id
	 * @var string $city_id
	 * @var string $postal_code
	 * @var string $latitude
	 * @var string $longitude
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return PostalCode the static model class
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
		return 'postal_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_id, state_id, county_id, city_id, postal_code', 'required'),
			array('country_id, state_id, county_id, city_id', 'length', 'max'=>20),
			array('postal_code', 'length', 'max'=>15),
			array('latitude, longitude', 'length', 'max'=>18),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country_id, state_id, county_id, city_id, postal_code, latitude, longitude, created, modified', 'safe', 'on'=>'search'),
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
			'addresses' => array(self::HAS_MANY, 'Address', 'postal_code_id'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
			'county' => array(self::BELONGS_TO, 'County', 'county_id'),
			'state' => array(self::BELONGS_TO, 'State', 'state_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'country_id' => 'Country',
			'state_id' => 'State',
			'county_id' => 'County',
			'city_id' => 'City',
			'postal_code' => 'Postal Code',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
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

		$criteria->compare('country_id',$this->country_id,true);

		$criteria->compare('state_id',$this->state_id,true);

		$criteria->compare('county_id',$this->county_id,true);

		$criteria->compare('city_id',$this->city_id,true);

		$criteria->compare('postal_code',$this->postal_code,true);

		$criteria->compare('latitude',$this->latitude,true);

		$criteria->compare('longitude',$this->longitude,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * This is the model class for table "state".
 */
class State extends Model
{
	/**
	 * The followings are the available columns in table 'state':
	 * @var string $id
	 * @var string $country_id
	 * @var string $state
	 * @var string $state_abbr
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return State the static model class
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
		return 'state';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_id, state, state_abbr, created, modified', 'required'),
			array('country_id', 'length', 'max'=>20),
			array('state', 'length', 'max'=>64),
			array('state_abbr', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country_id, state, state_abbr, created, modified', 'safe', 'on'=>'search'),
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
			'addresses' => array(self::HAS_MANY, 'Address', 'state_id'),
			'cities' => array(self::HAS_MANY, 'City', 'state_id'),
			'counties' => array(self::HAS_MANY, 'County', 'state_id'),
			'postalCodes' => array(self::HAS_MANY, 'PostalCode', 'state_id'),
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
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
			'state' => 'State',
			'state_abbr' => 'State Abbr',
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

		$criteria->compare('state',$this->state,true);

		$criteria->compare('state_abbr',$this->state_abbr,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
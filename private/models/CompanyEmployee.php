<?php

/**
 * This is the model class for table "company_employee".
 */
class CompanyEmployee extends Model
{
	/**
	 * The followings are the available columns in table 'company_employee':
	 * @var string $id
	 * @var string $person_id
	 * @var string $company_id
	 * @var string $position
	 * @var string $lft
	 * @var string $rgt
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CompanyEmployee the static model class
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
		return 'company_employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('person_id, company_id, position'/*, lft, rgt'*/, 'required'),
			array('person_id, company_id, lft, rgt', 'length', 'max'=>20),
			array('position', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, person_id, company_id, position, created, modified', 'safe', 'on'=>'search'),
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
			'accounts' => array(self::MANY_MANY, 'Account', 'account_contact(account_id, company_employee_id)'),
			'person' => array(self::BELONGS_TO, 'Person', 'person_id'),
			'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'person_id' => 'Person',
			'company_id' => 'Company',
			'position' => 'Position',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
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

		$criteria->compare('person_id',$this->person_id,true);

		$criteria->compare('company_id',$this->company_id,true);

		$criteria->compare('position',$this->position,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
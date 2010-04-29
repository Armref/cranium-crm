<?php

/**
 * This is the model class for table "account".
 */
class Account extends Model
{
	/**
	 * The followings are the available columns in table 'account':
	 * @var string $id
	 * @var string $account_name
	 * @var string $company_id
	 * @var string $lft
	 * @var string $rgt
	 * @var string $company_num
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Account the static model class
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
		return 'account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_name, company_id, lft, rgt', 'required'),
			array('account_name', 'length', 'max'=>128),
			array('company_id, lft, rgt', 'length', 'max'=>20),
			array('company_num', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_name, company_id, lft, rgt, company_num, created, modified', 'safe', 'on'=>'search'),
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
			'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
			'companyEmployees' => array(self::MANY_MANY, 'CompanyEmployee', 'account_contact(account_id, company_employee_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_name' => 'Account Name',
			'company_id' => 'Company',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'company_num' => 'Company Num',
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

		$criteria->compare('account_name',$this->account_name,true);

		$criteria->compare('company_id',$this->company_id,true);

		$criteria->compare('lft',$this->lft,true);

		$criteria->compare('rgt',$this->rgt,true);

		$criteria->compare('company_num',$this->company_num,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
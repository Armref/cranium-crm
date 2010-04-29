<?php

/**
 * This is the model class for table "user".
 */
class User extends Model
{
	/**
	 * The followings are the available columns in table 'user':
	 * @var string $id
	 * @var string $username
	 * @var string $password
	 * @var string $person_id
	 * @var string $created
	 * @var string $modified
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'user';
	}

	protected function beforeSave()
	{
		$passwordDiffers = true;
		/**
		 * If existing user, check password
		 */
		if(!$this->getIsNewRecord())
		{
			if(isset($this->oldAttributes['password']) && strcmp($this->oldAttributes['password'],$this->password)==0)
			{
				$passwordDiffers = false;
			}
		}

		if($passwordDiffers && !empty($this->password))
		{
			$this->password = $this->hashPassword($this->password);
		}elseif(!empty($this->oldAttributes['password'])){
			$this->password = $this->oldAttributes['password'];
		}

		return parent::beforeSave();
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, person_id', 'required'),
			array('password', 'required', 'on'=>'create'),
			array('username', 'length', 'max'=>32),
			array('username', 'unique'),
			array('password', 'length', 'max'=>32),
			array('person_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, person_id, created, modified', 'safe', 'on'=>'search'),
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
			'systemLogs' => array(self::HAS_MANY, 'SystemLog', 'user_id'),
			'person' => array(self::BELONGS_TO, 'Person', 'person_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			#'salt' => 'Salt',
			'person_id' => 'Person',
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

		$criteria->compare('username',$this->username,true);

		$criteria->compare('person_id',$this->person_id,true);

		$criteria->compare('created',$this->created,true);

		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider(get_class($this), array(
				'criteria'=>$criteria,
		));
	}

	public function validatePassword($password)
	{
		return $this->hashPassword($password/*, $this->salt*/)===$this->password;
	}

	public function hashPassword($password/*, $salt*/)
	{
		return hash('sha512', /*$salt . */$password);
	}
}
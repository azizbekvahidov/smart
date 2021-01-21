<?php

/**
 * This is the model class for table "employee".
 *
 * The followings are the available columns in table 'employee':
 * @property integer $employeeId
 * @property string $name
 * @property string $birthday
 * @property string $photo
 * @property string $surname
 * @property string $lastname
 * @property integer $departmentId
 * @property integer $sex
 * @property integer $status
 * @property integer $positionId
 * @property string $photo2
 * @property string $code
 * @property string $password
 * @property string $login
 */
class Employee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('departmentId, sex, status, positionId', 'numerical', 'integerOnly'=>true),
			array('name, photo, surname, lastname, photo2', 'length', 'max'=>50),
			array('code', 'length', 'max'=>15),
			array('password', 'length', 'max'=>255),
			array('login', 'length', 'max'=>100),
			array('birthday', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('employeeId, name, birthday, photo, surname, lastname, departmentId, sex, status, positionId, photo2, code, password, login', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'employeeId' => 'Employee',
			'name' => 'Name',
			'birthday' => 'Birthday',
			'photo' => 'Photo',
			'surname' => 'Surname',
			'lastname' => 'Lastname',
			'departmentId' => 'Department',
			'sex' => 'Sex',
			'status' => 'Status',
			'positionId' => 'Position',
			'photo2' => 'Photo2',
			'code' => 'Code',
			'password' => 'Password',
			'login' => 'Login',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('employeeId',$this->employeeId);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('departmentId',$this->departmentId);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('status',$this->status);
		$criteria->compare('positionId',$this->positionId);
		$criteria->compare('photo2',$this->photo2,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('login',$this->login,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Employee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getEmployeeList(){
	    $model = Yii::app()->db->CreateCommand()
	        ->select()
	        ->from("employee")
	        ->where('status != 1')
            ->order('positionId desc')
	        ->queryAll();

        return $model;
    }

    public function getEmployee($id){

        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where('employeeId = :id',array(':id'=>$id))
            ->queryRow();

        return $model;
    }

}

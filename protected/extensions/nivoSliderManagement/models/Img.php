<?php

/**
 * This is the model class for table "images".
 *
 * The followings are the available columns in table 'images':
 * @property string $id
 *  @property string $file_id
 * @property string $basename
 * @property string $extension
 * @property string $title
 * @property string $size
 * @property string $type
 * @property string $path
 * * @property string $url
 */
class Img extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Img the static model class
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
		return 'images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
		 	array('title', 'length', 'max'=>256),
		
			//array('title', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id,basename, extension,size, type, path,url', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
                        'file_id'=>'UniqueID',
			'basename' => 'Name',
			'extension' => 'Extension',
			'title' => 'Title',
			'size' => 'Size',
			'type' => 'Type',
			'path' => 'URLPath',
                        'url' => 'URL',
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

		$criteria->compare('basename',$this->basename,true);
		$criteria->compare('extension',$this->extension,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('path',$this->path,true);
                $criteria->compare('url',$this->url,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

        


}
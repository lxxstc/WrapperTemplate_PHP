<?php

/**
 * This is the model class for table "citynames_publish".
 *
 * The followings are the available columns in table 'citynames_publish':
 * @property string $cityname
 * @property string $belongToCity
 * @property integer $validate
 * @property string $publish_time
 */
class CitynamesPublish extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CitynamesPublish the static model class
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
		return 'citynames_publish';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cityname, belongToCity, publish_time', 'required'),
			array('validate', 'numerical', 'integerOnly'=>true),
			array('cityname, belongToCity', 'length', 'max'=>45),
			array('publish_time', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cityname, belongToCity, validate, publish_time', 'safe', 'on'=>'search'),
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
			'cityname' => 'Cityname',
			'belongToCity' => 'Belong To City',
			'validate' => 'Validate',
			'publish_time' => 'Publish Time',
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

		$criteria->compare('cityname',$this->cityname,true);
		$criteria->compare('belongToCity',$this->belongToCity,true);
		$criteria->compare('validate',$this->validate);
		$criteria->compare('publish_time',$this->publish_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
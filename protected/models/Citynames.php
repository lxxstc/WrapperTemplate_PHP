<?php

/**
 * This is the model class for table "citynames".
 *
 * The followings are the available columns in table 'citynames':
 * @property string $cityname
 * @property string $belongToCity
 * @property integer $validate
 */
class Citynames extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Citynames the static model class
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
		return 'citynames';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cityname, belongToCity', 'required'),
			array('validate', 'numerical', 'integerOnly'=>true),
			array('cityname, belongToCity', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cityname, belongToCity, validate', 'safe', 'on'=>'search'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
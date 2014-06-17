<?php

/**
 * This is the model class for table "airportcode_publish".
 *
 * The followings are the available columns in table 'airportcode_publish':
 * @property string $belongToCity
 * @property string $Code
 * @property string $airport_zh
 * @property string $airport_en
 * @property string $validate
 * @property string $airport_zh_short
 * @property string $publish_time
 */
class AirportcodePublish extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AirportcodePublish the static model class
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
		return 'airportcode_publish';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('belongToCity, Code, airport_zh, airport_en, airport_zh_short, publish_time', 'required'),
			array('belongToCity, airport_zh, airport_en', 'length', 'max'=>45),
			array('Code', 'length', 'max'=>3),
			array('validate', 'length', 'max'=>10),
			array('airport_zh_short', 'length', 'max'=>16),
			array('publish_time', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('belongToCity, Code, airport_zh, airport_en, validate, airport_zh_short, publish_time', 'safe', 'on'=>'search'),
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
			'belongToCity' => 'Belong To City',
			'Code' => 'Code',
			'airport_zh' => 'Airport Zh',
			'airport_en' => 'Airport En',
			'validate' => 'Validate',
			'airport_zh_short' => 'Airport Zh Short',
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

		$criteria->compare('belongToCity',$this->belongToCity,true);
		$criteria->compare('Code',$this->Code,true);
		$criteria->compare('airport_zh',$this->airport_zh,true);
		$criteria->compare('airport_en',$this->airport_en,true);
		$criteria->compare('validate',$this->validate,true);
		$criteria->compare('airport_zh_short',$this->airport_zh_short,true);
		$criteria->compare('publish_time',$this->publish_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
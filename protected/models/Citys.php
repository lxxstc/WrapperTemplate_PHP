<?php

/**
 * This is the model class for table "citys".
 *
 * The followings are the available columns in table 'citys':
 * @property string $uri
 * @property string $cityname_zh
 * @property string $cityname_en
 * @property string $belongToState
 * @property string $belongToCountry
 * @property string $validate
 * @property string $shortcode
 * @property string $citycode
 * @property string $hotcity
 * @property string $countrys_hotcitys
 * @property string $sort
 */
class Citys extends Ar 
{

    public $dbname = 'db_infocenter';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Citys the static model class
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
		return 'citys';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uri, cityname_zh, belongToState, belongToCountry, shortcode, citycode', 'required'),
			array('uri, cityname_zh, cityname_en, belongToState, belongToCountry', 'length', 'max'=>45),
			array('validate, shortcode, citycode, hotcity, countrys_hotcitys, sort', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('uri, cityname_zh, cityname_en, belongToState, belongToCountry, validate, shortcode, citycode, hotcity, countrys_hotcitys, sort', 'safe', 'on'=>'search'),
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
			'uri' => 'Uri',
			'cityname_zh' => 'Cityname Zh',
			'cityname_en' => 'Cityname En',
			'belongToState' => 'Belong To State',
			'belongToCountry' => 'Belong To Country',
			'validate' => 'Validate',
			'shortcode' => 'Shortcode',
			'citycode' => 'Citycode',
			'hotcity' => 'Hotcity',
			'countrys_hotcitys' => 'Countrys Hotcitys',
			'sort' => 'Sort',
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

		$criteria->compare('uri',$this->uri,true);
		$criteria->compare('cityname_zh',$this->cityname_zh,true);
		$criteria->compare('cityname_en',$this->cityname_en,true);
		$criteria->compare('belongToState',$this->belongToState,true);
		$criteria->compare('belongToCountry',$this->belongToCountry,true);
		$criteria->compare('validate',$this->validate,true);
		$criteria->compare('shortcode',$this->shortcode,true);
		$criteria->compare('citycode',$this->citycode,true);
		$criteria->compare('hotcity',$this->hotcity,true);
		$criteria->compare('countrys_hotcitys',$this->countrys_hotcitys,true);
		$criteria->compare('sort',$this->sort,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

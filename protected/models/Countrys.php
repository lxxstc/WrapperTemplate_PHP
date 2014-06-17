<?php

/**
 * This is the model class for table "countrys".
 *
 * The followings are the available columns in table 'countrys':
 * @property string $uri
 * @property string $countryname_zh
 * @property string $countryname_en
 * @property string $validate
 * @property string $belongToContinent
 * @property string $shortcode
 * @property string $code
 * @property string $country2code
 * @property string $country3code
 */
class Countrys extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Countrys the static model class
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
		return 'countrys';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uri, countryname_zh, countryname_en, shortcode, code, country3code', 'required'),
			array('uri, countryname_zh, countryname_en', 'length', 'max'=>45),
			array('validate, shortcode, country2code, country3code', 'length', 'max'=>10),
			array('belongToContinent', 'length', 'max'=>50),
			array('code', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('uri, countryname_zh, countryname_en, validate, belongToContinent, shortcode, code, country2code, country3code', 'safe', 'on'=>'search'),
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
			'countryname_zh' => 'Countryname Zh',
			'countryname_en' => 'Countryname En',
			'validate' => 'Validate',
			'belongToContinent' => 'Belong To Continent',
			'shortcode' => 'Shortcode',
			'code' => 'Code',
			'country2code' => 'Country2code',
			'country3code' => 'Country3code',
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
		$criteria->compare('countryname_zh',$this->countryname_zh,true);
		$criteria->compare('countryname_en',$this->countryname_en,true);
		$criteria->compare('validate',$this->validate,true);
		$criteria->compare('belongToContinent',$this->belongToContinent,true);
		$criteria->compare('shortcode',$this->shortcode,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('country2code',$this->country2code,true);
		$criteria->compare('country3code',$this->country3code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
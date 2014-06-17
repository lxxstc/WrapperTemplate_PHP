<?php

/**
 * This is the model class for table "continent".
 *
 * The followings are the available columns in table 'continent':
 * @property string $uri
 * @property string $continentname_zh
 * @property string $continentname_en
 * @property integer $validate
 */
class Continent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Continent the static model class
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
		return 'continent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uri, continentname_zh, continentname_en', 'required'),
			array('validate', 'numerical', 'integerOnly'=>true),
			array('uri, continentname_zh, continentname_en', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('uri, continentname_zh, continentname_en, validate', 'safe', 'on'=>'search'),
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
			'continentname_zh' => 'Continentname Zh',
			'continentname_en' => 'Continentname En',
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

		$criteria->compare('uri',$this->uri,true);
		$criteria->compare('continentname_zh',$this->continentname_zh,true);
		$criteria->compare('continentname_en',$this->continentname_en,true);
		$criteria->compare('validate',$this->validate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * This is the model class for table "attractions_publish".
 *
 * The followings are the available columns in table 'attractions_publish':
 * @property string $id
 * @property string $uri
 * @property string $name_zh
 * @property string $name_en
 * @property string $belongToCity
 * @property double $length
 * @property string $shortcode
 * @property string $code
 * @property string $validate
 * @property string $publish_time
 */
class AttractionsPublish extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AttractionsPublish the static model class
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
		return 'attractions_publish';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, uri, name_zh, name_en, belongToCity, shortcode, code, publish_time', 'required'),
			array('length', 'numerical'),
			array('id, shortcode, validate', 'length', 'max'=>10),
			array('uri, name_zh, name_en, belongToCity', 'length', 'max'=>45),
			array('code', 'length', 'max'=>40),
			array('publish_time', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uri, name_zh, name_en, belongToCity, length, shortcode, code, validate, publish_time', 'safe', 'on'=>'search'),
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
			'uri' => 'Uri',
			'name_zh' => 'Name Zh',
			'name_en' => 'Name En',
			'belongToCity' => 'Belong To City',
			'length' => 'Length',
			'shortcode' => 'Shortcode',
			'code' => 'Code',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('uri',$this->uri,true);
		$criteria->compare('name_zh',$this->name_zh,true);
		$criteria->compare('name_en',$this->name_en,true);
		$criteria->compare('belongToCity',$this->belongToCity,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('shortcode',$this->shortcode,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('validate',$this->validate,true);
		$criteria->compare('publish_time',$this->publish_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
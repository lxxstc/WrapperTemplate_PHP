<?php

/**
 * This is the model class for table "airline_publish".
 *
 * The followings are the available columns in table 'airline_publish':
 * @property string $id
 * @property string $code
 * @property string $icon
 * @property string $name_zh
 * @property string $name_en
 * @property string $name_zh_short
 * @property string $name_zh_short_2code
 * @property string $publish_time
 * @property string $telephone
 */
class AirlinePublish extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AirlinePublish the static model class
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
		return 'airline_publish';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, code, icon, name_zh, name_en, name_zh_short, name_zh_short_2code, publish_time', 'required'),
			array('id', 'length', 'max'=>8),
			array('code, name_zh, name_en', 'length', 'max'=>100),
			array('icon', 'length', 'max'=>255),
			array('name_zh_short, name_zh_short_2code', 'length', 'max'=>50),
			array('publish_time, telephone', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, icon, name_zh, name_en, name_zh_short, name_zh_short_2code, publish_time, telephone', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'icon' => 'Icon',
			'name_zh' => 'Name Zh',
			'name_en' => 'Name En',
			'name_zh_short' => 'Name Zh Short',
			'name_zh_short_2code' => 'Name Zh Short 2code',
			'publish_time' => 'Publish Time',
			'telephone' => 'Telephone',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('name_zh',$this->name_zh,true);
		$criteria->compare('name_en',$this->name_en,true);
		$criteria->compare('name_zh_short',$this->name_zh_short,true);
		$criteria->compare('name_zh_short_2code',$this->name_zh_short_2code,true);
		$criteria->compare('publish_time',$this->publish_time,true);
		$criteria->compare('telephone',$this->telephone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
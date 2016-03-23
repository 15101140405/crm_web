<?php

/**
 * This is the model class for table "staff".
 *
 * The followings are the available columns in table 'staff':
 * @property integer $id
 * @property integer $account_id
 * @property integer $name
 * @property integer $gender
 * @property string $avatar
 * @property string $avatar_media_id
 * @property string $job_title
 * @property string $telephone
 * @property string $weixin_id
 * @property string $email
 * @property integer $company_id
 * @property string $department_list
 * @property string $hotel_list
 * @property string $extattr
 * @property string $target
 * @property string $update_time
 */
class Staff extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'staff';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, name, gender, avatar, avatar_media_id, job_title, telephone, weixin_id, email, company_id, department_list, hotel_list, extattr, target, update_time', 'required'),
			array('account_id, name, gender, company_id', 'numerical', 'integerOnly'=>true),
			array('avatar, avatar_media_id, job_title, telephone, weixin_id, email, department_list, hotel_list, extattr, target', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account_id, name, gender, avatar, avatar_media_id, job_title, telephone, weixin_id, email, company_id, department_list, hotel_list, extattr, target, update_time', 'safe', 'on'=>'search'),
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
			'account_id' => 'Account',
			'name' => 'Name',
			'gender' => 'Gender',
			'avatar' => 'Avatar',
			'avatar_media_id' => 'Avatar Media',
			'job_title' => 'Job Title',
			'telephone' => 'Telephone',
			'weixin_id' => 'Weixin',
			'email' => 'Email',
			'company_id' => 'Company',
			'department_list' => 'Department List',
			'hotel_list' => 'Hotel List',
			'extattr' => 'Extattr',
			'target' => 'Target',
			'update_time' => 'Update Time',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('name',$this->name);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('avatar_media_id',$this->avatar_media_id,true);
		$criteria->compare('job_title',$this->job_title,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('weixin_id',$this->weixin_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('department_list',$this->department_list,true);
		$criteria->compare('hotel_list',$this->hotel_list,true);
		$criteria->compare('extattr',$this->extattr,true);
		$criteria->compare('target',$this->target,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Staff the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

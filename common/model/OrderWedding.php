<?php

/**
 * This is the model class for table "order_wedding".
 *
 * The followings are the available columns in table 'order_wedding':
 * @property integer $id
 * @property integer $account_id
 * @property integer $order_id
 * @property integer $expect_table_count
 * @property double $feast_discount
 * @property double $wedding_discount
 * @property string $update_time
 */
class OrderWedding extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_wedding';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	// public function rules()
	// {
	// 	// NOTE: you should only define rules for those attributes that
	// 	// will receive user inputs.
	// 	return array(
	// 		array('account_id, order_id, update_time', 'required'),
	// 		array('account_id, order_id', 'numerical', 'integerOnly'=>true),
	// 		// array('feast_discount, wedding_discount', 'numerical'),
	// 		// The following rule is used by search().
	// 		// @todo Please remove those attributes that should not be searched.
	// 		array('id, account_id, order_id, groom_name, groom_phone, groom_wechat, groom_qq,bride_name,bride_phone,bride_wechat,bride_qq, update_time', 'safe', 'on'=>'search'),
	// 	);
	// }

	/**
	 * @return array relational rules.
	 */
	// public function relations()
	// {
	// 	// NOTE: you may need to adjust the relation name and the related
	// 	// class name for the relations automatically generated below.
	// 	return array(
	// 	);
	// }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	// public function attributeLabels()
	// {
	// 	return array(
	// 		'id' => 'ID',
	// 		'account_id' => 'Account',
	// 		'order_id' => 'Order',
	// 		// 'expect_table_count' => 'Expect Table Count',
	// 		// 'feast_discount' => 'Feast Discount',
	// 		// 'wedding_discount' => 'Wedding Discount',
	// 		// ‘, groom_phone, groom_wechat, groom_qq,bride_name,bride_phone,bride_wechat,bride_qq,contact_name,contact_phone,
	// 		'update_time' => 'Update Time',
	// 	);
	// }

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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('groom_name',$this->groom_name);
		$criteria->compare('groom_phone',$this->groom_phone);
		$criteria->compare('groom_wechat',$this->groom_wechat);
		$criteria->compare('groom_qq',$this->groom_qq);
		$criteria->compare('bride_name',$this->bride_name);
		$criteria->compare('bride_phone',$this->bride_phone);	
		$criteria->compare('bride_wechat',$this->bride_wechat);	
		$criteria->compare('bride_qq',$this->bride_qq);		
		$criteria->compare('contact_name',$this->bride_qq);		
		$criteria->compare('contact_phone',$this->bride_qq);		
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderWedding the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

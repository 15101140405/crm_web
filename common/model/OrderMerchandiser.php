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
class OrderMerchandiser extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_merchandiser';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	/*public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		/*return array(
			array('id, order_id, time, remarks, type', 'required'),
			array('id, order_id', 'integerOnly'=>true),
			// array('feast_discount, wedding_discount', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, time, remarks, type', 'safe', 'on'=>'search'),
		);
	}*/

	/**
	 * @return array relational rules.
	 */
	/*public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		
	}*/

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	/*public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order ID',
			// 'expect_table_count' => 'Expect Table Count',
			// 'feast_discount' => 'Feast Discount',
			// 'wedding_discount' => 'Wedding Discount',
			// ‘, groom_phone, groom_wechat, groom_qq,bride_name,bride_phone,bride_wechat,bride_qq,contact_name,contact_phone,
			'time' => 'Time',
		);
	}*/

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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('time',$this->time);
		$criteria->compare('remarks',$this->remarks);
		$criteria->compare('type',$this->type);
		$criteria->compare('staff_name',$this->staff_name);
		$criteria->compare('order_name',$this->order_name);
		$criteria->compare('order_date',$this->order_date);

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

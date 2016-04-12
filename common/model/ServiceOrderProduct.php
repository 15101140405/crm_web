<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property string $id
 * @property integer $account_id
 * @property integer $planner_id
 * @property integer $staff_hotel_id
 * @property string $order_name
 * @property string $order_type
 * @property string $order_date
 * @property string $order_time
 * @property integer $order_status
 * @property string $update_time
 */
class ServiceOrderProduct extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service_order_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	/*public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, planner_id, staff_hotel_id, order_name, order_type, order_date, order_time, order_status, update_time', 'required'),
			array('account_id, planner_id, staff_hotel_id, order_status', 'numerical', 'integerOnly'=>true),
			array('order_name', 'length', 'max'=>256),
			array('order_type, order_time', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account_id, planner_id, designer_id, staff_hotel_id, order_name, order_type, order_date, order_time, order_status, update_time, feast_deposit, medium_term, final_payments', 'safe', 'on'=>'search'),
		);
	}*/

	/**
	 * @return array relational rules.
	 */
	/*public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}*/

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	/*public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_id' => 'Account',
			'planner_id' => 'Planner',
			'staff_hotel_id' => 'Staff Hotel',
			'order_name' => 'Order Name',
			'order_type' => 'Order Type',
			'order_date' => 'Order Date',
			'order_time' => 'Order Time',
			'order_status' => 'Order Status',
			'update_time' => 'Update Time',
			'feast_deposit' => 'Deposit',
			'medium_term' => 'Medium',
			'final_payments' => 'Final',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('service_person_id',$this->service_person_id);
		$criteria->compare('service_order_id',$this->service_order_id,true);
		$criteria->compare('service_product_id',$this->service_product_id,true);
		$criteria->compare('actural_price',$this->actural_price);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

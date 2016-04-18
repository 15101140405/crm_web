<?php

/**
 * This is the model class for table "supplier_product".
 *
 * The followings are the available columns in table 'supplier_product':
 * @property integer $id
 * @property integer $account_id
 * @property integer $supplier_id
 * @property integer $type_id
 * @property string $name
 * @property string $category
 * @property double $unit_price
 * @property double $unit_cost
 * @property string $unit
 * @property integer $service_charge_ratio
 * @property string $ref_pic_url
 * @property string $description
 * @property string $update_time
 */
class SupplierProduct extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'supplier_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	/*public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, supplier_id, name, category, unit_price, unit_cost, unit, service_charge_ratio, ref_pic_url, description, update_time', 'required'),
			array('account_id, supplier_id, service_charge_ratio', 'numerical', 'integerOnly'=>true),
			array('unit_price, unit_cost', 'numerical'),
			array('name, category, unit, ref_pic_url', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account_id, supplier_id, name, category, unit_price, unit_cost, unit, service_charge_ratio, ref_pic_url, description, update_time', 'safe', 'on'=>'search'),
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
			'supplier_id' => 'Supplier',
			'name' => 'Name',
			'category' => 'Category',
			'unit_price' => 'Unit Price',
			'unit_cost' => 'Unit Cost',
			'unit' => 'Unit',
			'service_charge_ratio' => 'Service Charge Ratio',
			'ref_pic_url' => 'Ref Pic Url',
			'description' => 'Description',
			'update_time' => 'Update Time',
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
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('supplier_type_id',$this->supplier_type_id);
		$criteria->compare('standard_id',$this->standard_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('unit_price',$this->unit_price);
		$criteria->compare('unit_cost',$this->unit_cost);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('service_charge_ratio',$this->service_charge_ratio);
		$criteria->compare('ref_pic_url',$this->ref_pic_url,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupplierProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

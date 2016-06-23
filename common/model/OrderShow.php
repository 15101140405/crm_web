<?php

/**
 * This is the model class for table "supplier_type".
 *
 * The followings are the available columns in table 'supplier_type':
 * @property integer $id
 * @property integer $account_id
 * @property string $name
 * @property string $role
 * @property string $update_time
 * @property string $avatar
 */
class OrderShow extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_show';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type);
		$criteria->compare('img_url',$this->img_url,true);
		$criteria->compare('order_product_id',$this->order_product_id,true);
		$criteria->compare('words',$this->words,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('show_area',$this->show_area,true);
		$criteria->compare('area_sort',$this->area_sort,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupplierType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

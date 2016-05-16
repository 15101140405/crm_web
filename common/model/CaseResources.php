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
class CaseResources extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'case_resources';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('CR_ID',$this->CR_ID);
		$criteria->compare('CI_ID',$this->CI_ID);
		$criteria->compare('CR_Type',$this->CR_Type,true);
		$criteria->compare('CR_Sort',$this->CR_Sort,true);
		$criteria->compare('CR_Name',$this->CR_Name,true);
		$criteria->compare('CR_CreateTime',$this->CR_CreateTime,true);
		$criteria->compare('CR_Path',$this->CR_Path,true);
		$criteria->compare('CR_Show',$this->CR_Show,true);
		$criteria->compare('CR_Remarks',$this->CR_Remarks,true);

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

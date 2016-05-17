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
class CaseInfo extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'case_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('CI_ID',$this->CI_ID);
		$criteria->compare('CI_Name',$this->CI_Name);
		$criteria->compare('CI_Place',$this->CI_Place,true);
		$criteria->compare('CI_Pic',$this->CI_Pic,true);
		$criteria->compare('CI_Time',$this->CI_Time,true);
		$criteria->compare('CI_CreateTime',$this->CI_CreateTime,true);
		$criteria->compare('CI_Sort',$this->CI_Sort,true);
		$criteria->compare('CI_Show',$this->CI_Show,true);
		$criteria->compare('CI_Remarks',$this->CI_Remarks,true);
		$criteria->compare('CI_Type',$this->CI_Type,true);
		$criteria->compare('CT_ID',$this->CT_ID,true);


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

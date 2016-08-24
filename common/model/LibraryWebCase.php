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
class LibraryWebCase extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'library_web_case';
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
		$criteria->compare('Case_ID',$this->Case_ID);
		$criteria->compare('Original_ID',$this->Original_ID);
		$criteria->compare('Web_ID',$this->Web_ID);
		$criteria->compare('Case_Name',$this->Case_Name);
		$criteria->compare('Case_Company',$this->Case_Company,true);
		$criteria->compare('Company_Logo',$this->Case_Company,true);
		$criteria->compare('Company_Adress',$this->Company_Adress,true);
		$criteria->compare('Case_Description',$this->Case_Description,true);
		$criteria->compare('cover_img',$this->cover_img,true);
		$criteria->compare('local_cover',$this->local_cover,true);
		$criteria->compare('sort',$this->sort,true);
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

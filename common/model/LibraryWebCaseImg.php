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
class LibraryWebCaseImg extends InitActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'library_web_case_img';
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
		$criteria->compare('Img_ID',$this->Img_ID);
		$criteria->compare('Original_ID',$this->Original_ID);
		$criteria->compare('Case_ID',$this->Case_ID);
		$criteria->compare('pre_URL',$this->pre_URL);
		$criteria->compare('local_URL',$this->local_URL);
		$criteria->compare('down',$this->down);
		$criteria->compare('be_liked',$this->be_liked,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('local_URL_lg',$this->local_URL_lg);


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

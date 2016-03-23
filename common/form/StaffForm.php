<?php

/**
 * Class StaffForm
 * Staff info
 */
class StaffForm extends InitForm
{
    public function getStaffList($accountId)
    {
        $staffArr = Staff::model()->findAll(array(
            "condition" => "account_id=:account_id",
            "params" => array(
                "account_id" => $accountId,
            ),
            "order" => "name desc",
        ));

        $departmentArr = $this->getDepartments($accountId, true);
        foreach ($staffArr as &$staff) {
            $idArr = $this->decodeJson($staff->department_list);
            $nameArr = array();
            foreach ($idArr as $id) {
                if (isset($departmentArr[$id])) {
                    $department = $departmentArr[$id];
                    $nameArr[] = $department->name;

                }
            }
            $staff->department_list = implode(" ", $nameArr);
        }

        return $staffArr;
    }

    public function getDepartments($accountId, $useIdAsKey = false)
    {
        $departmentArr = StaffDepartment::model()->findAll(array(
            "condition" => "account_id=:account_id",
            "params" => array(
                "account_id" => $accountId,
            ),
        ));
        if (!$useIdAsKey) {
            return $departmentArr;
        }

        $result = array();
        foreach ($departmentArr as $department) {
            $result[$department->id] = $department;
        }

        return $result;
    }

    public function getName($id)
    {
        $staff = Staff::model()->findByPk($id);
        return $staff['name'];
    }

}

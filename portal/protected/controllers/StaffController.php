<?php

class StaffController extends InitController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main-not-exited';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions' => array(''),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionList()
    {
        $accountId = $this->getAccountId();

        $staffForm = new StaffForm();
        $staffList = $staffForm->getStaffList($accountId);

        $this->render("list", array(
            "staffList" => $staffList,
        ));
    }

    public function actionAdd()
    {
        $model = new Staff();
        $this->render("add", array(
            "model" => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $staff = Staff::model()->findByPk($id);

        $this->render("add", array(
            "model" => $staff,
            "action" => "update",
        ));
    }

    public function actionChooseDept()
    {
        $accountId = $this->getAccountId();

        $staffForm = new StaffForm();
        $departments = $staffForm->getDepartments($accountId);

        $this->render("chooseDept", array(
            "departments" => $departments,
        ));
    }

}

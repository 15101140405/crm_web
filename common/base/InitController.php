<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for applications should extend from this base class.
 */
class InitController extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $smartyLayout = '';

    /**
     * 渲染一个smarty模板
     * 使用smarty替换Yii本身自带的view层
     *
     * @param string $view 模板文件名
     * @param array $data 模板变量
     * @param boolean $return 是否显示结果，或者返回结果字符串
     * @return string 渲染结果. 如果不需要则返回null
     */
    public function renderSmarty($view, $data = null, $return = false)
    {
        $smarty = Yii::app()->smarty;
        if (!is_array($data)) {
            $data = array();
        }
        $data['user'] = Yii::app()->user;

        if ($this->layout != '') {
            $data['__view__'] = $view;
            $view = $this->layout;
        }
        $smarty->assign($data);
        if ($return) {
            return $smarty->fetch($view);
        } else {
            $smarty->display($view);
            return null;
        }
    }

    /**
     * 渲染ajaxModel
     *
     * @param CModel $model
     */
    public function renderAjaxModel($model)
    {
        $success = !$model->hasErrors();
        if (isset($model->message)) {
            $message = $model->message;
            $data = array(
                'success' => $success,
                'message' => $message,
                'data' => $model->data,
            );
        } else {
            $data = array(
                'success' => $success,
                'messages' => $model->errors,
                'data' => $model->data,
            );
        }
        $this->renderJson($data);
    }

    /**
     * @param mixed
     */
    public function renderJson($data)
    {
        print(CJSON::encode($data));

    }

    /***
     * 截取utf8字符串
     * @param $string
     * @param $length
     * @param string $etc
     * @return string
     */
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strLen = strlen($string);
        for ($i = 0; (($i < $strLen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strLen) {
            $result .= $etc;
        }
        return $result;
    }

    /**
     * 随机码
     * @param int $pw_length
     * @return string
     */
    public static function generateCode($pw_length = 8)
    {
        $randPwd = '';
        for ($i = 0; $i < $pw_length; $i++) {
            // 从97-122中选择一个随机数(a-z的ascii),然后转换成小写字母
            $randPwd .= chr(mt_rand(97, 122));
        }
        return $randPwd;
    }

    /**
     * 将数组array转成utf8
     * @param $array
     * @return mixed
     */
    public static function utf8ToGBK($array)
    {
        foreach ($array as &$value) {
            $temp = $value;
            try {
                $value = iconv('UTF-8', 'GBK//ignore', $value);
            } catch (Exception $e) {
                $value = $temp;
            }
        }
        return $array;
    }

    public function getActionPath()
    {
        return $this->id . "/" . $this->action->id;
    }

    public function sucLog($message)
    {
        $action = $this->getActionPath();
        $module = Yii::app()->name;

        LogForm::log($action, $message, $module, "info");
    }

    public function yiiLog($message)
    {
        $action = $this->getActionPath();
        $module = Yii::app()->name;

        LogForm::yiiLog($message, "info");
    }

    public function errLog($message)
    {
        $action = $this->getActionPath();
        $module = Yii::app()->name;

        LogForm::log($action, $message, $module, "error");
    }

    /**
     * 成功时响应
     *
     * @param $message
     * @param $data
     * @param null $ext
     * @param bool|false $withoutJson
     * @param null $code
     * @param bool|false $withLog
     * @return bool
     */
    public function sucResponse($message, $data, $ext = null, $withoutJson = false, $code = null, $withLog = false)
    {
        $response = array(
            'code' => $code ? $code : 0,
            'message' => $message,
            'data' => $data,
        );

        if (is_array($ext) && count($ext) > 0) {
            $response = array_merge($ext, $response);
        }

        if (is_array($message)) {
            $message = InitForm::array2Json($message);
        }

        if ($withLog) {
            $this->sucLog($message);
        }

        if ($withoutJson === true) {
            return true;
        } else {
            $this->renderJson($response);
        }

    }

    /**
     * 失败时响应
     *
     * @param null $message
     * @param null $ext
     * @param null $code
     * @param bool $withoutJson
     * @return bool|void
     */
    public function errResponse($message = null, $ext = null, $code = null, $withoutJson = false)
    {
        $response = array(
            'code' => $code ? $code : 100,
            'message' => $message,
            'data' => null,
        );

        if (is_array($ext) && count($ext) > 0) {
            $response = array_merge($ext, $response);
        }

        if (is_array($message)) {
            $message = InitForm::array2Json($message);
        }

        $this->errLog($message);

        if ($withoutJson === true) {
            return true;
        } else {
            $this->renderJson($response);
        }

    }

    public function getAccountId()
    {
        $accountId = 1;
        return $accountId;
    }

}

<?php

/**
 * Class LogForm
 * Log info
 */
class LogForm extends InitForm
{
    /**
     * @param $action
     * @param $message
     * @param $module
     * @param string $level
     * @param string $category
     */
    public static function log($action, $message, $module, $level = 'info', $category = 'application', $user = null)
    {
        LogForm::yiiLog($module . ": " . $message, $level, $category, $user);
        LogForm::dbLog($action, $message, $module, $level, $user);
    }

    /**
     * 调用yii默认log函数
     * @param string $message
     * @param string $level
     * @param string $category
     * @param string $user
     */
    static public function yiiLog($message, $level = 'info', $category = 'application', $user = null)
    {
        if ($user == null) {
            $user = Yii::app()->user->name;
        }
        $message = "[ $user ] " . $message;
        Yii::log($message, $level, $category);
    }

    /**
     * 添加操作日志
     * 存储到数据库中
     * @param string $action 操作的动作
     * @param string $message 日志信息
     * @param string $module 项目名称
     * @param $level
     * @param null $user
     * @return boolean 返回日志添加是否成功
     */
    static public function dbLog($action, $message, $module, $level, $user = null)
    {
        if ($user == null) {
            $user = Yii::app()->user->name;
        }

        // 构造日志参数
        $logParams = array(
            'module' => $module,
            'action' => $action,
            'info' => $message,
            'level' => $level,
            'user' => $user,
            'ip' => Yii::app()->request->getUserHostAddress(),
            'create_time' => date("Y-m-d H:i:s"),
        );

        // 将日志存入数据库
        try {
            $logRecord = new Log();

            $logRecord->attributes = $logParams;
            if ($logRecord->save()) {
                return true;
            } else {
                throw new Exception();
            }

        } catch (Exception $e) {
            Yii::log("Failed logging to db! " . $e->getMessage(), 'error');
            return false;
        }
    }


}

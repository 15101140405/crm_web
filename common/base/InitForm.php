<?php

/**
 * Class InitForm
 *
 */
class InitForm extends CFormModel
{
    public function init()
    {
    }


    /**
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            foreach ($values as $name => $val) {
                if (is_string($val)) {
                    $values[$name] = trim($val);
                }
            }
        }
        return parent::setAttributes($values, $safeOnly);
    }

    /**
     * auto trim all
     */
    public function beforeValidate()
    {
        if (is_array($this->attributes)) {
            foreach ($this->attributes as $name => $val) {
                if (is_string($val)) {
                    $this->$name = trim($val);
                }
            }
        }
        return parent::beforeValidate();
    }

    /**
     * 发送post请求P
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    public static function sendost($url, $post_data)
    {
        $data = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $data,
                'timeout' => 15 * 60
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

    /**
     * 将数组转成字符串
     * @param $array
     * @return string
     * {'title':{'0':'Title 不可为空白.'}}
     */
    public static function array2Json($array)
    {
        if (!is_array($array)) {
            return "'" . $array . "'";
        }

        $str = CJSON::encode($array);
        return $str;
    }

    public static function decodeJson($json)
    {
        $result = array();
        $data = json_decode($json);
        if (!$json || is_null($data)) {
            return $result;
        }

        return CJSON::decode($json);
    }

    /**
     * 自动解析编码字符串
     * @param string $str 字符串
     * @param string $charset 读取编码
     * @return string 返回读取内容
     */
    public static function autoConvert($str, $charset = 'UTF-8')
    {
        $list = array('GBK', 'UTF-8', 'UTF-16LE', 'UTF-16BE', 'ISO-8859-1');
        foreach ($list as $item) {
            $tmp = mb_convert_encoding($str, $item, $item);
            if (md5($tmp) == md5($str)) {
                return mb_convert_encoding($str, $charset, $item);
            }
        }
        return "";
    }

    public static function getRandChars($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];     //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    public static function mergerArrayWithKey($a, $b)
    {
        $c = array();
        foreach ($a as $key => $value) {
            $c[$key] = $value;
        }
        foreach ($b as $key => $value) {
            $c[$key] = $value;
        }

        return $c;
    }


}
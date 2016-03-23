<?php

/**
 * Class CurlUtil
 *
 */
class CurlUtil
{
    public static function get($strUrl, $arrParams, $timeout = null)
    {
        if (($curl = curl_init()) !== false) {
            $strNewUrl = $strUrl . '?' . http_build_query($arrParams);
            curl_setopt($curl, CURLOPT_URL, $strNewUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);

            if ($timeout) {
                curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
            }

            $strRes = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);
            return array('url' => $strNewUrl, 'status' => $status, 'result' => $strRes);

        } else {
            return false;
        }
    }

    public static function post($strUrl, $arrParams, $timeout = null)
    {
        if (($curl = curl_init()) !== false) {
            $strPostData = http_build_query($arrParams);
            curl_setopt($curl, CURLOPT_URL, $strUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $strPostData);

            if ($timeout) {
                curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
            }

            $strRes = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            return array('url' => $strUrl, 'status' => $status, 'result' => $strRes);
        } else {
            return false;
        }
    }

    public static function get_curl($strUrl, $arrParams)
    {
        if (($curl = curl_init()) !== false) {
            $strNewUrl = $strUrl . '?' . http_build_query($arrParams);
            curl_setopt($curl, CURLOPT_URL, $strNewUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            return $curl;
        } else {
            return false;
        }
    }

    public static function post_curl($strUrl, $arrParams)
    {
        if (($curl = curl_init()) !== false) {
            $strPostData = http_build_query($arrParams);
            curl_setopt($curl, CURLOPT_URL, $strUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $strPostData);
            return $curl;
        } else {
            return false;
        }
    }

}

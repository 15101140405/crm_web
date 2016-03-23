<?php

$appid="wxf29be229f32800a9";
$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http%3a%2f%2fcrm.batorange.com%2fweixin%2fcallback.php&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';

header("Location:".$url);

?>
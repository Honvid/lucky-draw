<?php
/**
 * @author Honvid
 * @time: 2017/3/31  下午7:37
 */
require '../../helper/JsonHelper.php';
class WeChat {

    const APP_ID = '';
    const SECRET = '';

    public static function redirect()
    {
        if(empty($_SESSION['open_id']) || !empty($_SESSION['username'])){
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.self::APP_ID.'&redirect_uri='.urldecode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'&response_type=code&scope=snsapi_userinfo&state=honvid#wechat_redirect';
            header("Location:".$url);
            exit();
        }
    }

    public static function getUserInfo($code)
    {
        $info = self::getAccessToken($code);
        if(empty($info)) {
            return [];
        }
        $_SESSION['open_id'] = $info['openid'];
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$info['access_token'].'&openid='.$info['openid'].'&lang=zh_CN';
        $user = self::httpGet($url);
        $user = json_decode($user, true);
        if(!empty($user) && isset($user['nickname'])) {
            $_SESSION['username'] = $user['nickname'];
            JsonHelper::update($info['openid'], $user, 'dinner_list');
            return $user;
        }
    }

    private static function getAccessToken($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.self::APP_ID.'&secret='.self::SECRET.'&code='.$code.'&grant_type=authorization_code';
        $result = self::httpGet($url);
        if($result){
            return json_decode($result, true);
        }
        return [];
    }


    private static function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
}
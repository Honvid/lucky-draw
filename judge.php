<?php
/**
 * @author Honvid
 * @time: 2017/3/31  下午5:35
 */
header('Content-Type:application/json; charset=utf-8');
require 'helper/JsonHelper.php';

$phone = [
    [
        'uid' => 1,
        'phone' => "147****1092",
        'photo' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=2639867671,3554518423&fm=58'
    ],[
        'uid' => 2,
        'phone' => "133****3762",
        'photo' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=2639867671,3554518423&fm=58'
    ],[
        'uid' => 3,
        'phone' => "137****1331",
        'photo' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=2639867671,3554518423&fm=58'
    ],[
        'uid' => 3,
        'phone' => "131****9823",
        'photo' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=2639867671,3554518423&fm=58'
    ],
];
$fen = isset($_POST['fen']) ? $_POST['fen'] : 0;
$type = isset($_POST['type']) ? $_POST['type'] : 0;
$number = isset($_POST['number']) ? $_POST['number'] : 0;

if(!empty($phone) && !empty($fen) && !empty($type) && !empty($number)) {
    $keys = array_rand($phone, $number);
    $list = [];
    if(is_array($keys)) {
        foreach ($keys as $key) {
            $list[] = $phone[$key];
        }
    }else{
        $list = $phone[$keys];
    }

    exit(json_encode(['code' => 200, 'msg' => '获取成功', 'data' => $list], JSON_UNESCAPED_UNICODE));
}else{
    exit(json_encode(['code' => 500, 'msg' => '参数错误', 'data' => []], JSON_UNESCAPED_UNICODE));
}
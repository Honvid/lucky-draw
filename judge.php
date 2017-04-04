<?php
/**
 * @author Honvid
 * @time: 2017/3/31  下午5:35
 */
header('Content-Type:application/json; charset=utf-8');
require 'require.php';

function getUser($type, $number, $prize, $name){
    $result = Data::getUserByType($type);
    if(empty($result)) {
        return [];
    }
    $total = count($result);
    if($number > $total - 5){
        exit(json_encode(['code' => 500, 'msg' => '数据不足，请稍后再抽奖！', 'data' => []], JSON_UNESCAPED_UNICODE));
    }
    $keys = getKeys($number, $total - 1);
    $list = [];
    if(is_array($keys)) {
        foreach ($keys as $key) {
            $list[] = $result[$key];
        }
    }else{
        $list = $result[$keys[0]];
    }
    if(!empty($list)) {
        return Data::getLuckyUsers($list, $prize, $type, $number, $name);
    }
    return [];
}


$type = isset($_POST['type']) ? $_POST['type'] : '';
$prize = isset($_POST['prize']) ? $_POST['prize'] : 0;
$name = isset($_POST['name']) ? $_POST['name'] : '';
$number = isset($_POST['number']) ? $_POST['number'] : 0;
if(!empty($prize) && !empty($type) && !empty($number) && !empty($name)) {
    $user = getUser($type, $number, $prize, $name);
    if(!empty($user)) {
        exit(json_encode(['code' => 200, 'msg' => '抽奖成功', 'data' => $user], JSON_UNESCAPED_UNICODE));
    }
}
exit(json_encode(['code' => 500, 'msg' => '数据不足，请稍后再抽奖！', 'data' => []], JSON_UNESCAPED_UNICODE));

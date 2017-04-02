<?php
/**
 * @author Honvid
 * @time: 2017/4/2  下午9:54
 */
header('Content-Type:application/json; charset=utf-8');
require 'require.php';

$action = isset($_POST['action']) ? $_POST['action'] : 1;
$type = isset($_POST['type']) ? $_POST['type'] : '';
$default = isset($_POST['default_type']) ? $_POST['default_type'] : '';
if($action == 1 && !empty($type)) {
    $place = Data::getPlace($type);
    if(!empty($place)) {
        exit(json_encode(['code' => 200, 'msg' => '获取成功', 'data' => $place], JSON_UNESCAPED_UNICODE));
    }
}
if($action == 2) {
    // 更新
    if(!empty($default)) {
        $result = Data::updatePlaces($_POST, $default);
    }else{
        $result = Data::updatePlaces($_POST);
    }
    if($result) {
        exit(json_encode(['code' => 200, 'msg' => '更新成功', 'data' => []], JSON_UNESCAPED_UNICODE));
    }
}
exit(json_encode(['code' => 500, 'msg' => '参数错误', 'data' => []], JSON_UNESCAPED_UNICODE));
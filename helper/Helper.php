<?php
/**
 * @author Honvid
 * @time: 2017/4/1  下午3:26
 */

function getKeys($number, $max)
{
    $result = array();
    while (count($result) < $number) {
        $result[] = mt_rand(1, $max);
        $result = array_flip(array_flip($result));
    }
    return $result;
}

function getPhone($number = 1000)
{
    $phone_pre = [139, 138, 137, 136, 186, 185, 135, 134, 147, 188, 187, 184, 183, 182, 159, 158, 157, 152, 151, 150, 145, 156, 155, 132, 131, 130, 189, 181, 180, 153, 133];
    $phone_list = [];
    for($i = 0; $i < $number; $i++) {
        $phone_list[] = $phone_pre[array_rand($phone_pre)] . '****' . rand(0, 9). rand(0, 9). rand(0, 9). rand(0, 9);
    }
    return '["'.implode('","', array_unique($phone_list)) . '"]';
}
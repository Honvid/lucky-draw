<?php
/**
 * @author Honvid
 * @time: 2017/4/1  下午3:20
 */

class Data
{
    const DB = 'h3c';
    const ROOM = 'h3c_meet_room';
    const USER = 'h3c_meet_user';
    const PRIZE = 'h3c_meet_prize';
    public static function getUserCountByType($type)
    {
        $db = new MySQL(self::DB);
        $sql = 'SELECT `uid` FROM `'.self::PRIZE.'` WHERE `whichPlate` = :type';
        $db->prepare($sql);
        $db->bind(':type', $type);
        $db->execute();
        $count = $db->getrowcount();
        $db->close();
        return $count;
    }

    public static function getPlace($type)
    {
        $db = new MySQL(self::DB);
        $sql = 'SELECT * FROM `'.self::ROOM.'` WHERE `type` = :type';
        $db->prepare($sql);
        $db->bind(':type', $type);
        $db->execute();
        $result = $db->getsingle();
        $db->close();
        return $result;
    }

    public static function updatePlaces($data, $type = null)
    {
        $db = new MySQL(self::DB);

        if($type) {
            $sql = 'UPDATE `'.self::ROOM.'` SET 
            `name` = "'.$data['name'].'", 
            `type` = "'.$data['type'].'", 
            `prize_one` = "'.$data['prize_one'].'",
            `prize_one_num` =  '.intval($data['prize_one_num']).',
            `prize_one_status` = '.intval($data['prize_one_status']).',
            `prize_two` = "'.$data['prize_two'].'",
            `prize_two_num` = '.intval($data['prize_two_num']).',
            `prize_two_status` = '.intval($data['prize_two_status']).',
            `prize_three` = "'.$data['prize_three'].'",
            `prize_three_num` = '.intval($data['prize_three_num']).',
            `prize_three_status` = '.intval($data['prize_three_status']).'
             WHERE `type` = "'.$type.'";';
        }else{
            $sql = 'INSERT INTO `'.self::ROOM.'` (`name`, `type`, prize_one, 
              prize_one_num, prize_one_status, prize_two, prize_two_num, prize_two_status, 
              prize_three, prize_three_num, prize_three_status, create_time) VALUE ("'.$data['name'].'", "'.$data['type'].'", "'.$data['prize_one'].'", 
              '.intval($data['prize_one_num']).', '.intval($data['prize_one_status']).',
               "'.$data['prize_two'].'", '.intval($data['prize_two_num']).', '.intval($data['prize_two_status']).', 
              "'.$data['prize_three'].'", '.intval($data['prize_three_num']).', '.intval($data['prize_three_status']).', '.time().')';
        }
        $db->prepare($sql);
        $db->execute();
        $result = $db->getrowcount();
        $db->close();
        return $result > 0;
    }

    public static function getPrizePerson($type)
    {
        $db = new MySQL(self::DB);
        if(empty($type)) {
            $sql = 'SELECT p.*, r.name, u.* FROM `'.self::PRIZE.'` as p 
              LEFT JOIN `'.self::ROOM.'` as r ON r.`type` = p.`whichPlate` 
              LEFT JOIN `'.self::USER.'` as u ON u.`id` = p.`uid`
              WHERE p.`prize_name` != "no";';
        }else{
            $sql = 'SELECT p.*, r.name, u.* FROM `'.self::PRIZE.'` as p 
              LEFT JOIN `'.self::ROOM.'` as r ON r.`type` = p.`whichPlate` 
              LEFT JOIN `'.self::USER.'` as u ON u.`id` = p.`uid`
              WHERE p.`whichPlate` = "'.$type.'" AND p.`prize_name` != "no";';
        }
        $db->prepare($sql);
        $db->execute();
        $result = $db->getall();
        $db->close();
        return $result;
    }

    public static function getPlaceList()
    {
        $db = new MySQL(self::DB);
        $sql = 'SELECT * FROM `'.self::ROOM.'`';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getall();
        $db->close();
        return $result;
    }

    public static function countPerson()
    {
        $db = new MySQL(self::DB);
        $sql = 'SELECT whichPlate, COUNT(whichPlate) as number FROM `'.self::PRIZE.'` GROUP BY `whichPlate`';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getall();
        $db->close();
        $list = [];
        if(!empty($result)) {
            foreach ($result as $e) {
                $list[$e['whichPlate']] = $e['number'];
            }
        }
        return $list;
    }

    public static function getUserByType($type)
    {
        $db = new MySQL(self::DB);
        $sql = 'SELECT `uid` FROM `'.self::PRIZE.'` WHERE `whichPlate` = :type AND `uid` NOT IN (
                      SELECT uid FROM `'.self::PRIZE.'` WHERE `prize_name` != "no")';
        $db->prepare($sql);
        $db->bind(':type', $type);
        $db->execute();
        $result = $db->getall();
        $db->close();
        return $result;
    }

    public static function clearPrize($type)
    {
        $db = new MySQL(self::DB);
        $sql = 'UPDATE `'.self::PRIZE.'` SET `prize_name` = "no" WHERE `whichPlate` = "'.$type.'";';

        $sql .= 'UPDATE `'.self::ROOM.'` SET `prize_one_status` = 0,`prize_two_status` = 0,`prize_three_status` = 0 WHERE `type` = "'.$type.'"';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getrowcount();
        $db->close();
        return $result;
    }

    public static function getLuckyUsers($id, $prize, $type, $number, $name)
    {
        $ids = '';
        foreach ($id as $l) {
            $ids .= ','.$l['uid'];
        }
        $ids = ltrim($ids, ',');
        $db = new MySQL(self::DB);
        $sql = 'SELECT * FROM `'.self::USER.'` WHERE `id` IN ('.$ids.');';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getall();
        $db->close();
        if(!empty($result) && self::updatePlace($type, $name) && self::updatePrize($ids, $prize, $number,$type, $name)) {
            return $result;
        }
        return [];
    }

    private static function updatePrize($ids, $prize, $number, $type, $name)
    {
        if($name == 'prize_one_status') {
            $title = '一等奖：'.$prize;
        }elseif($name == "prize_two_status"){
            $title = '二等奖：'.$prize;
        }else if ($name == 'prize_three_status'){
            $title = '三等奖：'.$prize;
        }else{
            $title = '未知';
        }
        $db = new MySQL(self::DB);
        $sql = 'UPDATE `'.self::PRIZE.'` SET `prize_name` = "'.$title.'", `get_time` = "'.date('Y-m-d H:i:s').'" WHERE `whichPlate` = "'.$type.'" AND  `uid` IN ('.$ids.');';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getrowcount();
        $db->close();
        return $result == $number;
    }

    public static function updatePrizeTime($id)
    {
        $db = new MySQL(self::DB);
        $sql = 'UPDATE `'.self::PRIZE.'` SET `accept_time` = "'.date('Y-m-d H:i:s').'" WHERE `id` = "'.$id.'"';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getrowcount();
        $db->close();
        return $result > 0;
    }

    private static function updatePlace($type, $name)
    {
        $db = new MySQL(self::DB);
        $sql = 'UPDATE `'.self::ROOM.'` SET `'.$name.'` = 1 WHERE `type` = "'.$type.'";';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getrowcount();
        $db->close();
        return $result > 0;
    }
}
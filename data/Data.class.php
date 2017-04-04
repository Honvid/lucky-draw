<?php
/**
 * @author Honvid
 * @time: 2017/4/1  下午3:20
 */

class Data
{
    const DB = 'h3c';

    public static function getUserCountByType($type)
    {
        $db = new MySQL(self::DB);
        $sql = 'SELECT `uid` FROM `h3c_meet_prize` WHERE `whichPlate` = :type';
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
        $sql = 'SELECT * FROM `h3c_meet_room` WHERE `type` = :type';
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
            $sql = 'UPDATE `h3c_meet_room` SET 
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
            $sql = 'INSERT INTO `h3c_meet_room` (`name`, `type`, prize_one, 
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
            $sql = 'SELECT p.*, r.name, u.* FROM `h3c_meet_prize` as p 
              LEFT JOIN `h3c_meet_room` as r ON r.`type` = p.`whichPlate` 
              LEFT JOIN `h3c_meet_user` as u ON u.`id` = p.`uid`
              WHERE p.`prize_name` != "no";';
        }else{
            $sql = 'SELECT p.*, r.name, u.* FROM `h3c_meet_prize` as p 
              LEFT JOIN `h3c_meet_room` as r ON r.`type` = p.`whichPlate` 
              LEFT JOIN `h3c_meet_user` as u ON u.`id` = p.`uid`
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
        $sql = 'SELECT * FROM `h3c_meet_room`';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getall();
        $db->close();
        return $result;
    }

    public static function countPerson()
    {
        $db = new MySQL(self::DB);
        $sql = 'SELECT whichPlate, COUNT(whichPlate) as number FROM `h3c_meet_prize` GROUP BY `whichPlate`';
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
        $sql = 'SELECT `uid` FROM `h3c_meet_prize` WHERE `whichPlate` = :type AND `uid` NOT IN (
                      SELECT uid FROM `h3c_meet_prize` WHERE `prize_name` != "no")';
        $db->prepare($sql);
        $db->bind(':type', $type);
        $db->execute();
        $result = $db->getall();
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
        $sql = 'SELECT * FROM `h3c_meet_user` WHERE `id` IN ('.$ids.');';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getall();
        $db->close();
        if(!empty($result) && self::updatePlace($type, $name) && self::updatePrize($ids, $prize, $number,$type)) {
            return $result;
        }
        return [];
    }

    private static function updatePrize($ids, $prize, $number, $type)
    {
        $db = new MySQL(self::DB);
        $sql = 'UPDATE `h3c_meet_prize` SET `prize_name` = "'.$prize.'", `get_time` = "'.date('Y-m-d H:i:s').'" WHERE `whichPlate` = "'.$type.'" AND  `uid` IN ('.$ids.');';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getrowcount();
        $db->close();
        return $result == $number;
    }

    public static function updatePrizeTime($id)
    {
        $db = new MySQL(self::DB);
        $sql = 'UPDATE `h3c_meet_prize` SET `accept_time` = "'.date('Y-m-d H:i:s').'" WHERE `id` = "'.$id.'"';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getrowcount();
        $db->close();
        return $result > 0;
    }

    private static function updatePlace($type, $name)
    {
        $db = new MySQL(self::DB);
        $sql = 'UPDATE `h3c_meet_room` SET `'.$name.'` = 1 WHERE `type` = "'.$type.'";';
        $db->prepare($sql);
        $db->execute();
        $result = $db->getrowcount();
        $db->close();
        return $result > 0;
    }
}
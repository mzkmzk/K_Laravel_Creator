<?php
/**
 * Created by PhpStorm.
 * User: maizhikun
 * Date: 16/2/28
 * Time: 下午10:45
 */
namespace K_Laravel_Creator\Entities;

class Base_Entity{

    public static function get_attribute(){
        $attribute = array();
        $attribute['id'] = self::set_attribute("序号","id");
        $attribute['created_at'] = self::set_attribute("创建时间","date_time");
        $attribute['updated_at'] = self::set_attribute("修改时间","date_time");
        $attribute['deleted_at'] = self::set_attribute("删除时间","date_time");
        return $attribute ;
    }

    /**
     * @param $front 前端显示字段名
     * @param $type 字段类型 "id" , "string" , "date_time" "url","int"
     * @param $option 选项
     * @param $length 字段长度
     * @return array
     */
    public static function  set_attribute($front , $type , $option = [] ,$length = 255){
        return $attribute = [
            "front" => $front,
            "type" => $type,
            "option" => $option,
            "length" => $length,
        ];
    }

}
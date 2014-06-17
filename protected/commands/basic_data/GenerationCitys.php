<?php
/**
 * 生成城市缓存
 *
 * @auth degang.shen
 */
class GenerationCitys
{
    /**
     * @var array $generation_field 自定义生成所需的字段
     */
    public static $generation_field = array();


    public static function generation()
    {
        $citys_db = Citys::model();
        var_dump($citys_db);die;
        $citys_info = $citys_db->findAll( array('select' => 'cityname_zh, cityname_en, citycode') );
        print_r($citys_info);
    }

}

<?php
namespace app\model;

use think\Model;

class Salon extends Model
{
    protected $type = ["images" => "array"];
    /**
     * 沙龙信息的增删改查
     * @param postArray 沙龙相关信息
     */
    public function salon($postArray)
    {
        switch ($postArray["type"]) {
            case "add":
                if (array_key_exists("id", $postArray))
                    unset($postArray["id"]);
                $s = new Salon($postArray);
                $s->save();
                break;
            case "delete":
                $s = Salon::get($postArray["id"]);
                $s->delete();
                break;
            case "update":
                $s = Salon::get($postArray["id"]);
                $s->save($postArray);
                break;
            case "get":
                $s = new Salon();
                return $s->select()->toArray();
        }
    }
}

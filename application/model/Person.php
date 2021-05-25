<?php
namespace app\model;

use think\Model;

class Person extends Model
{
    protected $type = [
        "images" => "array",
        "available_time" => "array"
    ];
    /**
     * 沙龙信息的增删改查
     * @param postArray 沙龙相关信息
     */
    public function person($postArray)
    {
        switch ($postArray["type"]) {
            case "add":
                if (array_key_exists("id", $postArray))
                    unset($postArray["id"]);
                $s = new Person($postArray);
                $s->save();
                break;
            case "delete":
                $s = Person::get($postArray["id"]);
                $s->delete();
                break;
            case "update":
                $s = Person::get($postArray["id"]);
                $s->save($postArray);
                break;
            case "get":
                $s = new Person();
                return $s->select()->toArray();
        }
    }
}

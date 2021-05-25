<?php
namespace app\model;

use think\Model;

class Speaker extends Model
{
    protected $type = [
        "images" => "array"
    ];
    /**
     * 讲师信息的增删改查
     * @param postArray 沙龙相关信息
     */
    public function speaker($postArray)
    {
        switch ($postArray["type"]) {
            case "add":
                if (array_key_exists("id", $postArray))
                    unset($postArray["id"]);
                $s = new Speaker($postArray);
                $s->save();
                break;
            case "delete":
                $s = Speaker::get($postArray["id"]);
                $s->delete();
                break;
            case "update":
                $s = Speaker::get($postArray["id"]);
                $s->save($postArray);
                break;
            case "get":
                $s = new Speaker();
                return $s->where("team_id", $postArray["team_id"])->select()->toArray();
        }
    }
}

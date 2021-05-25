<?php
namespace app\model;

use think\Model;
use app\model\Speaker as SpeakerModel;

class Team extends Model
{
    protected $table = "team";
    protected $type = [
        "available_time" => "array",
        "images" => "array"
    ];
    /**
     * 用于微党课小组的增删改查
     */
    public function team($postArray)
    {
        switch ($postArray["type"]) {
            case "add":
                if (array_key_exists("id", $postArray))
                    unset($postArray["id"]);
                $t = new team($postArray);
                $t->save();
                break;
            case "delete":
                $t = Team::get($postArray["id"]);
                SpeakerModel::where("team_id", $postArray["id"])->delete(true);
                $t->delete();
                break;
            case "update":
                $t = Team::get($postArray["id"]);
                $t->save($postArray);
                break;
            case "get":
                $t = new Team();
                return $t->select()->toArray();
        }
    }
}

<?php
namespace app\model;

use think\Model;
use app\model\Team as TeamModel;

class TeamOrder extends Model
{
    protected $pk = "order_id";
    protected $type = ["order_period" => "array"];
    /**
     * 用于存储预约信息
     * @param postArray 用户上传的预约信息
     */
    public function storeOrder(array $postArray)
    {
        $postArray['status'] = 0;
        $postArray['time'] = date('Y-m-d H:i:s');
        $po = new TeamOrder($postArray);
        $po->save();
    }
    /**
     * 获取已经被预约的时间段
     * @param date 
     */
    public function getTime($postArray)
    {
        $target = new TeamOrder();
        $result = $target->where("order_date", $postArray["date"])->where("team_id", $postArray["teamId"])->field("order_period")->select()->toArray();
        $orderPeriod = [];
        foreach ($result as $value) {
            $orderPeriod = array_merge($orderPeriod, $value["order_period"]);
        }
        $team = TeamModel::get($postArray["teamId"])->toArray();
        $availableTime = $team["available_time"];
        $availablePeriod = array();
        foreach($availableTime as $value){
            if($value["date"] == $postArray["date"])
                $availablePeriod = $value["time"];
        }
        $return = array_diff($availablePeriod,$orderPeriod);
        $return["order_period"] = $return;
        return $return;
    }
    /**
     * 用于更改某条预约信息的通过状态
     * @param id 用于唯一标识某一条预约信息
     * @param status 要更改的状态,-1表示未通过，1表示通过，0表示未审核
     */
    public function changeStatus($id, $status)
    {
        $target = TeamOrder::get($id);
        $target->status = $status;
        $target->save();
    }
    /**
     * 获得所有预约信息
     * @param postArray 
     */
    public function getOrder($postArray = array())
    {
        $target = new TeamOrder();
        if ($postArray != null) {
            $key = array_keys($postArray);
            $key = $key[0];
            $return = $target->where("$key", $postArray["$key"])->order(['time' => 'desc'])->select()->toArray();
        } else
            $return = $target->order(['time' => 'desc'])->select()->toArray();
        foreach ($return as &$value) {
            $team = TeamModel::get($value["team_id"])->toArray();
            $value["team_name"] = $team["name"];
        }
        unset($value);
        return $return;
    }
}

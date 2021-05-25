<?php
namespace app\model;

use think\Model;

class RoomOrder extends Model
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
        $target = new RoomOrder($postArray);
        $target->save();
    }
    /**
     * 用于更改某条预约信息的通过状态
     * @param orderId 用于唯一标识某一条预约信息
     * @param status 要更改的状态,-1表示未通过，1表示通过，0表示未审核
     */
    public function changeStatus($orderId, $status)
    {
        $target = RoomOrder::get($orderId);
        $target->status = $status;
        $target->save();
    }
    /**
     * 获取已经被预约的时间段
     * @param date 
     */
    public function getTime($postArray)
    {
        $target = new RoomOrder();
        $result = $target->where("order_date", $postArray["date"])->field("order_period")->select()->toArray();
        $return = [];
        foreach ($result as $value) {
            $return = array_merge($return, $value["order_period"]);
        }
        $return["order_period"] = $return;
        return $return;
    }
    /**
     * 获得所有预约信息
     * @param postArray
     */
    public function getOrder($postArray = array())
    {
        $target = new RoomOrder();
        if ($postArray != null){
            $key = array_keys($postArray);
            $key = $key[0];
            return $target->where("$key", $postArray["$key"])->order(['time' => 'desc'])->select()->toArray();
        }
        else
            return $target->order(['time' => 'desc'])->select()->toArray();
    }
}

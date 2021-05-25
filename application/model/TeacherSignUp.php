<?php
namespace app\model;

use think\Model;
use app\model\Salon as SalonModel;

class TeacherSignUp extends Model
{
    /**
     * 用于存储报名信息
     * @param id 辅导员工资号
     * @param name 辅导员姓名
     * @param salonId 沙龙编号
     * @param open_id
     */
    public function storeSignUp($id, $name, $salonId, $open_id)
    {
        $target = new TeacherSignUp();
        $salon = SalonModel::get($salonId);
        if ($salon == null)
            return 17;
        $count = $target->where("id", $id)->where("salon_id", $salonId)->count();
        if ($count >= 1)
            return 14;
        $target->id = $id;
        $target->salon_id = $salonId;
        if ($salon->count < $salon->capacity)
            $salon->count = $salon->count + 1;
        else
            return 16;
        $target->time = date('Y-m-d H:i:s');
        $target->teacher_name = $name;
        $target->open_id = $open_id;
        $target->save();
        $salon->save();
        return 200;
    }
    /**
     * 用于获取报名的辅导员名单
     * @param postArray 
     */
    public function getSignUp($postArray = array())
    {
        $target = new TeacherSignUp();
        if ($postArray != null) {
            $key = array_keys($postArray);
            $key = $key[0];
            $result = $target->where("$key", $postArray["$key"])->order(['time' => 'desc'])->select()->toArray();
        } else
            $result = $target->order(['time' => 'desc'])->select()->toArray();
        foreach ($result as &$value) {
            $salon = SalonModel::get($value["salon_id"])->toArray();
            $value["salon_name"] = $salon["title"];
        }
        unset($value);
        return $result;
    }
}

<?php
namespace app\controller;

use think\Controller;
use app\model\TeacherSignUp as TeacherSignUpModel;
use app\model\RoomOrder as RoomOrderModel;
use app\model\Salon as SalonModel;
use app\model\TeamOrder as TeamOrderModel;
use app\model\PersonOrder as PersonOrderModel;
use app\model\Speaker as SpeakerModel;
use app\model\Team as TeamModel;
use app\model\Person as PersonModel;
use think\facade\Request as Request;
use think\exception\PDOException;

class Admin extends Controller
{
    public function initialize()
    {
        if (session("?logIn") == false) {
            echo msg(false, 12);
            exit();
        }
        $this->TeacherSignUpModel = new TeacherSignUpModel;
        $this->RoomOrderModel = new RoomOrderModel;
        $this->TeamOrderModel = new TeamOrderModel;
        $this->PersonOrderModel = new PersonOrderModel;
        $this->SalonModel = new SalonModel;
        $this->SpeakerModel = new SpeakerModel;
        $this->TeamModel = new TeamModel;
        $this->PersonModel = new PersonModel;
    }
    /**
     * 审核预约
     * @param id 用于唯一标识某一条预约信息
     * @param status 要更改的状态,-1表示未通过，1表示通过，0表示未审核
     * @param type 审核房间预约信息或者教师预约信息
     */
    public function changeStatus()
    {
        $id = Request::post('id');
        $status = Request::post('status');
        $type = Request::post('type');
        if ($id == null || $status == null || $type == null) {
            return msg(false, 11);
        }
        try {
            if ($type == "room")
                $this->RoomOrderModel->changeStatus($id, $status);
            elseif ($type == "team")
                $this->TeamOrderModel->changeStatus($id, $status);
            elseif ($type = "person")
                $this->PersonOrderModel->changeStatus($id, $status);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
        return msg(true, 200);
    }
    /**
     * 获取预约信息 按时间降序
     * @param type 获取教室预约信息或者教师预约信息
     */
    public function getList()
    {
        $type = Request::post("type");
        if ($type == null) {
            return msg(false, 11);
        }
        try {
            switch ($type) {
                case "room":
                    $list = $this->RoomOrderModel->getOrder();
                    break;
                case "team":
                    $list = $this->TeamOrderModel->getOrder();
                    break;
                case "teacher":
                    $list = $this->TeacherSignUpModel->getSignUp();
                    break;
                case "person":
                    $list = $this->PersonOrderModel->getOrder();
            }
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
        return msg(true, 200, $list);
    }
    /**
     * 辅导员沙龙的增删改查
     * @param start_time 开始时间
     * @param end_time 结束时间
     * @param location 地点
     * @param speaker 主讲人
     * @param title 主题
     * @param images 图片
     * @param capacity 容量
     * @param type 表明该操作是新增，删除，修改还是查找
     * @param id 如果是删除或者修改，需要传入id
     */
    public function salon()
    {
        $postArray = Request::post();
        if (array_key_exists("images", $postArray))
            $postArray["images"] = json_decode($postArray["images"]);
        if (array_key_exists("type", $postArray) == false) {
            return msg(false, 11);
        }
        if (($postArray["type"] == "update" || $postArray["type"] == "delete") && array_key_exists("id", $postArray) == false)
            return msg(false, 15);
        try {
            if ($postArray["type"] == "get") {
                $list = $this->SalonModel->salon($postArray);
                return msg(true, 200, $list);
            } else {
                $this->SalonModel->salon($postArray);
                return msg(true, 200);
            }
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 微党课小组内讲师的增删改查
     * @param name 讲师姓名
     * @param team_id 讲师所属的队伍
     * @param description 讲师的描述
     * @param id 删除或者更新操作需要
     * @param type 表明该操作是新增，删除，修改还是查找
     */
    public function speaker()
    {
        $postArray = Request::post();
        if (array_key_exists("type", $postArray) == false) {
            return msg(false, 11);
        }
        if (($postArray["type"] == "update" || $postArray["type"] == "delete") && array_key_exists("id", $postArray) == false)
            return msg(false, 15);
        try {
            if ($postArray["type"] == "get") {
                $list = $this->SpeakerModel->speaker($postArray);
                return msg(true, 200, $list);
            } else {
                $this->SpeakerModel->speaker($postArray);
                return msg(true, 200);
            }
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 微党课小组的增删改查
     * @param description 小组的总体描述
     * @param id 删除或者更新操作需要
     * @param available_time 可预约时间
     * @param images 小组的图片
     * @param type 表明该操作是新增，删除，修改还是查找
     */
    public function team()
    {
        $postArray = Request::post();
        if (array_key_exists("images", $postArray))
            $postArray["images"] = json_decode($postArray["images"]);
        if (array_key_exists("available_time", $postArray))
            $postArray["available_time"] = json_decode($postArray["available_time"]);
        if (array_key_exists("type", $postArray) == false) {
            return msg(false, 11);
        }
        if (($postArray["type"] == "update" || $postArray["type"] == "delete") && array_key_exists("id", $postArray) == false) {
            return msg(false, 15);
        }
        try {
            if ($postArray["type"] == "get") {
                $list = $this->TeamModel->team($postArray);
                return msg(true, 200, $list);
            } else {
                $this->TeamModel->team($postArray);
                return msg(true, 200);
            }
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 讲师的增删改查
     * @param name 讲师姓名
     * @param images 讲师的照片，用json数组的格式存储base64形式的图片
     * @param description 讲师的描述
     * @param id 删除或者更新操作需要
     * @param available_time 可预约时间
     * @param type 表明该操作是新增，删除，修改还是查找
     */
    public function person()
    {
        $postArray = Request::post();
        if (array_key_exists("images", $postArray))
            $postArray["images"] = json_decode($postArray["images"]);
        if (array_key_exists("available_time", $postArray))
            $postArray["available_time"] = json_decode($postArray["available_time"]);
        if (array_key_exists("type", $postArray) == false)
            return msg(false, 11);
        if (($postArray["type"] == "update" || $postArray["type"] == "delete") && array_key_exists("id", $postArray) == false)
            return msg(false, 15);
        try {
            if ($postArray["type"] == "get") {
                $list = $this->PersonModel->person($postArray);
                return msg(true, 200, $list);
            } else {
                $this->PersonModel->person($postArray);
                return msg(true, 200);
            }
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 删除某一条预约
     * @param type 表示预约的类型
     * @param id 某一条预约信息的id
     */
    public function delete()
    {
        $type = Request::post("type");
        $id = Request::post("id");
        if ($type == null || $id == null)
            return msg(false, 11);
        try {
            switch ($type) {
                case "team":
                    $to = TeamOrderModel::get($id);
                    $to->delete();
                    break;
                case "person":
                    $po = PersonOrderModel::get($id);
                    $po->delete();
                    break;
                case "teacher_sign_up":
                    $tsu = TeacherSignUpModel::get($id);
                    $tsu->delete();
                    break;
                case "room":
                    $ro = RoomOrderModel::get($id);
                    $ro->delete();
            }
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
        return msg(true, 200);
    }
}

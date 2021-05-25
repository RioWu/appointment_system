<?php
namespace app\controller;

use think\Controller;
use think\facade\Request as Request;
use app\model\TeamOrder as TeamOrderModel;
use app\model\TeacherSignUp as TeacherSignUpModel;
use app\model\RoomOrder as RoomOrderModel;
use app\model\PersonOrder as PersonOrderModel;
use app\model\Speaker as SpeakerModel;
use app\model\Salon as SalonModel;
use app\model\Team as TeamModel;
use app\model\Person as PersonModel;

use think\exception\PDOException;

class User extends Controller
{
    public function initialize()
    {
        $this->TeamOrderModel = new TeamOrderModel;
        $this->RoomOrderModel = new RoomOrderModel;
        $this->PersonOrderModel = new PersonOrderModel;
        $this->TeacherSignUpModel = new TeacherSignUpModel;
        $this->SpeakerModel = new SpeakerModel;
        $this->SalonModel = new SalonModel;
        $this->TeamModel = new TeamModel;
        $this->PersonModel = new PersonModel;
    }
    /**
     * 预约
     * @param postArray 用户上传信息
     * @param type 表示预约教室还是预约教师
     */
    public function order()
    {
        $postArray = Request::post();
        $postArray["order_period"] = json_decode($postArray["order_period"]);
        try {
            if ($postArray["type"] == "team")
                $this->TeamOrderModel->storeOrder($postArray);
            elseif ($postArray["type"] == "room")
                $this->RoomOrderModel->storeOrder($postArray);
            elseif ($postArray["type"] == "person")
                $this->PersonOrderModel->storeOrder($postArray);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
	//$return = array("remark" => $postArray["remarks"]);
        //return msg(true, 200, $return);
       	return msg(true, 200);
    }
    /**
     * 辅导员报名
     * @param id 辅导员工资号
     * @param name 辅导员姓名
     * @param salonId 沙龙编号
     * @param open_id
     */
    public function signUp()
    {
        $id = Request::post('id');
        $name = Request::post('name');
        $salonId = Request::post("salonId");
        $open_id = Request::post("open_id");
        if ($id == null || $name == null || $salonId == null || $open_id == null) {
            return msg(false, 11);
        }
        try {
            $result = $this->TeacherSignUpModel->storeSignUp($id, $name, $salonId, $open_id);
            if ($result != 200)
                return msg(false, $result);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
        return msg(true, 200);
    }
    /**
     *  获得已经被预约的时间段
     * @param type 表示是预约讲师团、讲师还是教师
     * @param date 表示获取某一天的已被预约时间段
     */
    public function getTime()
    {
        $postArray = Request::post();
        try {
            if ($postArray["type"] != null || $postArray["date"] != null) {
                switch ($postArray["type"]) {
                    case "team":
                        $return = $this->TeamOrderModel->getTime($postArray);
                        break;
                    case "person":
                        $return = $this->PersonOrderModel->getTime($postArray);
                        break;
                    case "room":
                        $return = $this->RoomOrderModel->getTime($postArray);
                }
            }
            return msg(true, 200, $return);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 获取个人历史记录
     * @param open_id 用户的微信账户对应的id
     */
    public function getHistory()
    {
        $postArray = Request::post();
        if ($postArray == null) {
            return msg(false, 11);
        }
        $teamList = $this->TeamOrderModel->getOrder($postArray);
        $rList = $this->RoomOrderModel->getOrder($postArray);
        $pList = $this->PersonOrderModel->getOrder($postArray);
        $teacherList = $this->TeacherSignUpModel->getSignUp($postArray);
        $return = array(
            "TeamOrderHistory" => $teamList,
            "RoomOrderHistory" => $rList,
            "PersonOrderHistory" => $pList,
            "TeacherSignUpHistory" => $teacherList
        );
        $return = array_filter($return);        //去除为空的元素
        return msg(true, 200, $return);
    }
    /**
     * 获取所有讲师团中讲师信息
     */
    public function speaker()
    {
        $postArray = Request::post();
        $postArray["type"] = "get";
        try {
            $list = $this->SpeakerModel->speaker($postArray);
            return msg(true, 200, $list);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 获取所有沙龙信息
     */
    public function salon()
    {
        $postArray = Request::post();
        $postArray["type"] = "get";
        try {
            $list = $this->SalonModel->salon($postArray);
            return msg(true, 200, $list);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 获取所有讲师团信息
     */
    public function team()
    {
        $postArray = Request::post();
        $postArray["type"] = "get";
        try {
            $list = $this->TeamModel->team($postArray);
            return msg(true, 200, $list);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 获取所有讲师信息
     */
    public function person()
    {
        $postArray = Request::post();
        $postArray["type"] = "get";
        try {
            $list = $this->PersonModel->person($postArray);
            return msg(true, 200, $list);
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

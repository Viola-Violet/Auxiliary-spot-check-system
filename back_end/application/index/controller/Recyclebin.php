<?php

namespace app\index\controller;

use \think\Db;

class Recyclebin extends BaseController
{
    /**
     * 查看被删除的查寝记录
     */
    public function checkDeletedRecords()
    {
        // $parameter = ['grade', 'department'];
        // 输入判断
        if (empty($_POST['grade'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入年级！';
            return json($return_data);
        } else if (empty($_POST['department'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入系！';
            return json($return_data);
        }

        // 查询条件
        $where = array();
        $where['s.grade'] = $_POST['grade'];
        $where['s.department'] = $_POST['department'];
        $record = Db::table('record')
            ->field('start_time, end_time')   // 指定字段
            ->alias('r')    // 别名
            ->join('dorm d', 'd.id = r.dorm_id')
            ->join('student s', 's.id = d.student_id')
            ->distinct(true)   // 返回唯一不同的值
            ->where($where)
            ->where('r.deleted', 1)
            ->order('start_time desc')
            ->select();

        if ($record) {
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '显示查寝记录！';
            $return_data['data'] = $record;

            return json($return_data);
        } else {
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '暂无查寝记录！';

            return json($return_data);
        }
    }

    /**
     * 选择日期查看被删除的查寝记录
     */
    public function specifiedDeletedDate()
    {
        // $parameter = ['grade', 'department', 'date'];
        // 输入判断
        if (empty($_POST['grade'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入年级！';
            return json($return_data);
        } else if (empty($_POST['department'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入系！';
            return json($return_data);
        } else if (empty($_POST['date'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入时间！';
            return json($return_data);
        }

        $date = $_POST['date'];

        // 判断是否非法日期
        $is_date = strtotime($date) ? strtotime($date) : false;
        if ($is_date === false && $date != "") {
            $return_data = array();
            $return_data['error_code'] = 3;
            $return_data['msg'] = '日期格式非法！';

            return json($return_data);
        }

        // 查询条件
        $where = array();
        $where['s.grade'] = $_POST['grade'];
        $where['s.department'] = $_POST['department'];
        // dump($_POST['date']);
        $record = Db::table('record')
            ->field('start_time, end_time')   // 指定字段
            ->alias('r')    // 别名
            ->join('dorm d', 'd.id = r.dorm_id')
            ->join('student s', 's.id = d.student_id')
            ->distinct(true)   // 返回唯一不同的值
            ->where($where)
            ->where('start_time', 'between time', [$date . ' 00:00:00', $date . ' 23:59:59'])
            ->where('r.deleted', 1)
            ->select();

        if ($record) {
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '显示' . $date . '查寝记录！';
            $return_data['data'] = $record;

            return json($return_data);
        } else {
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '没有' . $date . '这天查寝记录！';

            return json($return_data);
        }
    }

    /**
     * 恢复查寝记录
     */
    public function recoverRecord()
    {
        // $parameter = ['department', 'grade', 'start_time', 'end_time'];
        // 输入判断
        if (empty($_POST['grade'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入年级！';
            return json($return_data);
        } else if (empty($_POST['department'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入系！';
            return json($return_data);
        } else if (empty($_POST['start_time'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入开始时间！';
            return json($return_data);
        } else if (empty($_POST['end_time'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入结束时间！';
            return json($return_data);
        }

        // 查询条件
        $where = array();
        $where['s.grade'] = $_POST['grade'];
        $where['s.department'] = $_POST['department'];
        $where['r.start_time'] = $_POST['start_time'];
        $where['r.end_time'] = $_POST['end_time'];

        $result = Db::table('record')
            ->alias('r')    // 别名
            ->join('dorm d', 'd.id = r.dorm_id')
            ->join('student s', 's.id = d.student_id')
            ->where($where)
            ->update(['record.deleted' => 0]);

        if ($result) {
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '删除查寝记录' . $result . '条';
            // $return_data['data'] = $result;

            return json($return_data);
        } else {
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '无此时间段的查寝记录';

            return json($return_data);
        }
    }

    /**
     * 查看被删除的详细
     */
    public function viewDeletedDetails()
    {
        // $parameter = ['grade', 'department', 'start_time'];
        // 输入判断
        if (empty($_POST['grade'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入年级！';
            return json($return_data);
        } else if (empty($_POST['department'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入系！';
            return json($return_data);
        } else if (empty($_POST['start_time'])) {
            $return_data = array();
            $return_data['error_code'] = 1;
            $return_data['msg'] = '请输入开始时间！';
            return json($return_data);
        }

        // 查询条件
        $where = array();
        $where['s.grade'] = $_POST['grade'];
        $where['s.department'] = $_POST['department'];
        $where['r.start_time'] = $_POST['start_time'];
        $record = Db::table('record')
            ->field('start_time, end_time, photo, d.dorm_num, r.rand_num')   // 指定字段
            ->alias('r')    // 别名
            ->join('dorm d', 'd.id = r.dorm_id')
            ->join('student s', 's.id = d.student_id')
            ->where($where)
            ->where('r.deleted', 1)
            ->select();

        if ($record) {
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '显示查寝记录';
            $return_data['data'] = $record;

            return json($return_data);
        } else {
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '无该天的查寝记录';

            return json($return_data);
        }
    }
}
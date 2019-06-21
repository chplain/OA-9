<?php
// +----------------------------------------------------------------------
// | Author: chenlei <714753756@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
// use User\Api\UserApi as UserApi;

/**
 * 后台OA控制器
 * @author chenlei <714753756@qq.com>
 */

/*项目售后服务记录管理*/
class PjserviceController extends AdminController {

    /*项目售后服务记录列表*/
    public function index(){
      
      $map['cp.project_name'] = array('like', '%' . (string)I('project_name') . '%');
      $map['wi.status'] = array('like', '%' . (string)$key2 . '%');
      $map['cp.purchase_intention'] = array('like', '%' . (string)I('purchase_intention') . '%');
      $map['cp.project_type'] = I('project_type');
      $map['cp.status'] = I('status');
      $map['pa.executor'] = I('executor');
      $map['ph.products'] = I('products');
      $map['_logic'] = 'or';
    
      $res = M("cst_pj_service as wi")
                    ->join('left join oa_cst_cti_project as pr on pr.id = wi.project_id')
                    ->join('left join oa_cst_pj_payreminder_rd as pa on wi.project_id = pa.project_id')
                    ->join('left join oa_cst_pj_plan_phases as ph on ph.id = pa.plan_id and pa.phases = ph.phases')
                    ->where($map)
                    ->order('cp.create_time desc')
                    ->page($pageindex, $pagesize)
                    ->select();
      var_dump($res);
      // var_dump(M()->getLastSql());
      $this->display();
    }

    /*项目售后服务记录添加*/
    public function add(){
        /*获取最大uid*/
        $maxid = M("cst_pj_service")
                    ->field('id')
                    ->order('id desc')
                    ->find();
        $data['record_code'] = "SH".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0   
        $data['service_type'] = I('service_type');
        $data['project_id'] = I('project_id');
        $data['status'] = 0;
        
        $data['create_person'] = UID;
        $data['create_time'] = date("Y-m-d H:i:s",time());
        $res = M("cst_pj_service")->add($data);

        if($res){

            /*添加项目问题*/
            foreach ($variable as $key => $value) {
                
                $data1['record_code'] = "SH".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0   
                $data1['service_type'] = I('service_type');
                $data1['project_id'] = I('project_id');
                $data1['status'] = 0;
                $data1['create_person'] = UID;
                $data1['create_time'] = date("Y-m-d H:i:s",time());
                $res1 = M("cst_pj_service_que")->add($data1);
            }

            /*添加问题处理记录*/
            foreach ($variable as $key => $value) {
                
                $data2['record_code'] = "SH".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0   
                $data2['service_type'] = I('service_type');
                $data2['project_id'] = I('project_id');
                $data2['status'] = 0;
                $data2['create_person'] = UID;
                $data2['create_time'] = date("Y-m-d H:i:s",time());
                $res2 = M("cst_pj_service_deal")->add($data2);
            }

            /*添加问题回访记录*/
            foreach ($variable as $key => $value) {
                
                $data3['record_code'] = "SH".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0   
                $data3['service_type'] = I('service_type');
                $data3['project_id'] = I('project_id');
                $data3['status'] = 0;
                $data3['create_person'] = UID;
                $data3['create_time'] = date("Y-m-d H:i:s",time());
                $res3 = M("cst_pj_service_callback")->add($data3);
            }
        }

    }

 
}

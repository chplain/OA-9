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

/*财务收款管理*/
class WithdrawController extends AdminController {

    /*收款列表首页*/
    public function index(){
        /*获取分页*/
        $pageindex['p'] = $_GET["p"];
          if (empty($pageindex['p'])||$pageindex['p']=="0") {
              $pageindex['p']=1;
          }
        $pagesize = PAGESIZE;

        // $map['cp.project_name'] = array('like', '%' . (string)I('project_name') . '%');
        // $map['wi.status'] = array('like', '%' . (string)$key2 . '%');
        // $map['cp.purchase_intention'] = array('like', '%' . (string)I('purchase_intention') . '%');
        // $map['cp.project_type'] = I('project_type');
        // $map['cp.status'] = I('status');
        // $map['pa.executor'] = I('executor');
        // $map['ph.products'] = I('products');
        // $map['_logic'] = 'or';
    
        if(isset($_GET['project_name']) ){
          $map['pr.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
        }
        
        if(isset($_GET['prompt_id']) ){
          $map['pt.prompt_id']   =  $_GET['prompt_id'];
        }
      
        if(isset($_GET['status'])){
          $map['wi.status']  =   $_GET['status'];
        }else{
          $map['wi.status']  =   array('in', '0,1,2,3');
        }

        $withdraws = M("cst_fd_withdraw as wi")
                        ->field('wi.id,wi.remoney_code,wi.status,pr.project_name,wi.contract_code,co.contract_fee,wi.plan_code,wi.phases,ph.rtdate,ph.rmoney,me.nickname as create_person,wi.create_time')
                        ->join('left join oa_cst_contract as co on wi.contract_code = co.contract_code')
                        ->join('left join oa_cst_cti_project as pr on pr.id = wi.project_id')
                        ->join('left join oa_cst_pj_plan_phases as ph on ph.plan_code = wi.plan_code and ph.phases = wi.phases')
                        ->join('left join oa_member as me on me.uid = wi.create_person')
                        ->where($map)
                        ->order('wi.create_time desc')
                        ->page($pageindex['p'], $pagesize)
                        ->select();
        // $withdraws = M("cst_fd_withdraw") ->select();
        // var_dump($withdraws);
        // var_dump(M()->getLastSql());
        $this ->assign('withdraws',$withdraws);

        $count=M("cst_fd_withdraw as wi")
                    ->join('left join oa_cst_contract as co on wi.contract_code = co.contract_code')
                    ->join('left join oa_cst_cti_project as pr on pr.id = wi.project_id')
                    ->join('left join oa_cst_pj_plan_phases as ph on ph.plan_code = wi.plan_code and ph.phases = wi.phases')
                    ->join('left join oa_member as me on me.uid = wi.create_person')
                    ->where($map)
                    ->count();
        // var_dump(M()->getLastSql());
        if($count > $pagesize) {
            $page = new \COM\Page($count, $pagesize,$pageindex);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }

        $res = M('cst_pj_payreminder_rd')
                        ->field('')
                        ->join()
                        ->where()
                        ->select();


        $this->display();
    }

    /*收款添加*/
    public function add(){
        if(IS_POST){
            /*获取最大uid*/
            $maxid = M("cst_fd_withdraw")
                        ->field('id')
                        ->order('id desc')
                        ->find();
            $data['remoney_code'] = "FK".date('Ymd',time()).sprintf("%02d", $maxid['id']+1);//生成4位数，不足前面补0   
            $data['project_id'] = I('project_id');
            $data['phases'] = I('phases');
            $data['need_pay'] = I('need_pay');
            $data['payment_received'] = I('payment_received');
            $data['discribe'] = I('discribe');
            $data['remark'] = I('remark');
            $data['create_person'] = UID;
            $data['create_time'] = date('Y-m-d',time(0));
            
            $data['create_person'] = UID;
            $data['create_time'] = date("Y-m-d H:i:s",time());

            // var_dump(I());
            // die();
            $res = M("cst_fd_withdraw")->add($data);
            if($res){
                $this->success('添加成功！',U('index'));
            }else {
                $this->error('添加失败！');
            }

        }else{
            /*拉去项目*/
            $projects = M('cst_cti_project') ->field('id,project_name')->select();
            // var_dump($projects);
            $this ->assign('projects',$projects);
            $this ->display();
        }
        


    }

    /*ajax通过项目id获取实施计划收款阶段*/
    public function get_phases(){
        $map['ph.project_id'] = I('project_id');
        $map['ph.IsPayPhases'] = '1';

        $phases = M("cst_pj_plan_phases as ph") 
                    ->field('ph.phases,ph.PhasesFee') 
                    ->where($map)
                    ->select();
        

        // $this ->ajaxReturn(M()->getLastSql());
        $this ->ajaxReturn($phases);

    }

    /*ajax通过项目id获取实施计划收款阶段*/
    public function get_need_pay(){
        $map['ph.phases'] = I('phases');
        $map['ph.project_id'] = I('project_id');

        $phases = M("cst_pj_plan_phases as ph") 
                    ->field('ph.PhasesFee') 
                    ->where($map)
                    ->find();
        

        // $this ->ajaxReturn(M()->getLastSql());
        $this ->ajaxReturn($phases);

    }

    /*收款删除*/
    public function delete(){
        /*获取删除数组*/
        $idArray = I('id');
        // $uidArray = array(4);
        $map['id'] = array('in', $idArray);
        $res = M("cst_fd_withdraw")->where($map)->delete();
        var_dump(M()->getLastSql());
   
    }

    /*财务收款确认*/
    public function fd_sure(){
        /*获取删除合同数组*/
        $idArray = I('ids');
        // $uidArray = array(4);
        $map['id'] = array('in', $idArray);
        $data['status'] = '2';
        $res = M("cst_fd_withdraw")->where($map)->save($data);

        

        $res1 = M("cst_fd_withdraw")->field('plan_code,phases')->where($map)->select();

        foreach ($res1 as $key => $value) {
          $map1['plan_code'] = $value['plan_code'];
          $map1['phases'] = $value['phases'];
          $data1['status'] = '2';
          $res2 = M("cst_pj_payreminder_rd")->where($map1)->save($data1);
        }
        // var_dump($res1);
        // die();
        if($res){
            action_log('withdraw_sure','withdraw',UID,UID);
            $this->success('确认成功！',U('index'));
        }else {
                $this->error('确认失败！');
        }
        // var_dump(M()->getLastSql());
    }


    /*收款作废*/
    public function tail_list(){
        /*获取数组*/
        $idArray = I('id');
        // $uidArray = array(4);
        $map['id'] = array('in', $idArray);
        $data['status'] = 2;
        $res = M("cst_fd_withdraw")->where($map)->delete();
    }


    /*项目开票记录列表*/
    public function invoice(){
        /*获取分页*/
        $pageindex['p'] = $_GET["p"];
          if (empty($pageindex['p'])||$pageindex['p']=="0") {
              $pageindex['p']=1;
          }
        $pagesize = PAGESIZE;

        if(isset($_GET['project_name']) ){
          $map['pr.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
        }
        if(isset($_GET['province']) ){
            $map['pr.province']   =   array('like', '%'.$_GET['province'].'%');
        }
        if(isset($_GET['transfer_code']) ){
            $map['ce.complete_code']   =   array('like', '%'.$_GET['transfer_code'].'%');
        }

        // var_dump($map);
        $invoices = M("cst_fd_invoice as ce")
                    ->field('ce.updata_person,ce.updata_time,ce.id,pr.project_name,ce.Isinvoice,ce.downpayment,ce.complete_code,pr.province,co.contract_fee,ce.phases,ph.PhasesFee')
                    ->join('left join oa_cst_cti_project as pr on ce.project_id = pr.id')
                    ->join('left join oa_cst_pj_plan as pl on ce.plan_code = pl.plan_code')
                    ->join('left join oa_cst_contract as co on co.contract_code = pl.contract_code')
                    ->join('left join oa_cst_pj_plan_phases as ph on ph.plan_code = ce.plan_code and ph.phases = ce.phases')
                    ->where($map)
                    ->page($pageindex['p'], $pagesize)
                    ->select();
        // var_dump($invoices);
        // var_dump(M()->getLastSql());
        // die();
        $this ->assign('invoices',$invoices);

        $count=M("cst_fd_invoice as ce")
                    ->field('ce.id,pr.project_name,ce.Isinvoice,ce.downpayment,ce.complete_code,pr.province,co.contract_fee,ce.phases,ph.PhasesFee')
                    ->join('left join oa_cst_cti_project as pr on ce.project_id = pr.id')
                    ->join('left join oa_cst_pj_plan as pl on ce.plan_code = pl.plan_code')
                    ->join('left join oa_cst_contract as co on co.contract_code = pl.contract_code')
                    ->join('left join oa_cst_pj_plan_phases as ph on ph.plan_code = ce.plan_code and ph.phases = ce.phases')
                    ->where($map)
                    ->count();
        // var_dump(M()->getLastSql());
        if($count > $pagesize) {
            $page = new \COM\Page($count, $pagesize,$pageindex);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }

        $this->display();
    }

    /*新增项目开票记录*/
    public function invoice_add(){
        if(IS_POST){

            $data['project_id'] = I('project_id');
            $data['downpayment'] = I('downpayment');
            $data['remark'] = I('remark');
            $res = M('cst_fd_invoice') ->add($data);
            if($res){
                action_log('withdraw_invoice_add','withdraw',UID,UID);
                $this->success('添加成功！',U('invoice'));
            }else {
                $this->error('添加失败！');
            }
          
        }else{
            /*拉去项目*/
            $projects = M('cst_cti_project') ->field('id,project_name')->select();
            foreach ($projects as $key => $value) {
              $tmap['project_id'] = $value['id'];
              $tr = M('cst_ctp_transfer') ->where($tmap) ->find();
              if(!$tr){
                unset($projects[$key]);
              }
            }
            // var_dump($projects);
            $this ->assign('projects',$projects);
            $this ->display();
        }
        
    }

    /*ajax通过项目id获取项目合同详情*/
    public function get_projectmess(){
        $map['pr.id'] = I('project_id');

        $project = M("cst_cti_project as pr")
                    ->field('pr.project_name,pr.province,cu.customer') 
                    // ->join('oa_cst_contract as co on co.project_id = pr.id')
                    ->join('oa_cst_customer as cu on cu.id = pr.customer')
                    ->where($map)
                    ->find();
        $map1['co.project_id'] = I('project_id');
        $contract = M("cst_contract as co")
                        ->field('co.id,co.contract_code')
                        ->where($map1)
                        ->select();
        $project['contract'] = $contract;         
        // var_dump(M()->getLastSql());

        // $this ->ajaxReturn(M()->getLastSql());
        $this ->ajaxReturn($project);

    }

    /*ajax通过合同编号获取合同金额详情*/
    public function getContractFee(){
        $map['co.contract_code'] = I('contract_code');

        $contract = M("cst_contract as co")
                    ->field('co.contract_fee') 
                    ->where($map)
                    ->find();

        // $this ->ajaxReturn(M()->getLastSql());
        $this ->ajaxReturn($contract);

    }

    /*开票动作*/
    public function doinvoice(){

        $map1['uid'] = UID;
        $name = M('member')->field('nickname')->where($map1)->find();
        /*获取删除合同数组*/
        $idArray = I('ids');
        // $uidArray = array(4);
        $map['id'] = array('in', $idArray);
        $data['updata_person'] = $name['nickname'];
        $data['updata_time'] = date("Y-m-d H:i:s",time());
        $data['Isinvoice'] = '1';
        // var_dump(M()->getLastSql());
        // die();
        $res = M("cst_fd_invoice")->where($map)->save($data);
        if($res){
            action_log('withdraw_doinvoice','withdraw',UID,UID);
            $this->success('开票成功！',U('invoice'));
        }else {
                $this->error('开票失败！');
        }
    }

}

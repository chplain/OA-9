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

/*实施管理控制器*/
class TechController extends AdminController {

    /*默认首页*/
    public function first(){
      $this ->display();
    }

    /*意向合同首页*/
    public function index(){

      // $to = "somebody@example.com";
      // $subject = "My subject";
      // $txt = "Hello world!";
      // $headers = "From: 714753756@qq.com" . "\r\n" .
      // "CC: 714753756@qq.com";

      // mail($to,$subject,$txt,$headers);
      // var_dump(mail());

      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;
      

      if(isset($_GET['customer']) ){
          $map['oc.customer']   =   array('like', '%'.$_GET['customer'].'%');
      }
      if(isset($_GET['project_name']) ){
          $map['p.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['province']) ){
          $map['p.province']   =   array('like', '%'.$_GET['province'].'%');
      }
     
      if(isset($_GET['status'])){
          $map['ppl.status']  =   $_GET['status'];
      }else{
           $map['ppl.status']  =   array('in', '0,1,2,3');
      }

      // var_dump($map);
      $plans = M("cst_pj_plan as ppl")
                    ->field('ppl.contract_code,ppl.id,ppl.plan_code,ppl.status,oc.customer,p.project_name,ppl.products,ppl.project_money,p.province,ppl. sales_charge_person,ppl.tech_charge_person,meme.nickname as create_person,ppl.create_time,ppl.update_person,ppl.update_time')
                    ->join('left join oa_cst_cti_project as p on p.id = ppl.project_id')
                    ->join('left join oa_cst_customer as oc on p.customer = oc.id')
                    ->join('left join oa_member as me on me.uid = ppl.sales_charge_person')
                    ->join('left join oa_member as mem on mem.uid = ppl.tech_charge_person')
                    ->join('left join oa_member as meme on meme.uid = ppl.create_person')
                    // ->join('left join oa_member as mem on mem.uid = tr.create_person')
                    ->where($map)
                    ->order('create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      $this->assign('plans',$plans);             
      // var_dump($plans);
      // var_dump(M()->getLastSql());
      // die();
      $count=M("cst_pj_plan as ppl")
                ->join('left join oa_cst_cti_project as p on p.id = ppl.project_id')
                ->join('left join oa_cst_customer as oc on p.customer = oc.id')
                ->where($map)
                ->count();
      // var_dump(M()->getLastSql());
      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }

      
      $this ->display();
     
    }

    /*添加实施计划*/
    public function add(){

      if(!IS_POST){
        /*拉取客户*/
        $c = M("cst_customer as cu")
                ->distinct('true')
                ->field('cu.id,cu.customer')
                ->join('right join oa_cst_cti_project as p on p.customer = cu.id')
                ->join('right join oa_cst_ctp_transfer as tr on tr.project_id = p.id')
                ->select();
        // var_dump($c );
        /*拉取项目*/
        $p = M("cst_product") ->field('id,product_name')->order('product_name asc')->select();
        $this->assign('customers',$c);
        $this->assign('products',$p);
       
        /*拉去实施人员*/
        $map1['ac.group_id'] = 3;
        $techs = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();
        $this->assign('techs',$techs);
        $this->display();
      }else{
        $pmap['contract_code'] = I('contract_code');
        $prr = M('cst_pj_plan')->where($pmap)->find();
        if($prr){
              $this->error('添加失败！该合同计划已经录入！！');
        }
        /*实施计划表添加*/
        $maxid = M("cst_pj_plan")
                    ->field('id')
                    ->order('id desc')
                    ->find();
        $data['plan_code'] = "SS".date("Ymd",time()).sprintf("%02d", $maxid['id']+1);//生成2位数，不足前面补0 
        $data['project_id'] = I('project_id');
        $data['contract_code'] = I('contract_code');
        $data['products'] = I('contract_productlist');
        $data['sales_charge_person'] = I('salesRole'); 
        $data['tech_charge_person'] = I('TechRole'); 
        // $data['start_time'] = I('start_time');
        // $data['end_time'] = I('end_time'); 
        $data['project_money'] = I('contract_fee'); 
        $data['create_person'] = UID; 
        $data['create_time'] = date("Y-m-d h:m:s",time());
        $data['remark'] = I('remark'); 

        /* 调用文件上传组件上传文件 */
        $File = D('File');
        $info = $File->upload($_FILES, C('DOWNLOAD_UPLOAD')); //TODO:上传到远程服务器
        if($info){
          $data['accessory'] = "http://oa.gdwstech.com/Uploads/Download/".$info['accessory']['savepath'].$info['accessory']['savename'];
        }
        // var_dump($data);
        // die();
        $res = M('cst_pj_plan') ->add($data);

        /*如果添加成功，再循环添加实施计划周期*/
        if($res){
          /*项目实施计划周期表添加*/
          $phases = I('data');
          foreach ($phases as $key => $value) {
            $data1[$key]['project_id'] = I('project_id');
            $data1[$key]['plan_code'] = $data['plan_code'];
            $data1[$key]['contract_code'] = $data['contract_code'];
            $data1[$key]['phases'] = $key+1;
            $data1[$key]['products'] = implode(",", $value['products']);
            if($value['stime']){
              $data1[$key]['stime'] = $value['stime'];
            }
            if($value['etime']){
              $data1[$key]['etime'] = $value['etime'];
            }
            if($value['rtdate']){
              $data1[$key]['rtdate'] = $value['rtdate'];
            }
            $data1[$key]['IsPayPhases'] = $value['IsPayPhases'];
            $data1[$key]['PhasesFee'] = $value['PhasesFee'];
            $data1[$key]['rpercent'] = $value['rpercent'];
            $data1[$key]['rmoney'] = $value['rmoney'];
            $data1[$key]['executor'] = implode(',',$value['executor']);
            $data1[$key]['remark'] = $value['remark'];
            $res2 = M('cst_pj_plan_phases') ->add($data1[$key]);
          }

          if($res||$res2){
              action_log('tech_add','tech',UID,UID);
              $this->success('添加成功！',U('index'));
          }else {
              $this->error('添加失败！');
          }

        }


        // $this->display();
      }
   
       
    }

    /*ajax根据客户id获取该客户交接的项目*/
    public function get_transferProject(){
        $map['pr.customer'] = I('customer');
        // $map['tr.status'] = 2;
        $projects = M("cst_cti_project as pr") 
                    // ->distinct('true')
                    ->field('pr.id,pr.project_name') 
                    // ->join('left join oa_cst_ctp_transfer as tr on tr.project_id = pr.id')
                    ->where($map)
                    ->select();
        // foreach ($projects as $key => $value) {
        //             $pro  = explode(",",$value['project_productlist']);
        //             $map1['id'] = array('in', $pro);
        //             $product = M('cst_product') ->field('id,product_name')->where($map1)->select();
        //             foreach ($product as $key1 => $value1) {
        //               $data[] = $value1['product_name'];
        //             }
        //             $projects[$key]['project_productlist'] = implode(',',$data);
        //         }            
        $this ->ajaxReturn($projects);
    }

    /*Ajax通过商业地产获取合同列表*/
    public function getContractCode(){
        $map['co.project_id'] = I('project_id');
        $map['co.status'] = 3;
        $contracts = M("cst_contract as co")
                    ->field('co.contract_code') 
                    ->where($map)
                    ->select();
        // foreach ($projects as $key => $value) {
        //             $pro  = explode(",",$value['project_productlist']);
        //             $map1['id'] = array('in', $pro);
        //             $product = M('cst_product') ->field('id,product_name')->where($map1)->select();
        //             foreach ($product as $key1 => $value1) {
        //               $data[] = $value1['product_name'];
        //             }
        //             $projects[$key]['project_productlist'] = implode(',',$data);
        //         }            
        $this ->ajaxReturn($contracts);
    }



    /*ajax根据交接的项目获取详情*/
    public function get_transferProjectMess(){
        $map['tr.contract_code'] = I('contract_code');
        $res = M('cst_ctp_transfer as tr')
                    ->field('tr.accessory,tr.project_productlist,co.contract_fee,pr.province,tr.EnterTime,tr.OpenTime,tr.keyCustom,tr.link_phone,me.nickname as charge_person,tr.ProjectRishDescribe,tr.CustomerITNow,tr.TechRole,tr.SalesRole,tr.DevRole,tr.ProductRole,tr.ProjectRishLevel,tr.CustomerExpectLevel,tr.Project_opponents,tr.IsSI,tr.remark')
                    ->join('left join oa_cst_contract as co on co.contract_code = tr.contract_code')
                    ->join('left join oa_cst_cti_project as pr on pr.id = tr.project_id')
                    ->join('left join oa_member as me on me.uid = co.charge_person')
                    ->where($map)
                    ->find();
        // $this ->ajaxReturn(M()->getLastSql());            
        // $pro  = explode(",",$res['project_productlist']);
        // $map1['id'] = array('in', $pro);
        // $product = M('cst_product') ->field('id,product_name')->where($map1)->select();
        // foreach ($product as $key1 => $value1) {
        //   $data[] = $value1['product_name'];
        // }
        // $res['project_productlist'] = implode(',',$data);

        $this ->ajaxReturn($res);
        // $this ->ajaxReturn(M()->getLastSql());
    }



    /*实施计划生效*/
    public function tech_sure(){
      /*获取删除合同数组*/
      $idArray = I('ids');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);

      $data['status'] = '1';
      $res = M('cst_pj_plan') ->where($map)->save($data);

      if($res){
          action_log('tech_effect','tech',UID,UID);
          $this->success('生效成功！',U('index'));
      }else {
          $this->error('生效失败！');
      }
    }


    /*实施计划终止*/
    public function termination(){
      /*获取删除合同数组*/
      $idArray = I('ids');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      
      /*状态转化为终止*/
      $data['status'] = '3';
     
      $res = M("cst_pj_plan")->where($map)->save($data);

      if($res){
          action_log('tech_cancel','tech',UID,UID);
          $this->success('终止成功！',U('index'));
      }else {
          $this->error('终止失败！');
      }
    }

    /*实施计划详情*/
    public function plan_mess(){
      if(IS_POST){
        $map['contract_code'] = I('contract_code');
        $res1 = M('cst_pj_plan_phases') ->where($map)->delete();
        // var_dump(M()->getLastSql());
        // die();
        $phases = I('data');

        foreach ($phases as $key => $value) {
          $data1[$key]['project_id'] = I('project_id');
          $data1[$key]['plan_code'] = I('plan_code');
          $data1[$key]['contract_code'] = I('contract_code');
          $data1[$key]['phases'] = $key+1;
          $data1[$key]['products'] = implode(",", $value['products']);
          if($value['stime']){
            $data1[$key]['stime'] = $value['stime'];
          }
          if($value['etime']){
            $data1[$key]['etime'] = $value['etime'];
          }
          if($value['rtdate']){
            $data1[$key]['rtdate'] = $value['rtdate'];
          }
          $data1[$key]['IsPayPhases'] = $value['IsPayPhases'];
          $data1[$key]['PhasesFee'] = $value['PhasesFee'];
          $data1[$key]['rpercent'] = $value['rpercent'];
          $data1[$key]['rmoney'] = $value['rmoney'];
          $data1[$key]['executor'] = implode(',',$value['executor']);
          $data1[$key]['remark'] = $value['remark'];
          $res2 = M('cst_pj_plan_phases') ->add($data1[$key]);
        }

        if($res1||$res2){
            action_log('tech_up','tech',UID,UID);
            $this->success('更新成功！',U('index'));
        }else {
            $this->error('更新失败！');
        }

      }else{
        /*拉去产品*/
        $p = M("cst_product") ->field('id,product_name')->select();
        $this->assign('products',$p);
       
        /*拉去实施人员*/
        $map12['ac.group_id'] = 3;
        $techs = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map12)
                ->select();
        // var_dump($techs);
        $this->assign('techs',$techs);


        $map['pl.id'] = I('id');
        $plan = M('cst_pj_plan as pl')
                      ->field('pl.remark as rem,pl.accessory,pl.id,pl.plan_code,cu.customer,pr.id as pid,pr.project_name,pl.contract_code,pl.products,pl.contract_code,con.contract_fee,pr.id as project_id,pr.province,tr.EnterTime,tr.OpenTime,tr.keyCustom,tr.link_phone,me.nickname as charge_person,tr.TechRole,tr.ProjectRishDescribe,tr.CustomerITNow,tr.TechRole,tr.SalesRole,tr.DevRole,tr.ProductRole,tr.ProjectRishLevel,tr.CustomerExpectLevel,tr.Project_opponents,tr.IsSI,tr.remark')
                      ->join('left join oa_cst_cti_project as pr on pl.project_id = pr.id')
                      ->join('left join oa_cst_customer as cu on cu.id = pr.customer')
                      ->join('left join oa_cst_contract as con on pl.contract_code = con.contract_code')
                      ->join('left join oa_cst_ctp_transfer as tr on tr.contract_code = pl.contract_code')
                      ->join('left join oa_member as me on me.uid = con.charge_person')
                      ->where($map)
                      ->find();
        switch ($plan['ProjectRishLevel']) {
          case '0':
            $plan['ProjectRishLevel'] = "低";
            break;
          case '1':
            $plan['ProjectRishLevel'] = "中";
            break;
          case '2':
            $plan['ProjectRishLevel'] = "高";
            break;
          default:
            $plan['ProjectRishLevel'] = "中";
            break;
        }
        switch ($plan['CustomerExpectLevel']) {
          case '0':
            $plan['CustomerExpectLevel'] = "低";
            break;
          case '1':
            $plan['CustomerExpectLevel'] = "中";
            break;
          case '2':
            $plan['CustomerExpectLevel'] = "高";
            break;
          default:
            $plan['CustomerExpectLevel'] = "中";
            break;
        }
        switch ($plan['IsSI']) {
          case '0':
            $plan['IsSI'] = "否";
            break;
          case '1':
            $plan['IsSI'] = "是";
            break;
          default:
            $plan['IsSI'] = "否";
            break;
        }
        // var_dump(M()->getLastSql());
        // var_dump($plan);
        $this ->assign('plan',$plan);

        $map1['ph.project_id'] =  $plan['project_id'];
        $map1['ph.contract_code'] =  $plan['contract_code'];
        $phases = M('cst_pj_plan_phases as ph') 
                          ->field('ph.*')
                          ->where($map1)
                          ->select();
        // var_dump(M()->getLastSql());
        $this ->assign('phases',$phases);
        // var_dump($phases);
        $this->display();
      }
    }



    /*实施工作计划list*/
    public function workPlan(){
      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;
      

      // if(isset($_GET['customer']) ){
      //     $map['oc.customer']   =   array('like', '%'.$_GET['customer'].'%');
      // }
      if(isset($_GET['project_name']) ){
          $map['p.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      // if(isset($_GET['province']) ){
      //     $map['p.province']   =   array('like', '%'.$_GET['province'].'%');
      // }
     
      if(isset($_GET['status'])){
          $map['wp.status']  =   $_GET['status'];
          $this ->assign('status',$_GET['status']);
      }else{
           $map['wp.status']  =   array('in', '1,2,3');
      }

      // var_dump($map);
      $plans = M("cst_pj_work_plan as wp")
                    // ->field('*')
                    ->field('wp.id,p.project_name,wp.plan_code,wp.phases,wp.status,wp.products,wp.remark,me.nickname as create_person,wp.create_time,ppp.stime,ppp.etime')
                    ->join('left join oa_cst_pj_plan as pl on pl.plan_code = wp.plan_code')
                    ->join('left join oa_cst_cti_project as p on p.id = pl.project_id')
                    ->join('left join oa_cst_pj_plan_phases as ppp on ppp.plan_code = wp.plan_code and ppp.phases = wp.phases')
                    ->join('left join oa_member as me on me.uid = wp.create_person')
                    ->where($map)
                    ->order('wp.create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      // var_dump($plans);
      // var_dump(M()->getLastSql());
      $this->assign('plans',$plans);  
      $this->display();
    }

    /*实施工作计划list*/
    public function workPlan_add(){
      
          if(IS_POST){
            $work['plan_code'] = I('plan_code');
            $work['phases'] = I('plan_phases');
            $work['products'] = I('products');
            $work['remark'] = I('remark');
            $work['accessory'] = I('accessory');
            // $work['status'] = I('status');
            $work['create_person'] = UID;
            $work['create_time'] = date("Y-m-d h:m:s",time());;
            // var_dump($work);
            // var_dump(I('data'));
            // die();
            $res = M('cst_pj_work_plan')->add($work);
            $wph['work_id'] = M()->getLastInsID();
            if($res){
              $wphases = I('data');
              foreach ($wphases as $key => $value) {
                $wph['phases'] = $key+1;
                $wph['products'] = implode(",", $value['products']);
                if($value['stime']){
                  $wph['stime'] = $value['stime'];
                }
                if($value['etime']){
                  $wph['etime'] = $value['etime'];
                }
                $wph['executor'] = implode(",", $value['executor']);
                $wph['remark'] = $value['remark'];
                $res1 = M('cst_pj_work_plan_phases')->add($wph);
              }
            }
            if($res||$res1){
              action_log('tech_work_add','tech',UID,UID);
              $this->success('添加成功！',U('workPlan'));
            }else{
              $this->error('添加失败！');
            }

          }else{


            /*获取已交接的项目*/
            $map4['pl.status'] = '1';
            $projects = M('cst_pj_plan as pl')
                        ->field('pl.project_id,pr.project_name')
                        ->join('left join oa_cst_cti_project as pr on pr.id = pl.project_id')
                        // ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = pl.project_id')
                        ->where($map4)
                        ->select();
            // var_dump($plans);
            // var_dump(M()->getLastSql());
            $this ->assign('projects',$projects);

            $map['status'] = '1';
            $plans = M('cst_pj_plan')->field('plan_code')->where($map)->select();
            // var_dump($plans);
            $this ->assign('plans',$plans);

            /*拉取产品*/
            $p = M("cst_product") ->field('id,product_name')->order('product_name asc')->select();
            $this->assign('products',$p);
           
            /*拉去实施人员*/
            $map1['ac.group_id'] = 3;
            $techs = M('member as me')
                    ->field('me.uid,me.nickname')
                    ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                    ->where($map1)
                    ->select();
            $this->assign('techs',$techs);
            $this->display();

          }
    }

    /*实施工作计划作废*/
    public function wp_breake(){
      /*获取删除合同数组*/
      $idArray = I('ids');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      
      /*状态转化为终止*/
      $data['status'] = '2';
     
      $res = M("cst_pj_work_plan")->where($map)->save($data);

      if($res){
          action_log('tech_work_cancel','tech',UID,UID);
          $this->success('作废成功！',U('workplan'));
      }else {
          $this->error('作废失败！');
      }
    }

    /*实施工作计划详情*/
    public function workplanMess(){
      if(IS_POST){
        // var_dump(I());

        $map['work_id'] = I('id');
        $res1 = M('cst_pj_work_plan_phases')->where($map)->delete();

        $data = I('data');
        $wph['work_id'] = I('id');
        foreach ($data as $key => $value) {
          $wph['phases'] = $key+1; 
          $wph['products'] = implode(",", $value['products']);
          if($value['stime']){
            $wph['stime'] = $value['stime'];
          }
          if($value['etime']){
            $wph['etime'] = $value['etime'];
          }
          $wph['executor'] = implode(",", $value['executor']);
          $wph['remark'] = $value['remark'];
          $res1 = M('cst_pj_work_plan_phases')->add($wph);
        }
        if($res1){
            action_log('tech_work_up','tech',UID,UID);
            $this->success('更新成功！',U('workPlan'));
        }else{
            $this->error('更新失败！');
        }
      }else{

          /*拉取产品*/
          $p = M("cst_product") ->field('id,product_name')->select();
          $this->assign('products',$p);
         
          /*拉去实施人员*/
          $map2['ac.group_id'] = 3;
          $techs = M('member as me')
                  ->field('me.uid,me.nickname')
                  ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                  ->where($map2)
                  ->select();
          $this->assign('techs',$techs);

          $map['wp.id'] = $_GET['id'];
          $plan = M('cst_pj_work_plan as wp')
                      ->field('wp.id,wp.plan_code,wp.phases,wp.products,ph.stime,ph.etime,ph.IsPayPhases,ph.PhasesFee,ph.rtdate,ph.rpercent,ph.rmoney,ph.remark,wp.remark as wremark') 
                      ->join('left join oa_cst_pj_plan_phases as ph on wp.plan_code = ph.plan_code and wp.phases = ph.phases')
                      ->where($map) 
                      ->find();
          // var_dump($plan);
          if($plan['IsPayPhases'] == "0"){
              $plan['IsPayPhases'] = '否';
          }elseif($plan['IsPayPhases'] = "1"){
              $plan['IsPayPhases'] = '是';
          }
          // var_dump($plan);
          $this->assign('plan',$plan);

          /*获取工作计划阶段*/
          $map1['work_id'] = $_GET['id'];
          $wp = M('cst_pj_work_plan_phases')->where($map1)->order('phases asc')->select();
          $this->assign('wp',$wp);
          // var_dump($wp);
          $this ->display();
      }
    }

    /*Ajax根据实施计划获取实施阶段*/
    public function get_planPhases(){
      $map['plan_code'] = I('plan_code');
      $plan_phases = M('cst_pj_plan_phases')->field('phases')->where($map)->select();
      // var_dump($plans);
      $this ->ajaxReturn($plan_phases);
    }

    /*Ajax根据实施阶段获取阶段详情*/
    public function get_PhasesMess(){
      $map['phases'] = I('plan_phases');
      $map['plan_code'] = I('plan_code');
      $phasesMess = M('cst_pj_plan_phases')->field('products,stime,etime,rtdate,rpercent,rmoney,remark,IsPayPhases,PhasesFee')->where($map)->find();
      if($phasesMess['IsPayPhases']== '0'){
          $phasesMess['IsPayPhases'] = '否';
      }elseif($phasesMess['IsPayPhases']== '1'){
          $phasesMess['IsPayPhases'] = '是';
      }
      // var_dump($plans);
      $this ->ajaxReturn($phasesMess);
    }

    /*实施工作记录list*/
    public function work_log(){
      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;
      

      // if(isset($_GET['customer']) ){
      //     $map['oc.customer']   =   array('like', '%'.$_GET['customer'].'%');
      // }
      if(isset($_GET['project_name']) ){
          $map['pr.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['executor']) ){
          $map['wpp.executor']   =   array('like', '%'.$_GET['executor'].'%');
      }
     
      if(isset($_GET['status'])){
          $map['ppl.status']  =   $_GET['status'];
          $this ->assign('status',$_GET['status']);
      }else{
           $map['ppl.status']  =   array('in', '0,1,2,3');
      }
      // var_dump($map);
      $work_log = M("cst_pj_work_log as ppl")
                    ->field('ppl.id,ppl.create_time,pr.project_name,wp.plan_code,wp.phases,wpp.phases  as wphases,wpp.remark,wpp.executor,ppl.status,ppl.fee,ppl.next_plan,me.nickname as create_person,pp1.products')
                    ->join('left join oa_cst_pj_work_plan as wp on wp.id = ppl.work_id')
                    ->join('left join oa_cst_pj_plan as pp on wp.plan_code = pp.plan_code')
                    ->join('left join oa_cst_cti_project as pr on pr.id = pp.project_id')
                    ->join('left join oa_cst_pj_work_plan_phases as wpp on wpp.id=ppl.workplan_phases')
                    ->join('left join oa_member as me on me.uid = ppl.create_person')
                    ->join('left join oa_cst_pj_plan_phases as pp1 on pp1.plan_code = wp.plan_code and pp1.phases = wp.phases')
                    ->where($map)
                    ->order('create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      $this->assign('work_log',$work_log);
      // var_dump($work_log);
      // var_dump(M()->getLastSql());
      // die();
      $count=M("cst_pj_plan as ppl")
                ->join('left join oa_cst_cti_project as p on p.id = ppl.project_id')
                ->join('left join oa_cst_customer as oc on p.customer = oc.id')
                ->where($map)
                ->count();
      // var_dump(M()->getLastSql());
      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }

      
      $this ->display();
    }

    /*实施工作记录完成*/
    public function complete(){
      /*获取删除合同数组*/
      $idArray = I('ids');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      
      /*状态转化为终止*/
      $data['status'] = '2';
     
      $res = M("cst_pj_work_log")->where($map)->save($data);

      if($res){
          action_log('tech_work_log_complete','tech',UID,UID);
          $this->success('状态变更成功！',U('work_log'));
      }else {
          $this->error('状态变更失败！');
      }
    }

    /*实施工作记录添加*/
    public function work_log_add(){

      if($_GET['id']){
        $res = M('cst_pj_work_plan as wp') 
                  ->field('pr.project_name,pr.id as project_id')
                  ->join('left join oa_cst_pj_plan as pp on pp.plan_code = wp.plan_code')
                  ->join('oa_cst_cti_project as pr on pr.id = pp.project_id')
                  ->where()
                  ->select();
        $this ->assign('project',$res);

        $map['ppl.id'] = $_GET['id'];
        $this ->assign('mid',$_GET['id']);
        $work_log = M("cst_pj_work_log as ppl")
                  ->field('ppl.id,ppl.create_time,pr.project_name,wp.plan_code,wp.phases,wpp.phases as wphases,wpp.remark,wpp.executor,ppl.status,ppl.fee,ppl.next_plan,me.nickname as create_person,pp1.products,pp1.stime,pp1.etime,ppl.discribe,ppl.fee,ppl.next_plan')
                  ->join('left join oa_cst_pj_work_plan as wp on wp.id = ppl.work_id')
                  ->join('left join oa_cst_pj_plan as pp on wp.plan_code = pp.plan_code')
                  ->join('left join oa_cst_cti_project as pr on pr.id = pp.project_id')
                  ->join('left join oa_cst_pj_work_plan_phases as wpp on wpp.id=ppl.workplan_phases')
                  ->join('left join oa_member as me on me.uid = ppl.create_person')
                  ->join('left join oa_cst_pj_plan_phases as pp1 on pp1.plan_code = wp.plan_code and pp1.phases = wp.phases')
                  ->where($map)
                  ->find();
        // var_dump($work_log);
        $this ->assign('work_log',$work_log);
        $this->display();  
      }else{
        if(IS_POST){
          // var_dump(I());
          $data['work_id'] = I('workplan');
          $data['workplan_phases'] = I('workplan_phases');
          $data['discribe'] = I('discribe');
          $data['fee'] = I('fee');
          $data['next_plan'] = I('next_plan');
          $data['status'] = I('status');
          $data['create_person'] = UID;
          $data['create_time'] = date("Y-m-d h:m:s",time());
          // var_dump($data);
          $res = M('cst_pj_work_log')->add($data);
          if($res){
            action_log('tech_work_log_add','tech',UID,UID);
            $this->success('添加成功！',U('work_log'));
          }else{
            $this->error('添加失败！');
          }
        }else{
          $map['wp.status'] = '1';
          $res = M('cst_pj_work_plan as wp') 
                    ->field('pr.project_name,pr.id as project_id')
                    ->join('left join oa_cst_pj_plan as pp on pp.plan_code = wp.plan_code')
                    ->join('oa_cst_cti_project as pr on pr.id = pp.project_id')
                    ->where($map)
                    ->select();
          $this ->assign('project',$res);          
          // var_dump($res);
          $this->display();
        }

      }

    }


    /*Ajax根据商业地产拉去相应的工作计划*/
    public function get_wpByProject(){
      $map['pp.project_id'] = I('project_id');
      $phasesMess = M('cst_pj_work_plan as wp')
                        ->field('wp.plan_code,wp.phases,wp.id')
                        ->join('left join oa_cst_pj_plan as pp on pp.plan_code = wp.plan_code')
                        ->join('left join oa_cst_cti_project as p on pp.project_id = p.id')
                        ->where($map)
                        ->select();
      
      $this ->ajaxReturn($phasesMess);
      // $this ->ajaxReturn($phasesMess);
    }

    /*Ajax根据工作计划拉去工作计划阶段*/
    public function get_wpPhasesByWid(){
      $map['work_id'] = I('workplan');
      $wpp = M('cst_pj_work_plan_phases')
                        ->field('id,phases')
                        ->where($map)
                        ->order('phases asc')
                        ->select();
      
      $this ->ajaxReturn($wpp);
      // $this ->ajaxReturn($phasesMess);
    }

    /*Ajax根据工作计划阶段id拉去阶段详情*/
    public function get_wpPhasesMessByPhases(){
      $map['work_id'] = I('wid');
      $map['id'] = I('phases');
      $wpp = M('cst_pj_work_plan_phases')
                        ->field('*')
                        ->where($map)
                        ->order('phases asc')
                        ->find();
      
      // $this ->ajaxReturn(M()->getLastSql());
      $this ->ajaxReturn($wpp);
    }
   
    /*实施计划跟进记录列表*/
    public function follow_up(){
      $res = M("cst_pj_excute_rd")

                ->where($map)
                ->page($pageindex, $pagesize)
                ->select($data);
    }

    /*添加实施计划跟进记录*/
     public function add_follow_up(){
      $res = M("cst_pj_excute_rd")->add($data);
    }

    /*删除加实施计划跟进记录*/
     public function delete_follow_up(){
      /*获取删除合同数组*/
      $idArray = I('id');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      $res = M("cst_pj_excute_rd")->where($map)->delete();
      var_dump(M()->getLastSql());
    }



    /*实施过程记录列表首页*/
    public function execut_log(){

      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;


      /*获取列表*/
      // $map['p.project_name'] = array('like', '%' . (string)I('project_name') . '%');
      // $map['rd.phases'] = I('phases');
      // $map['rd.executor'] = I('executor');
      // $map['_logic'] = 'or';

      if(isset($_GET['project_name']) ){
          $map['p.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['executor']) ){
          $map['rd.executor']   =   array('like', '%'.$_GET['executor'].'%');
      }
      // if(isset($_GET['province']) ){
      //     $map['p.province']   =   array('like', '%'.$_GET['province'].'%');
      // }


      $excutes = M("cst_pj_excute_rd as rd")
                    ->field('rd.plan_code,rd.next_plan,rd.id,rd.manday,rd.rd_time,me.nickname as create_person,p.project_name,rd.phases,rd.executor,rd.content,rd.project_fee,rd.s_time,rd.e_time')
                    ->join('left join oa_cst_cti_project as p on p.id = rd.project_id')
                    ->join('left join oa_member as me on me.uid = rd.create_person')
                    // ->join('left join oa_member as mem on mem.uid = rd.executor')
                    ->where($map)
                    ->order('rd.rd_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      // var_dump(M()->getLastSql());
      // var_dump($excutes);

      $this->assign('excutes',$excutes);
      $count=M("cst_pj_excute_rd as rd")
                    ->field('rd.rd_time,p.project_name,rd.phases,rd.executor,rd.content,rd.project_fee')
                    ->join('left join oa_cst_cti_project as p on p.id = rd.project_id')
                    ->where($map)
                    // ->order('create_time desc')
                    ->count();

      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }
      
      $this ->display();
    }

    /*添加实施过程记录*/
    public function add_execut_log(){
      // var_dump(phpinfo());
      if(IS_POST){
        $map1['uid'] = UID;
        $user = M('member') ->field('nickname')->where($map1) ->find();
        $map2['id'] = I('project_id');
        $project = M('cst_cti_project') ->field('project_name')->where($map2) ->find();
        $content = $user['nickname']."添加了实施记录
                                              --项目：".$project['project_name'].
                                              "--计划编号:".I('plan_code').
                                              "--阶段：第".I('phases').
                                              "--实施人员：".I('executor').
                                              "--实施人天:".I('manday').
                                              "--开始日期:".I('s_time').
                                              "--结束日期:".I('e_time').
                                              "--实施内容:".I('content').
                                              "--报销费用(元):".I('project_fee').
                                              "--下一步计划:".I('next_plan');
        $map3['plan_code'] = I('plan_code');
        $tech_charge_person = M('cst_pj_plan') ->field('tech_charge_person')->where($map3) ->find();
        $map4['username'] = $tech_charge_person ['tech_charge_person'];
        $em = M('ucenter_member') ->field('email')->where($map4) ->find();
        $ml = "chenlei@gdwstech.com";
        SendMail($em['email'],$user['nickname'].'新增实施记录',$content);
        // die();
        /*实施记录表添加*/
        $data['project_id'] = I('project_id'); 
        $data['plan_code'] = I('plan_code'); 
        $data['phases'] = I('phases'); 
        $data['executor'] = I('executor'); 
        $data['content'] = I('content'); 
        $data['manday'] = I('manday'); 
        if(I('s_time')){
          $data['s_time'] = I('s_time'); 
        }
        if(I('e_time')){
          $data['e_time'] = I('e_time'); 
        }
        $data['next_plan'] = I('next_plan'); 
        $data['rd_time'] = date("Y-m-d h:m:s",time()); 
        $data['project_fee'] = I('project_fee'); 
        $data['create_person'] = UID; 
        $res = M('cst_pj_excute_rd') ->add($data);
        // var_dump(M()->getLastSql());
        if($res){
            action_log('tech_process_add','tech',UID,UID);
            $this->success('添加成功！',U('execut_log'));
            // SendMail($ml,$user['nickname'].'新增实施记录',$content);
        }else {
            $this->error('添加失败！');
        }
      }else{

        /*拉去实施人员*/
        $map1['ac.group_id'] = 3;
        $techs = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();
        $this->assign('techs',$techs);
        // var_dump($techs);


        /*获取已交接的项目*/
        $map['pl.status'] = '1';
        $plans = M('cst_pj_plan as pl')
                    ->field('pl.project_id,pr.project_name')
                    ->join('left join oa_cst_cti_project as pr on pr.id = pl.project_id')
                    // ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = pl.project_id')
                    ->where($map)
                    ->select();
        // var_dump($plans);
        // var_dump(M()->getLastSql());
        $this ->assign('plans',$plans);
        $this ->display();
      }
        
    }

    /*Ajax根据project_id获取实施计划编号*/
    public function getPlanCode(){
      $map['project_id'] = I('project_id');
      $res = M('cst_pj_plan')->field('plan_code')->where($map)->select();
      $this ->ajaxReturn($res);
    }

    /*Ajax根据project_id获取实施计划阶段数据*/
    public function get_phases(){
      $map['plan_code'] = I('plan_code');
      $res = M('cst_pj_plan_phases')->field('phases')->where($map)->select();
      $this ->ajaxReturn($res);
    }

    /*实施项目验收记录列表*/
    public function pj_complete(){
        $pageindex['p'] = $_GET["p"];
        if (empty($pageindex['p'])||$pageindex['p']=="0") {
            $pageindex['p']=1;
        }
        $pagesize = PAGESIZE;

        // /*获取列表*/
        // $map['p.project_name'] = array('like', '%' . (string)I('project_name') . '%');
        // $map['rd.phases'] = I('phases');
        // $map['rd.executor'] = I('executor');
        // $map['_logic'] = 'or';

        if(isset($_GET['project_name']) ){
          $map['pr.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
        }
        if(isset($_GET['executor']) ){
            $map['me.nickname']   =   array('like', '%'.$_GET['executor'].'%');
        }

        $completes = M('cst_pj_complete as co')
              ->field('co.id,co.complete_code,pr.project_name,ph.plan_code,ph.phases,co.IsDelay,co.DelayWhy,co.receipt_url,co.prompt_url,co.prompt_id,me.nickname as executor,co.ac_rtime,co.rd_time')
              ->join('left join oa_cst_cti_project as pr on pr.id = co.project_id')
              // ->join('left join oa_cst_pj_plan_phases as pl on pl.project_id = co.project_id')
              ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = co.project_id and ph.phases = co.phases')
              ->join('left join oa_member as me on me.uid = co.executor')
              ->where($map)
              ->order('rd_time desc')
              ->page($pageindex['p'], $pagesize)
              ->select();
        // var_dump($map);
        // var_dump(M()->getLastSql());
        $this ->assign('completes',$completes);

        $count=M("cst_pj_complete as co")
                    ->join('left join oa_cst_cti_project as pr on pr.id = co.project_id')
                    // ->join('left join oa_cst_pj_plan_phases as pl on pl.project_id = co.project_id')
                    ->join('left join oa_cst_pj_plan_phases as ph on ph.plan_code = co.plan_code and ph.phases = co.phases')
                    ->where($map)
                    ->order('rd_time desc')
                    ->count();

        if($count > $pagesize) {
            $page = new \COM\Page($count, $pagesize,$pageindex);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }


        $this ->display();

    }

    /*实施项目交付记录添加*/
    public function add_project_complete(){
        if(IS_POST){
            $maxid = M("cst_pj_complete")
                    ->field('id')
                    ->order('id desc')
                    ->find();

            $data['complete_code'] = "JF".date("Ymd",time()).sprintf("%02d", $maxid['id']+1);//生成4位数，不足前面补0 
            $data['project_id'] = I('project_id');
            $data['plan_code'] = I('plan_code');
            /*实施计划阶段*/ 
            $data['phases'] = I('phases'); 

            /*是否延期交付*/
            $data['IsDelay'] = I('IsDelay');
            if(I('IsDelay')){
              $data['DelayWhy'] = I('DelayWhy'); 
            }
            /*实施人员*/
            $data['executor'] = I('executor'); 
            /*实际交付时间*/
            if(I('ac_rtime')){
              $data['ac_rtime'] = I('ac_rtime');
            }
            $data['remark'] = I('remark');
            $data['remark'] = I('remark');

            /*验收单*/
            // $data['receipt_url'] = I('receipt_url');
            $data['create_person'] = UID; 
            $data['rd_time'] = date("Y-m-d h:m:s",time());

            /* 调用文件上传组件上传文件 */
            $File = D('File');
            $info = $File->upload($_FILES, C('DOWNLOAD_UPLOAD')); //TODO:上传到远程服务器
            if($info){
              $data['receipt_url'] = "http://oa.gdwstech.com/Uploads/Download/".$info['file']['savepath'].$info['file']['savename'];
            }

            /*判断是否是收款周期*/
            $map1['project_id'] = I('project_id'); 
            $map1['phases'] = I('phases'); 
            $phase = M('cst_pj_plan_phases')->field('id,plan_code,IsPayPhases,PhasesFee,etime')->where($map1)->find();
            // var_dump($phase);
            // die();
            /*如果是生成催款单*/
            if($phase['IsPayPhases'] == 1){
              $maxid1 = M("cst_pj_prompt")
                    ->field('id')
                    ->order('id desc')
                    ->find();
           

              /*收款记录表，部分字段例如：预计回款 和实施计划周期表一致不用重复录入*/
              /*项目id*/
              $data1['project_id'] = I('project_id');
              /*实施计划编码*/
              $data1['plan_code'] = $phase['plan_code'];
              /*实施计划阶段*/
              $data1['phases'] = I('phases');
              /*催款日期*/
              $data1['prompt_time'] = $phase['etime'];
              /*催款单号*/
              $data1['prompt_id'] = "CK".date("Ymd",time()).sprintf("%02d", $maxid['id']+1);//生成2位数，不足前面补0 
              
              // var_dump($data1);
              // die();

              $res1 = M('cst_pj_prompt')->add($data1);

              /*开票表同步验收记录，待财务确认是否开票*/
              $data3['complete_code'] = "JF".date("Ymd",time()).sprintf("%02d", $maxid['id']+1);//生成4位数，不足前面补0 
              $data3['project_id'] = I('project_id');
              $data3['plan_code'] = I('plan_code');
              $data3['phases'] = I('phases');
              $res3 = M('cst_fd_invoice')->add($data3);

              $data['prompt_id'] = $data1['prompt_id'];
              $data['plan_code'] = $data1['plan_code'];

            }

            $res = M('cst_pj_complete') ->add($data);

            if($res){
                // $map11['uid'] = UID;
                // $user11 = M('member') ->field('nickname')->where($map11) ->find();

                // $map55['plan_code'] = I('plan_code');
                // $tech_charge_person = M('cst_pj_plan') ->field('tech_charge_person')->where($map55) ->find();
                // $map44['username'] = $tech_charge_person ['tech_charge_person'];
                // $em = M('ucenter_member') ->field('email')->where($map44) ->find();

                // $map33['uid'] = I('executor');
                // $user3 = M('member') ->field('nickname')->where($map33) ->find();

                // $map2['project_id'] = I('project_id');
                // $project11 = M('cst_cti_project') ->field('project_name')->where($map2) ->find();

                // if(I('IsDelay') == '0'){
                //   $IsDelay = '否';
                // }else{
                //   $IsDelay = '是--延期原因:'.I('DelayWhy');
                // }
                // $content = $user11['nickname']."添加了验收记录--项目：".$project11['project_name']."--计划编号:".I('plan_code')."--阶段：第".I('phases')."--实施人员：".$user3['nickname']."--是否延期交付:".$IsDelay."--实际交付时间:".I('ac_rtime')."--备注:".I('remark');
                // SendMail($em['email'],$user11['nickname'].'添加了验收记录',$content);
                // // var_dump(I());
                // // die();
                
                // if(SendMail('chenlei@gdwstech.com',$user['nickname'].'添加了验收记录',$content)) {
                //   die();
                //     // $this->success('发送成功！');
                // } else {
                //   die();
                //     // $this->error('发送失败');
                // }
                action_log('tech_check_add','tech',UID,UID);
                $this->success('添加成功！',U('pj_complete'));
            }else {
                $this->error('添加失败！');
            }
           
        }else{

            /*获取实施计划中正在进行的项目*/
            $map['pl.status'] = '1';
            $projects = M('cst_pj_plan as pl')
                      ->field('pl.project_id,pr.project_name')
                      ->join('left join oa_cst_cti_project as pr on pr.id = pl.project_id')
                      ->where($map)
                      ->select();
            $this ->assign('projects',$projects);
            // var_dump($projects);     
            // var_dump(M()->getLastSql());   
            
            /*获取实施人员列表*/
            $map1['ac.group_id'] = 3;
            $techs = M('member as me')
                    ->field('me.uid,me.nickname')
                    ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                    ->where($map1)
                    ->select();   
            $this->assign('techs',$techs);
            $this ->display();
        }


    }

    /*Ajax根据project_id获取正式合同数据*/
    public function get_contractMess(){
      $map['co.project_id'] = I('project_id');

      $res = M('cst_contract as co')
                  ->field('co.start_time,pr.project_type,co.province,tr.TechRole,tr.project_productlist,co.contract_fee')
                  ->join('left join oa_cst_cti_project as pr on pr.id = co.project_id')
                  ->join('left join oa_cst_ctp_transfer as tr on tr.project_id = co.project_id')
                  ->where($map)
                  ->find();
      $this ->ajaxReturn($res);
    }

    /*批量提交交付记录生成催款单*/
    public function create_prompt_url(){
      /*获取数组*/
      $idArray = I('ids');
      foreach ($idArray as $key => $value) {

          /*收款记录表，部分字段例如：预计回款 和实施计划周期表一致不用重复录入*/

          /*项目id*/
          $data['project_id'] = I('project_id');
          /*实施计划编码*/
          $data['plan_code'] = I('plan_code');
          /*实施计划阶段*/
          $data['phases'] = I('phases');
          /*催款日期*/
          $data['prompt_time'] = I('prompt_time');
          /*催款单号*/
          $data['prompt_id'] = "CK".date_format("Y-m-d",time()).sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
          /*催款事由*/
          $data['reasons'] = I('reasons');
          /*情况描述*/
          $data['discribe'] = I('discribe');
          /*需要配合*/
          $data['help'] = I('help');
          /*催款状态*/
          $data['status'] = 0;
          /*收款日期（客服承诺收款日期）*/
          $data['rtime'] = I('rtime');
          
          $res = M('cst_pj_prompt')->where()->add();
      }
    
      var_dump(M()->getLastSql());
    }

    /*实施收款记录列表*/
    public function payreminder(){
        // $key1 = I('project_name');
        // $key2 = I('executor');
        // $key3 = I('phases');
        // $key4 = I('prompt_code');
        // $map['pr.project_name'] = array('like', '%' . (string)$key1 . '%');
        // $map['rd.executor'] = array('like', '%' . (string)$key2 . '%');
        // $map['rd.phases'] = array('like', '%' . (string)$key3 . '%');
        // $map['rd.prompt_code'] = I('project_type');
        // $map['_logic'] = 'or';
       
        if(isset($_GET['project_name']) ){
          $map['pr.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
        }
        if(isset($_GET['executor']) ){
            $map['me.nickname']   =   array('like', '%'.$_GET['executor'].'%');
        }
        if(isset($_GET['prompt_id']) ){
            $map['rd.prompt_id']   =   array('like', '%'.$_GET['prompt_id'].'%');
        }

        $pageindex['p'] = $_GET["p"];
        if (empty($pageindex['p'])||$pageindex['p']=="0") {
            $pageindex['p']=1;
        }
        $pagesize = PAGESIZE;

        $payreminders = M('cst_pj_payreminder_rd as rd')
                    ->field('rd.plan_code,rd.id,rd.create_time,pr.project_name,pt.phases,rd.executor,rd.prompt_result,rd.status,rd.rtime,rd.rmoney')
                    ->join('left join oa_cst_pj_prompt as pt on pt.prompt_id = rd.prompt_id')
                    ->join('left join oa_cst_cti_project as pr on pr.id = pt.project_id')
                    // ->join('left join oa_member as me on me.uid = rd.executor')
                    ->where($map)
                    ->order('rd.create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
       
        $this ->assign('payreminders',$payreminders);
        // var_dump(M()->getLastSql());
        // var_dump($payreminders);
        $count=M('cst_pj_payreminder_rd as rd')
                    ->field('rd.create_time,pr.project_name,pt.phases,rd.executor,rd.prompt_result,rd.status,rd.rtime,rd.rmoney')
                    ->join('left join oa_cst_pj_prompt as pt on pt.prompt_id = rd.prompt_id')
                    ->join('left join oa_cst_cti_project as pr on pr.id = pt.project_id')
                    ->where($map)
                    ->count();

        if($count > $pagesize) {
            $page = new \COM\Page($count, $pagesize,$pageindex);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }

        $this ->display();
    }
    

    /*实施收款记录录入*/
    public function payreminder_add(){
      if(IS_POST){
        /*催款单号*/
        $data['prompt_id'] = I('prompt_id');
        $data['project_id'] = I('project_id');
        $data['plan_code'] = I('plan_code');
        $data['phases'] = I('phases');
        
        /*收款情况描述*/
        $data['prompt_result'] = I('prompt_result');
        /*收款状况*/
        $data['status'] = I('status');
        /*预计回款日期*/
        $data['rtime'] = I('rtime');
        /*预计预计比例*/
        $data['rpercent'] = I('rpercent');
        /*预计预计回款金额*/
        $data['rmoney'] = I('rmoney');
        /*实际收款金额*/
        $data['acRmoney'] = I('acRmoney');
        $data['remark'] = I('remark');
        $data['executor'] = I('executor');
        $data['create_person'] = UID;
        $data['create_time'] = date("Y-m-d",time());
        // var_dump(I());
        // die();
        // $res = M("cst_pj_payreminder_rd")->add($data);


        $maxid = M("cst_fd_withdraw")
                    ->field('id')
                    ->order('id desc')
                    ->find();
        if(!$maxid){
          $maxid['id'] = 0;
        }

        $data1['remoney_code'] = "FK".date('Ymd',time()).sprintf("%02d", $maxid['id']+1);//生成4位数，不足前面补0   
        $data1['project_id'] = I('project_id');
        $data1['plan_code'] = I('plan_code');

        /*根据实施计划获取合同编号*/
        $pp['plan_code'] = I('plan_code');
        $res333 = M('cst_pj_plan') ->field('contract_code')->where($pp)->find();
        $data1['contract_code'] = $res333['contract_code'];

        $data1['phases'] = I('phases');
        $data1['create_person'] = UID;
        $data1['create_time'] = date('Y-m-d',time(0));
        if(I('status') == '1'){
           $data1['status'] = '1';
        }elseif(I('status') == '2'){
          $data1['status'] = '2';
        }
        // var_dump($data1);
        // die();
        $res = M("cst_pj_payreminder_rd")->add($data);
        $res1 = M("cst_fd_withdraw")->add($data1);


        if($res){
          action_log('tech_gathering_add','tech',UID,UID);
          $this->success('添加成功！',U('payreminder'));
        }else {
            $this->error('添加失败！');
        }
        // $this ->display();
      }else{
        /*获取催款单号*/
        $map['status'] = '0';
        $prompts = M('cst_pj_prompt')
                  ->field('id,prompt_id')
                  ->where($map)
                  ->select();
        // var_dump($res);
        // var_dump(M()->getLastSql());
        $this ->assign('prompts',$prompts);
        $this ->display();
      }
      
    }

    /*ajax根据催款单号获取详情*/
    public function getMessByPromptId(){
        $map['pt.prompt_id'] = I('prompt_id');
        $prompt = M("cst_pj_prompt as pt") 
                    ->field('pt.plan_code,pr.project_name,pr.id,pt.phases,me.nickname as executor,ph.rtdate,ph.rpercent,ph.rmoney') 
                    ->join('left join oa_cst_cti_project as pr on pt.project_id = pr.id')
                    ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = pr.id and ph.phases = pt.phases')
                    ->join('left join oa_member as me on ph.executor = me.uid')
                    ->where($map)
                    ->find();
        
        // $this ->ajaxReturn(123);
        $this ->ajaxReturn($prompt);
    }

    /*实施收款记录-客户确认*/
    public function cus_sure(){
        /*获取删除合同数组*/
        $idArray = I('ids');
        // $uidArray = array(4);
        $map['id'] = array('in', $idArray);
        $data['status'] = '1';
        $res = M("cst_pj_payreminder_rd")->where($map)->save($data);

        $res1 = M("cst_pj_payreminder_rd")->field('project_id,phases')->where($map)->select();

        foreach ($res1 as $key => $value) {
          $map1['project_id'] = $value['project_id'];
          $map1['phases'] = $value['phases'];
          $data1['status'] = '1';
          $res2 = M("cst_fd_withdraw")->where($map1)->save($data1);
        }
        // var_dump($res1);
        // die();
        if($res){
            action_log('tech_gathering_sure','tech',UID,UID);
            $this->success('确认成功！',U('payreminder'));
        }else {
                $this->error('确认失败！');
        }
        // var_dump(M()->getLastSql());
    }

    
    
}

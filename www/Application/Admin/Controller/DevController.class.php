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

/*开发管理控制器*/
class DevController extends AdminController {

    /*新产品开发计划列表*/
    public function newDevList(){

      /*获取分页*/
      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;
      
      if(isset($_GET['product_name']) ){
          $map['ne.product_name']    =   array('like', '%'.$_GET['product_name'].'%');
      }
      if(isset($_GET['status']) ){
          $map['ne.status']   =   array('like', $_GET['status']);
      }
      $this ->assign('status',$_GET['status']);
      $pageindex['status']=$_GET['status'];
      $pageindex['product_name']=$map['ne.product_name'];
      /*获取列表*/
      
      // $map['product_name'] = array('like', '%' . (string)I('project_name') . '%');
      // $map['start_time'] = I('start_time');
      // $map['product_type'] = I('product_type');
     

      // $map['_logic'] = 'or';

      $newproducts = M("cst_dev_newproduct as ne")
                    ->field('ne.product_charge,ne.id,ne.plan_code,ne.version,ne.product_type,ne.status,ne.product_name,ne.start_time,ne.ac_time,me.nickname as dev_role,ne.describe,me1.nickname as create_person,ne.create_time,me2.nickname as update_person,ne.update_time')
                    ->join('left join oa_member as me on ne.dev_role = me.uid')
                    ->join('left join oa_member as me1 on ne.create_person = me1.uid')
                    ->join('left join oa_member as me2 on ne.update_person = me2.uid')
                    // ->join('left join oa_member as me3 on ne.dev_role = me.uid')
                    ->where($map)
                    ->order('ne.create_time desc,ne.update_time,ne.start_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();

      foreach ($newproducts as $key => $value) {
        $tt = (strtotime($value['ac_time'])-strtotime($value['start_time']))/(60*60*24);
        if($tt>=0){
          $newproducts[$key]['manday'] = (strtotime($value['ac_time'])-strtotime($value['start_time']))/(60*60*24);
        }
        // echo strtotime($value['ac_time']);
      }
      // var_dump($newproducts);
      // var_dump(M()->getLastSql());
      $this ->assign('newproduct',$newproducts);
      /*获取总数*/
      $count=M("cst_dev_newproduct as ne")
                  ->field('ne.id,ne.plan_code,ne.status,ne.product_name,ne.start_time,ne.end_time,ne.manday,me.nickname as dev_role,ne.describe,me1.nickname as create_person,ne.create_time,me2.nickname as update_person,ne.update_time')
                  ->join('left join oa_member as me on ne.dev_role = me.uid')
                  ->join('left join oa_member as me1 on ne.create_person = me1.uid')
                  ->join('left join oa_member as me2 on ne.update_person = me2.uid')
                  // ->join('left join oa_member as me3 on ne.dev_role = me.uid')
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

    /*添加*/
    public function new_dev_add(){
      if(!IS_POST){
        /*拉取产品*/
        $products = M("cst_product") ->field('id,product_name')->order('product_name asc')->select();
        $this->assign('products',$products);
        // /*拉取开发人员*/
        $map1['ac.group_id'] = 10;
        $devs = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();
        $this->assign('devs',$devs);

        /*拉取开发负责人*/
        $map11['ac.group_id'] = 11;
        $devs1 = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map11)
                ->select();   
        $this->assign('devs1',$devs);
       

        /*拉取产品负责人*/
        $map22['ac.group_id'] = 8;
        $prods = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map22)
                ->select();   
        $this->assign('prods',$prods);
        // var_dump($devs);
        $this->assign('project',$p);
        
        $mmmm['ac.group_id'] = array('in','8,11,10');
        $dddd = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($mmmm)
                ->select();
        $this->assign('dddd',$dddd);

        $map2['id'] = I('id');
        $res2 = M('cst_dev_newproduct')->where($map2) ->find();
        // var_dump($res2);
        $this->assign('newproducts',$res2);
        $map3['plan_code'] = $res2['plan_code'];
        $res3 = M('cst_new_dev_phases') ->where($map3)->order('phases asc')->select();
        $this ->assign('phases',$res3);
        // var_dump($res3);
        $this->display();

      }else{
        if(!I('id')){
            $maxid = M("cst_dev_newproduct")
                        ->field('id')
                        ->order('id desc')
                        ->find();

            /*新产品表添加*/
            $data['plan_code'] = "KF".date("Ymd",time()).sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
            $data['product_type'] = I('product_type'); 
            $data['product_name'] = I('product_name'); 
            $data['version'] = I('version'); 
            if(I('ac_time')){
              $data['ac_time'] = I('ac_time');
            } 
            if(I('end_time')){
              $data['end_time'] = I('end_time'); 
            } 
            if(I('start_time')){
              $data['start_time'] = I('start_time');
            } 
            $data['manday'] = I('manday'); 
            $data['dev_role'] = I('dev_role'); 
            $data['product_charge'] = I('product_charge'); 
            $data['describe'] = I('describe');
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

            $res = M('cst_dev_newproduct') ->add($data);

            /*如果添加成功，再循环添加计划周期*/
            if($res){
              /*新产品计划周期表添加*/
              $phases = I('data');
              foreach ($phases as $key => $value) {
                $data1[$key]['plan_code'] = $data['plan_code'];
                $data1[$key]['phases'] = $key+1;
                $data1[$key]['product'] = I('product_name');
                $data1[$key]['product_jd'] = $value['product_jd'];
                if($value['stime'] !== ''){
                  $data1[$key]['stime'] = $value['stime'];
                }
                if($value['etime'] !== ''){
                  $data1[$key]['etime'] = $value['etime'];
                }
                $data1[$key]['manday'] = $value['manday'];
                $data1[$key]['PhasesFee'] = $value['PhasesFee'];
                $data1[$key]['coder'] = $value['coder'];
                $data1[$key]['remark'] = $value['remark'];
                $res2 = M('cst_new_dev_phases') ->add($data1[$key]);
              }
              
            }

            if($res&&$res2){
              action_log('newproduct_plan_add','dev',UID,UID);
              $this->success('添加成功！',U('newDevList'));
            }else {
                $this->error('添加失败！');
            }

            // $this->display();

        }else{
            // var_dump(I());
            // die();
            /*保存新产品基础信息*/
            $map['id'] = I('id');
            $data['product_type'] = I('product_type'); 
            $data['product_name'] = I('product_name'); 
            if(I('ac_time')){
              $data['ac_time'] = I('ac_time');
            } 
            if(I('end_time')){
              $data['end_time'] = I('end_time'); 
            } 
            if(I('start_time')){
              $data['start_time'] = I('start_time');
            } 

            $data['manday'] = I('manday'); 
            $data['version'] = I('version'); 
            $data['dev_role'] = I('dev_role');
            $data['product_charge'] = I('product_charge');  
            $data['describe'] = I('describe');
            $data['update_person'] = UID;
            $data['update_time'] = date("Y-m-d h:m:s",time());
            $data['remark'] = I('remark'); 
            $data['accessory'] = I('accessory'); 

            /* 调用文件上传组件上传文件 */
            $File = D('File');
            $info = $File->upload($_FILES, C('DOWNLOAD_UPLOAD')); //TODO:上传到远程服务器
            if($info){
              $data['accessory'] = "http://oa.gdwstech.com/Uploads/Download/".$info['accessory']['savepath'].$info['accessory']['savename'];
            }
            // var_dump($info);
            // die();
            $res = M('cst_dev_newproduct') ->where($map) ->save($data);

            /*删除新产品阶段并新增*/
            $plan_code = M('cst_dev_newproduct') ->field('plan_code')->where($map) ->find();
            $map2['plan_code'] = $plan_code['plan_code'];
            $res2 = M('cst_new_dev_phases') ->where($map2)->delete();
            $phases = I('data');
            foreach ($phases as $key => $value) {
              $data1[$key]['plan_code'] = $plan_code['plan_code'];
              $data1[$key]['phases'] = $key+1;
              $data1[$key]['product'] = I('product_name');
              $data1[$key]['product_jd'] = $value['product_jd'];
              if($value['stime'] !== ''){
                $data1[$key]['stime'] = $value['stime'];
              }
              if($value['etime'] !== ''){
                $data1[$key]['etime'] = $value['etime'];
              }
              $data1[$key]['manday'] = $value['manday'];
              $data1[$key]['PhasesFee'] = $value['PhasesFee'];
              $data1[$key]['coder'] = $value['coder'];
              $data1[$key]['remark'] = $value['remark'];
              $data1[$key]['charge_person'] = $value['charge_person'];
              // var_dump($data1);
              // die();
              $res3 = M('cst_new_dev_phases') ->add($data1[$key]);
            }

            if($res3||$res){
              action_log('newproduct_plan_up','dev',UID,UID);
              $this->success('更新成功！',U('newDevList'));
            }else{
              $this->error('更新失败！');
           
            }

        }
      }
    }

    /*Ajax根据开发阶段拉去相应负责人*/
    public function getChargeperson(){
      $type = I('product_jd');
      if($type == '产品设计'){
        /*拉取产品负责人*/
        $map22['ac.group_id'] = 8;
        $role = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map22)
                ->select();   
      }elseif($type == '产品开发'){
        /*拉取开发负责人*/
        $map11['ac.group_id'] = 10;
        $role = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map11)
                ->select();   
      }elseif($type == '产品测试及发布'){
        /*拉取测试负责人*/
        $map11['ac.group_id'] = 16;
        $role = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map11)
                ->select();  
      }
      $this->ajaxReturn($role);
    }

    /*新开发计划删除*/
    public function new_sure(){

      /*获取删除合同数组*/
      $idArray = I('ids');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      $status = I('request.status');
      // var_dump($status);
      // die();

      if($status == '-1'){
        $data1['status'] = '1';
        $res1 = M('cst_dev_newproduct') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('newproduct_plan_sure','dev',UID,UID);
          $this->success('确认成功！',U('newDevList'));
        }else {
            $this->error('确认失败！');
        }
      }elseif($status == '-2'){

        $data1['status'] = '2';
        $data1['ac_time'] = '2';
        $res1 = M('cst_dev_newproduct') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('newproduct_plan_complete','dev',UID,UID);
          $this->success('完成成功！',U('newDevList'));
        }else {
            $this->error('完成失败！');
        }
      }elseif($status == '-3'){

        $data1['status'] = '3';
        $res1 = M('cst_dev_newproduct') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('newproduct_plan_cancel','dev',UID,UID);
          $this->success('终止成功！',U('newDevList'));
        }else {
            $this->error('终止失败！');
        }
      }elseif($status == '-4'){

        $data1['status'] = '4';
        $res1 = M('cst_dev_newproduct') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('newproduct_plan_delay','dev',UID,UID);
          $this->success('延期成功！',U('newDevList'));
        }else {
          $this->error('延期失败！');
        }
      }


      
    }

    
    /*新开发计划详情*/
    public function new_devMess(){
      $map['id'] = I('id');
      $res = M('cst_dev_newproduct as ne')
                    ->field('ne.*,me.nickname')
                    ->join('left join oa_member as me on me.uid = ne.dev_role')
                    ->where($map)
                    ->find();
      $this ->assign('newproduct',$res);
      // var_dump($res);
      $this->display();
    }

    

    /*定制开发计划列表*/
    public function customization(){

      /*获取分页*/
      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;
      
      if(isset($_GET['project_name']) ){
          $map['cu.project_name']    =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['status']) ){
          $map['cu.status']   =   array('like', $_GET['status']);
      }
      $this ->assign('status',$_GET['status']);
      $pageindex['status']=$_GET['status'];
      $pageindex['project_name']=$_GET['project_name'];

      /*获取列表*/
      $customizations = M("cst_dev_customization as cu")
                    ->field('cu.contract_code,cu.id,cu.plan_code,cu.status,cu.project_name,cu.products,cu.pr_role,cu.Need_time,cu.Sure_time,cu.eEnd_time,cu.End_time,cu.create_time,cus.customer,cu.fee,cu.dev_role,me.nickname as create_person')
                    ->join('left join oa_member as me on me.uid = cu.create_person')
                    ->join('left join oa_cst_cti_project as pr on cu.project_id = pr.id')
                    ->join('left join oa_cst_customer as cus on cus.id = pr.customer')
                    ->where($map)
                    ->order('cu.create_time desc,cu.Need_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      // var_dump($customizations);
      // var_dump(M()->getLastSql());
      $this ->assign('customizations',$customizations);

      $count=M("cst_dev_customization as cu")
                    // ->field('cu.id,cu.plan_code,cu.status,cu.project_name,cu.products,me.nickname as dev_role,cu.pr_role,cu.Need_time,cu.Sure_time,cu.eEnd_time,cu.End_time,cu.create_person,cu.create_time,cus.customer')
                    // ->join('left join oa_member as me on me.uid = cu.dev_role')
                    // ->join('left join oa_cst_cti_project as pr on cu.project_id = pr.id')
                    // ->join('left join oa_cst_customer as cus on cus.id = pr.customer')
                    ->where($map)
                    // ->order('cu.create_time desc')
                    ->count();
      // var_dump(M()->getLastSql());
      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }

      $this ->display();
    }

    /*定制开发计划录入*/
    public function customization_add(){
      if(IS_POST){
        $maxid = M("cst_dev_customization")
                    ->field('id')
                    ->order('id desc')
                    ->find();
        /*定制化表添加*/
        $data['plan_code'] = "KF".date("Ymd",time()).sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
        $data['project_id'] = I('project_id'); 
        $mm['id'] = I('project_id');
        $pr = M('cst_cti_project')->field('project_name')->where($mm)->find();
        $data['project_name'] = $pr['project_name'];
        $data['contract_code'] = I('contract_code');
        $data['customer'] = I('customer'); 
        $data['products'] = implode('、',I('products')); 

        if(I('Need_time')){
          $data['Need_time'] = I('Need_time'); 

        }
        if(I('Sure_time')){
          
          $data['Sure_time'] = I('Sure_time'); 
        }
        if(I('eEnd_time')){
          $data['eEnd_time'] = I('eEnd_time'); 
          
        }
        if(I('End_time')){
          $data['End_time'] = I('End_time'); 

        }
        $data['status'] = I('status');

        $data['pr_manday'] = I('pr_manday'); 
        $data['dev_manday'] = I('dev_manday'); 
        $data['te_manday'] = I('te_manday'); 
        $data['manday'] = I('pr_manday')+I('dev_manday')+I('te_manday'); 
        $data['pr_role'] = I('pr_role'); 
        $data['dev_role'] = I('dev_role'); 
        
        $data['remark'] = I('remark'); 
        // $data['accessory'] = I('accessory'); 
        $data['create_person'] = UID;
        $data['create_time'] = date("Y-m-d h:m:s",time());

        /* 调用文件上传组件上传文件 */
        $File = D('File');
        $info = $File->upload($_FILES, C('DOWNLOAD_UPLOAD')); //TODO:上传到远程服务器
        if($info){
          $data['accessory'] = "http://oa.gdwstech.com/Uploads/Download/".$info['accessory']['savepath'].$info['accessory']['savename'];
        }

        // var_dump($info);
        // die();
        $res = M('cst_dev_customization') ->add($data);
        // var_dump(M()->getLastSql());
        if($res){
          action_log('customization_add','dev',UID,UID);
          $this->success('添加成功！',U('customization'));
        }else {
            $this->error('添加失败！');
        }
      }else{
        // if(I('id')){
            /*定制化表编辑*/
            $map['id'] = I('id');
            $res = M('cst_dev_customization') ->where($map)->find();
            $this->assign('customization',$res);
            // var_dump($res);
            // die();
        // }else{

          /*拉取客户*/
          $mmmm['pr.status'] = '2';
          $customers = M("cst_customer as cu") 
                          ->field('cu.id,cu.customer')
                          ->join('left join oa_cst_cti_project as pr on pr.customer = cu.id')
                          ->where($mmmm)
                          ->select();
          $this->assign('customers',$customers);

          /*拉取产品*/
          $products = M("cst_product") ->field('id,product_name')->order('product_name asc')->select();

          
          /*拉取实施负责人*/
          $map1['ac.group_id'] = 9;
          $techs = M('member as me')
                  ->field('me.uid,me.nickname')
                  ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                  ->where($map1)
                  ->select();   
          $this->assign('techs',$techs);

          /*拉取开发负责人*/
          $map1['ac.group_id'] = 10;
          $devs = M('member as me')
                  ->field('me.uid,me.nickname')
                  ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                  ->where($map1)
                  ->select();   
          $this->assign('devs',$devs);

          /*拉取开发人员*/
          $map1['ac.group_id'] = 11;
          $coders = M('member as me')
                  ->field('me.uid,me.nickname')
                  ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                  ->where($map1)
                  ->select();   
          $this->assign('coders',$coders);

          $this->assign('products',$products);
          // var_dump($devs);
          // $this->assign('project',$p);
        // }
        $this->display();
        
      }
    }

    /*定制开发详情*/
    public function customizationMess(){
      if(IS_POST){
        $map1['id'] = I('id');
        $map['status'] = I('status');
        $map['End_time'] = I('End_time');
        $map['remark'] = I('remark');
        // var_dump(I());
        // die();
        $res = M('cst_dev_customization') ->where($map1) ->save($map);
        if($res){
          action_log('customization_up','dev',UID,UID);
          $this->success('修改成功！',U('customization'));
        }else{
          $this->error('修改失败！');
        }
      }else{
        $map['cst.id'] = I('id');
        $res = M('cst_dev_customization as cst') 
                ->field('cst.*,cu.customer as cum')
                ->join('left join oa_cst_customer as cu on cu.id = cst.customer')
                ->where($map) 
                ->find();
        $this ->assign('customization',$res);
        $this->display();
      }
      
    }

    /*定制开发确认*/
    public function cus_sure(){

      $status = I('request.status');
      /*获取删除合同数组*/
      $idArray = I('ids');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);

      if($status == '-1'){
        $data1['status'] = '1';
        $data1['Sure_time'] = date('Y-m-d',time());
        $res1 = M('cst_dev_customization') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('customization_need','dev',UID,UID);
          $this->success('需求分析确认！',U('customization'));
        }else {
            $this->error('确认失败！');
        }
      }elseif($status == '-2'){
        $data1['status'] = '2';
        $data1['End_time'] = date('Y-m-d',time());
        $res1 = M('cst_dev_customization') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('customization_dev','dev',UID,UID);
          $this->success('开发确认！',U('customization'));
        }else {
            $this->error('确认失败！');
        }
      }elseif($status == '-3'){

        $data1['status'] = '3';
        $res1 = M('cst_dev_customization') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('customization_delivery','dev',UID,UID);
          $this->success('交付成功！',U('customization'));
        }else {
            $this->error('交付失败！');
        }
      }elseif($status == '-4'){

        $data1['status'] = '4';
        $res1 = M('cst_dev_customization') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('customization_suspend','dev',UID,UID);
          $this->success('暂停成功！',U('customization'));
        }else {
            $this->error('暂停失败！');
        }
      }elseif($status == '-5'){

        $data1['status'] = '5';
        $res1 = M('cst_dev_customization') ->where($map)->save($data1);
        // var_dump(M()->getLastSql());
        // die();
        if($res1){
          action_log('customization_cancel','dev',UID,UID);
          $this->success('终止成功！',U('customization'));
        }else {
            $this->error('终止失败！');
        }
      }

    }

    /*AJAX根据客户拉去项目列表*/
    public function getProject(){
      $map['customer'] = I('customer');
      $projects = M("cst_cti_project") ->field('id,project_name') ->where($map)->select();
      $this ->ajaxReturn($projects);
      // $this ->ajaxReturn(M()->getLastSql());
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

    /*开发交付首页*/
    public function dev_delivery(){
        /*获取分页*/
        $pageindex['p'] = $_GET["p"];
        if (empty($pageindex['p'])||$pageindex['p']=="0") {
            $pageindex['p']=1;
        }
        $pagesize = PAGESIZE;


        /*获取列表*/
        if(isset($_GET['project_name']) ){
          $map['p.project_name']    =   array('like', '%'.$_GET['project_name'].'%');
        }
        if(isset($_GET['contract_code']) ){
            $map['de.contract_code']   =   array('like', '%'.$_GET['contract_code'].'%');
        }
        if(isset($_GET['status']) ){
          $map['de.delivery_type']   =   array('like', $_GET['status']);
          $pageindex['status']=$_GET['status'];
        }
        // var_dump($_GET['status']);
        $this ->assign('status',$_GET['status']);
        $deliverys = M("cst_dev_delivery as de")
                      ->field('de.contract_code,de.delivery_type,de.id,de.delivery_code,p.project_name,de.del_date,p.project_type,p.province,de.tech_role,de.project_productlist,de.deliver_product,de.deliver_version,de.dev_role,de.describe,me.nickname as create_person,de.create_time,de.update_person,de.update_time')
                      ->join('left join oa_cst_cti_project as p on p.id = de.project_id')
                      ->join('left join oa_cst_customer as oc on p.customer = oc.id')
                      ->join('left join oa_member as me on me.uid = de.create_person')
                      ->where($map)
                      ->order('de.create_time desc')
                      ->page($pageindex['p'], $pagesize)
                      ->select();
        // var_dump($deliverys);
        // var_dump(M()->getLastSql());
        $this ->assign('deliverys',$deliverys);

        $count=M("cst_dev_delivery as de")
                      ->join('left join oa_cst_cti_project as p on p.id = de.project_id')
                      ->join('left join oa_cst_customer as oc on p.customer = oc.id')
                      ->join('left join oa_member as me on me.uid = de.create_person')
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

    /*开发交付录入*/
    public function dev_delivery_add(){
      if(IS_POST){
        $maxid = M("cst_dev_delivery")
                    ->field('id')
                    ->order('id desc')
                    ->find();
        /*新产品表添加*/
        $data['delivery_code'] = "JF".date("Ymd",time()).sprintf("%02d", $maxid['id']+1);//生成4位数，不足前面补0 
        $data['delivery_type'] = I('delivery_type');
        $data['project_id'] = I('project_id');
        $data['contract_code'] = I('contract_code');
        $data['project_productlist'] = I('project_productlist');
        $data['deliver_product'] = implode(",", I('deliver_product'));
        $data['deliver_version'] = I('deliver_version');
        $data['tech_role'] = I('tech_role');
        $data['dev_role'] = I('dev_role');
        $data['describe'] = I('describe');
        if(I('del_date')){
          $data['del_date'] = I('del_date');

        }else{
          $data['del_date'] = date("Y-m-d",time());
        }
        $data['create_person'] = UID;
        $data['create_time'] = date("Y-m-d H:i:s",time());

        // var_dump(I());
        // die();
        
        $res = M("cst_dev_delivery")->add($data);
        if($res){
          action_log('dev_delivery_add','dev',UID,UID);
          $this->success('添加成功！',U('dev_delivery'));
        }else {
            $this->error('添加失败！');
        }
      }else{
         /*拉去项目*/
        $projects = M('cst_cti_project') ->field('id,project_name')->select();
        // var_dump($projects);
        foreach ($projects as $key => $value) {
          $tmap['project_id'] = $value['id'];
          $tr = M('cst_ctp_transfer') ->where($tmap) ->find();
          if(!$tr){
            unset($projects[$key]);
          }
        }
        // var_dump($projects);
        $this ->assign('projects',$projects);

        /*拉去产品*/
        $products = M('cst_product') ->field('id,product_name')->select();
        $this ->assign('products',$products);
        // var_dump($products);
        $this ->display();
      }
      
    }

    /*开发交付详情*/
    public function dev_delivery_mess(){
      $map['de.id'] = I('id');
      $delivery = M("cst_dev_delivery as de")
                    ->field('de.delivery_code,de.delivery_type,pr.project_name,pr.project_type,pr.province,de.contract_code,de.project_productlist,de.deliver_product,de.tech_role,de.deliver_version,de.del_date,de.dev_role,de.describe')
                    ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
                    ->join('left join oa_cst_ctp_transfer as tr on tr.contract_code = de.contract_code')
                    ->where($map)
                    ->find();
      switch ($delivery['delivery_type']) {
        case '1':
          $delivery['delivery_type'] = '需求新增';
          break;
        case '2':
          $delivery['delivery_type'] = 'bug修复';
          break;
        
        default:
          $delivery['delivery_type'] = '需求新增';
          break;
      }
      // var_dump($delivery);
      // var_dump(M()->getLastSql());
      $this ->assign('delivery',$delivery);
      $this ->display();
      
    }

    /*AJAX根据项目id拉去项目列表*/
    public function getProjectBypid(){
      $map['pr.id'] = I('project_id');
      $projects = M("cst_cti_project as pr") 
                        ->field('pr.id,pr.project_name,pr.project_type,pr.province,me.nickname as TechRole,tr.project_productlist') 
                        ->join('left join oa_cst_ctp_transfer as tr on tr.project_id = pr.id')
                        ->join('left join oa_member as me on me.uid = tr.TechRole')
                        ->where($map)
                        ->find();

      $map1['co.project_id'] = I('project_id');
      $contract = M("cst_contract as co")
                        ->field('co.id,co.contract_code')
                        ->where($map1)
                        ->select();
      $projects['contract'] = $contract;
      $this ->ajaxReturn($projects);
      // $this ->ajaxReturn(M()->getLastSql());
    }

    /*AJAX根据合同id拉去交付产品*/
    public function getProductsBycid(){
      $map['tr.contract_code'] = I('contract_code');
      
      $project_productlist = M("cst_ctp_transfer as tr")
                        ->field('tr.id,tr.project_productlist')
                        ->where($map)
                        ->find();         
      $this ->ajaxReturn($project_productlist);
    }

    /*项目版本号管理*/
    public function version(){
      // echo 13;
      $this ->display();
    }

    /*项目版本号新增*/
    public function version_add(){
      if(IS_POST){

      }else{
        $map['status'] = 2;
        $res = M('cst_cti_project')->field('id,project_name')->where($map)->select();
        // var_dump($res);
        $this ->assign('projects',$res);
        $this ->display();
      }
    }


}

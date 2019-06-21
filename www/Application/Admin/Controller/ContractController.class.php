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

/*合同控制器*/
class ContractController extends AdminController {
  
    /*默认首页*/
    public function first(){
      $this ->display();
    }

    /*意向合同首页*/
    public function intention(){

      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;

      // /*获取列表*/
      // $map['cu.customer'] = array('like', '%' . (string)I('customer') . '%');
      // $map['p.project_name'] = array('like', '%' . (string)I('project_name') . '%');
      // $map['co.status'] = I('status');
      // $map['p.province'] = I('province');
      // $map['p.purchase_intention'] = array('like', '%' . (string)$key3 . '%');
      
      if(isset($_GET['customer']) ){
          $map['cu.customer']   =   array('like', '%'.$_GET['customer'].'%');
      }
      if(isset($_GET['project_name']) ){
          $map['p.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['province']) ){
          $map['p.province']   =   array('like', '%'.$_GET['province'].'%');
      }
     
      if($_GET['status'] == '1'){
          $map['co.status']  =   $_GET['status'];
          $pageindex['status'] = $_GET['status'];
      }else{
           $map['co.status']  =   array('in', '0,1');
      }
      // var_dump($map);
      $contracts = M("cst_contract as co")
                    ->field('co.Contract_unit,co.softProject,co.contract_fee,co.contract_productlist,co.id,co.status,co.start_time,co.end_time,co.intention_contract_code,co.create_time,mem.nickname as nic,co.updata_time,co.updata_person,cu.customer,p.project_name,p.province,p.purchase_intention,p.budget,me.nickname')
                    ->join('left join oa_cst_cti_project as p on p.id = co.project_id')
                    ->join('left join oa_cst_customer as cu on p.customer = cu.id')
                    ->join('left join oa_member as me on me.uid = co.charge_person')
                    ->join('left join oa_member as mem on mem.uid = co.create_person')
                    ->where($map)
                    ->order('co.create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->order('create_time desc')
                    ->select();
      foreach ($contracts as $key6 => $value6) {
        $contracts[$key6]['contract_fee'] = number_format ($value6['contract_fee'] , 2 , '.' , ',' );
      }
      // var_dump($res);
      // var_dump(M()->getLastSql());
      $this->assign('contracts',$contracts);
      $count=M("cst_contract as co")
              ->join('left join oa_cst_cti_project as p on p.id = co.project_id')
              ->join('left join oa_cst_customer as cu on p.customer = cu.id')
              ->where($map)
              ->count();

      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }

      /*获取意向产品*/
      $inp = M("cst_product")->select();
      // var_dump($inp);
      // var_dump(M()->getLastSql());
      $this->display();
    }

    /*添加意向合同*/
    public function add(){

      if(!IS_POST){
        /*拉取客户*/
        $customers = M("cst_customer") ->field('id,customer')->select();
        $this->assign('customers',$customers);

        /*拉取产品*/
        $products = M("cst_product") ->field('id,product_name')->order('product_name asc')->select();

        $this->assign('products',$products);

        /*获取商务负责人*/
        $map1['ac.group_id'] = 19;
        $charge_persons = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();
        $this->assign('charge_persons',$charge_persons);

        $this->display();

      }else{

        // var_dump(I());
        // die();
        $maxid = M("cst_contract")
                    ->field('id')
                    ->order('id desc')
                    ->find();

        /*合同表添加*/
        $data['project_id'] = I('project_id');
        
        $data['intention_contract_code'] = "YX".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
        /*采购产品列表*/  
        $data['contract_productlist'] = implode(",", I('contract_productlist'));
        $data['contract_fee'] = I('contract_fee');  
        // $data['province'] = I('province')."-".I('city');  
        $data['softProject'] = I('softProject');  
        $data['Contract_unit'] = I('Contract_unit');
        if(I('start_time')){
          $data['start_time'] = I('start_time');
        }
        if(I('end_time')){
          $data['end_time'] = I('end_time');  
        }
        $data['remark'] = I('remark');
        $data['status'] = 1;  
        $data['create_person'] = UID;  
        $data['create_time'] = date("Y-m-d H:i:s",time());
        $data['charge_person'] = I('charge_person');  
        // var_dump(I());
        // die();
        
        /* 调用文件上传组件上传文件 */
        $File = D('File');
        $info = $File->upload($_FILES, C('DOWNLOAD_UPLOAD')); //TODO:上传到远程服务器
        if($info){
          $data['accessory'] = "http://oa.gdwstech.com/Uploads/Download/".$info['accessory']['savepath'].$info['accessory']['savename'];
        }
        // var_dump($data);
        // die();
        $res = M('cst_contract') ->add($data);
        $contract_id = M()->getLastInsId();
        
        if($res){

          /*更新项目表，项目状态*/
          $map['id'] = I('project_id');
          $data2['status'] = '1';
          $res = M('cst_cti_project') ->where($map)->save($data2);

          /*项目费用周期表添加*/
          $phases = I('data');
          foreach ($phases as $key => $value) {
            $data1['project_id'] = I('project_id');
            $data1['contract_id'] = $contract_id;
            $data1['phases'] = $key+1;
            $data1['fee'] = $value['fee'];
            if(I('stime')){
              $data1['stime'] = $value['stime'];
            }
            if(I('etime')){
              $data1['etime'] = $value['etime'];
            }
            $data1['products'] = implode(",", $value['products']);
            $data1['remark'] = $value['remark'];
            $res1 = M('cst_pj_phases') ->add($data1);
          }
          action_log('intention_add','contract',UID,UID);
          $this->success('添加成功！',U('intention'));
        }else {
            $this->error('添加失败！');
        }
      }
 
    }

    /*AJAX根据客户拉去项目列表*/
    public function getProject(){
      $map['customer'] = I('customer');
      $map['status'] = array('in','0,1,2');
      $projects = M("cst_cti_project") ->field('id,project_name') ->where($map)->select();
      $this ->ajaxReturn($projects);
      // $this ->ajaxReturn(M()->getLastSql());
    }

    /*AJAX根据客户拉去项目列表*/
    public function getProjectMess1(){
      $map['id'] = I('id');
      $projects = M("cst_cti_project") ->where($map)->find();
      $this ->ajaxReturn($projects);
      // $this ->ajaxReturn(M()->getLastSql());
    }

    /*意向合同作废*/
    public function inten_delete(){
      /*获取删除合同数组*/
      $idArray = I('ids');
      $data['status'] = '0';
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      $res = M("cst_contract")->where($map)->save($data);
      if($res){
          action_log('intention_cancel','contract',UID,UID);
          $this->success('作废成功！',U('intention'));
      }else {
          $this->error('作废失败！');
      }

      // var_dump(M()->getLastSql());
    }


    /*意向合同确认并转入正式合同等待生效*/
    public function inten_sure(){
   
      /*获取删除合同数组*/
      $idArray = I('ids');
      foreach ($idArray as $key => $value) {
          $map['id'] = $value;
          $res1 = M("cst_contract")->field('intention_contract_code')->where($map)->find();
          $data['contract_code'] = str_replace("YX","HT",$res1['intention_contract_code']);
          $data['contract_type'] = 1;
          $data['status'] = 2;
          $res = M("cst_contract")->where($map)->save($data);
      }
       if($res){
                action_log('intention_tran','contract',UID,UID);
                $this->success('确认完成已生成正式合同！',U('intention'));
        }else {
            $this->error('确认失败！');
        }

    }


    /*意向合同详情*/
    public function intention_mess(){

      if(IS_POST){
        /*合同表添加*/
        // $data['id'] = I('id');
        
        // $data['intention_contract_code'] = "YX".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
        /*采购产品列表*/  
        $data['contract_productlist'] = implode(",", I('contract_productlist'));
        $data['contract_fee'] = I('contract_fee');  
        // $data['province'] = I('province')."-".I('city');  
        // $data['softProject'] = I('softProject');  
        $data['Contract_unit'] = I('Contract_unit');

        if(I('start_time')){
          $data1['start_time'] = $value['start_time'];
        }
        if(I('end_time')){
          $data1['end_time'] = $value['end_time'];
        }

        $data['remark'] = I('remark');
        // $data['status'] = 1;  
        $data['updata_person'] = UID;  
        $data['updata_time'] = date("Y-m-d H:i:s",time());
        // $data['charge_person'] = I('charge_person');  
        // var_dump(I());
        // die();
        
        /* 调用文件上传组件上传文件 */
        $File = D('File');
        $info = $File->upload($_FILES, C('DOWNLOAD_UPLOAD')); //TODO:上传到远程服务器
        if($info){
          $data['accessory'] = "http://oa.gdwstech.com/Uploads/Download/".$info['accessory']['savepath'].$info['accessory']['savename'];
        }
        // var_dump($data);
        // die();
        $mmm['id'] = I('id');
        // var_dump(I('id'));
        // die();
        $res = M('cst_contract') ->where($mmm)->save($data);
      
        /*项目费用周期删除*/
        $nn['contract_id'] = I('id');
        $res1 = M('cst_pj_phases') ->where($nn)->delete();
        /*项目费用周期表添加*/
        $phases = I('data');
        foreach ($phases as $key => $value) {
          // $data1['project_id'] = I('project_id');
          $data1['contract_id'] = I('id');
          $data1['phases'] = $key+1;
          $data1['fee'] = $value['fee'];
          if(I('stime')){
            $data1['stime'] = $value['stime'];
          }
          if(I('etime')){
            $data1['etime'] = $value['etime'];
          }
          $data1['products'] = implode(",", $value['products']);
          $data1['remark'] = $value['remark'];
          $res1 = M('cst_pj_phases') ->add($data1);
        }
        if($res||$res1){
          action_log('intention_up','contract',UID,UID);
          $this->success('更新成功！',U('intention'));
        }else {
            $this->error('更新失败！');
        }
      }else{
        /*拉取客户*/
        $customers = M("cst_customer") ->field('id,customer')->select();
        $this->assign('customers',$customers);

        /*拉取产品*/
        $products = M("cst_product") ->field('id,product_name')->select();

        $this->assign('products',$products);

        /*获取商务负责人*/
        $map1['ac.group_id'] = 19;
        $charge_persons = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();
        $this->assign('charge_persons',$charge_persons);

        $map['co.id'] = I('id');
        $contracts = M("cst_contract as co")
                      ->field('co.remark,co.accessory,co.id,co.Contract_unit,co.softProject,pr.province as prv,co.project_id,co.intention_contract_code,cu.customer,pr.project_name,co.contract_productlist,co.contract_fee,co.province,co.start_time,co.end_time,me.nickname as charge_person')
                      ->join('left join oa_cst_cti_project as pr on pr.id = co.project_id')
                      ->join('left join oa_cst_customer as cu on cu.id = pr.customer')
                      ->join('left join oa_member as me on co.charge_person = me.uid')
                      ->where($map)
                      ->find();
        // var_dump($contracts);
        // echo 123;
        // var_dump(M()->getLastSql());
        $map2['contract_id'] = $contracts['id'];
        $phases = M('cst_pj_phases')->where($map2)->order('phases asc')->select();
        // var_dump(M()->getLastSql());
        $this ->assign('contracts',$contracts);
        $this ->assign('phases',$phases);
        // var_dump($phases);
        $this ->display();
      }

      
    }



    /*正式合同列表*/
    public function official(){
      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;

      if(isset($_GET['customer']) ){
          $map['cu.customer']   =   array('like', '%'.$_GET['customer'].'%');
      }
      if(isset($_GET['project_name']) ){
          $map['p.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['province']) ){
          $map['p.province']   =   array('like', '%'.$_GET['province'].'%');
      }
     
      if($_GET['status'] == '2'||$_GET['status'] == '3'||$_GET['status'] == '4'){
          $map['co.status']  =   $_GET['status'];
          $pageindex['status']=$_GET['status'];
          $this -> assign('status',$_GET['status']);
      }else{
           $map['co.status']  =   array('in', '2,3,4');
      }
      // var_dump($map);

      $contracts = M("cst_contract as co")
                    ->field('co.Contract_unit,co.softProject,co.id,co.contract_productlist,co.status,co.start_time,co.end_time,co.intention_contract_code,co.contract_code,co.contract_fee,co.create_time,mem.nickname as nic,co.updata_time,co.updata_person,cu.customer,p.project_name,p.province,p.purchase_intention,me.nickname')
                    ->join('left join oa_cst_cti_project as p on p.id = co.project_id')
                    ->join('left join oa_cst_customer as cu on p.customer = cu.id')
                    ->join('left join oa_member as me on me.uid = p.charge_person')
                    ->join('left join oa_member as mem on mem.uid = co.create_person')
                    ->where($map)
                    // ->order('create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->order('create_time desc')
                    ->select();
      foreach ($contracts as $key6 => $value6) {
        $contracts[$key6]['contract_fee'] = number_format ($value6['contract_fee'] , 2 , '.' , ',' );
      }
      // var_dump($res);
      // var_dump(M()->getLastSql());
      $this->assign('contracts',$contracts);
      $count=M("cst_contract as co")
              ->join('left join oa_cst_cti_project as p on p.id = co.project_id')
              ->join('left join oa_cst_customer as cu on p.customer = cu.id')
              ->where($map)
              ->count();

      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }

      /*获取意向产品*/
      $inp = M("cst_product")->select();
      // var_dump($inp);
      // var_dump(M()->getLastSql());
      $this->display();
    
    }

    /*正式合同生效确认*/
    public function contract_sure(){
      /*获取合同数组*/
      $idArray = I('ids');
      foreach ($idArray as $key => $value) {
          $map['id'] = $value;
          $data['status'] = 3;
          $res = M("cst_contract")->where($map)->save($data);
          $res1 =  M("cst_contract")->field('project_id')->where($map)->find();
          $projects[] = $res1['project_id'];
      }
      if($res){
            /*生成正式合同后，跟踪项目状态转换为已签约*/
            $map1['id'] = array('in', $projects);
            $data1['status'] = '2';
            $res2 = M("cst_cti_project")->where($map1)->save($data1);
            action_log('official_effect','contract',UID,UID);
            $this->success('生效成功！',U('official'));
      }else {
          $this->error('生效失败！');
      }
    }

    /*正式合同终止*/
    public function contract_termination(){
      /*获取删除合同数组*/
      $idArray = I('ids');
      // var_dump($idArray);
      // die();
      foreach ($idArray as $key => $value) {
          $map['id'] = $value;
          $data['status'] = 4;
          $res = M("cst_contract")->where($map)->save($data);
      }
      // var_dump(M()->getLastSql());
      // die();
      if($res){
          action_log('official_cancel','contract',UID,UID);
          $this->success('终止成功！',U('official'));
      }else {
          $this->error('终止失败！');
      }
    }

    /*正式合同详情*/
    public function official_mess(){
      $map['co.id'] = I('id');
      $contracts = M("cst_contract as co")
                    ->field('co.remark,co.accessory,co.id,co.Contract_unit,co.softProject,pr.province as prv,co.project_id,co.contract_code,cu.customer,pr.project_name,co.contract_productlist,co.contract_fee,co.province,co.start_time,co.end_time,me.nickname as charge_person')
                    ->join('left join oa_cst_cti_project as pr on pr.id = co.project_id')
                    ->join('left join oa_cst_customer as cu on cu.id = pr.customer')
                    ->join('left join oa_member as me on co.charge_person = me.uid')
                    ->where($map)
                    ->find();
      // var_dump($contracts);
      // echo 123;
      // var_dump(M()->getLastSql());
      $map2['contract_id'] = $contracts['id'];
      $phases = M('cst_pj_phases')->where($map2)->select();
      $this ->assign('contracts',$contracts);
      $this ->assign('phases',$phases);
      // var_dump($phases);
      $this ->display();
    }
    

    /*项目交接记录列表*/
    public function contract_transfer(){
      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;


      // /*获取列表*/
      // $map['p.customer'] = array('like', '%' . (string)I('customer') . '%');
      // $map['p.project_name'] = array('like', '%' . (string)I('project_name') . '%');
      // $map['tr.status'] = I('status');
      // $map['co.province'] = I('province');
      // $map['tr.project_productlist'] = array('like', '%' . (string)$key3 . '%');

      // $map['_logic'] = 'or';

      if(isset($_GET['customer']) ){
          $map['cu.customer']   =   array('like', '%'.$_GET['customer'].'%');
      }
      if(isset($_GET['project_name']) ){
          $map['p.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['province']) ){
          $map['p.province']   =   array('like', '%'.$_GET['province'].'%');
      }
     
      if(isset($_GET['status'])){
          $map['tr.status']  =   $_GET['status'];
      }else{
           $map['tr.status']  =   array('in', '1,2,3,4');
      }
      // var_dump($map);

      $transfers = M("cst_ctp_transfer as tr")
                    ->field('tr.id,tr.transfer_code,tr.status,cu.customer,p.project_name,co.contract_code,co.contract_fee,p.project_type,co.province,co.contract_productlist,me.nickname as charge_person,tr.ProjectRishLevel,mem.nickname as create_person,tr.create_time,tr.update_person,tr.update_time')
                    ->join('left join oa_cst_contract as co on co.contract_code = tr.contract_code')
                    ->join('left join oa_cst_cti_project as p on p.id = tr.project_id')
                    ->join('left join oa_cst_customer as cu on p.customer = cu.id')
                    ->join('left join oa_member as me on me.uid = p.charge_person')
                    ->join('left join oa_member as mem on mem.uid = tr.create_person')
                    ->where($map)
                    ->order('tr.create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      foreach ($transfers as $key6 => $value6) {
        $transfers[$key6]['contract_fee'] = number_format ($value6['contract_fee'] , 2 , '.' , ',' );
      }
      // var_dump($transfers);
      // var_dump(M()->getLastSql());
      
      $count=M("cst_ctp_transfer as tr")
                    ->field('tr.transfer_code,tr.status,cu.customer,p.project_name,co.contract_code,p.project_type,co.province,co.contract_productlist,co.charge_person,tr.ProjectRishLevel,tr.create_person,tr.create_time,tr.update_person,tr.update_time')
                    ->join('left join oa_cst_contract as co on co.contract_code = tr.contract_code')
                    ->join('left join oa_cst_cti_project as p on p.id = tr.project_id')
                    ->join('left join oa_cst_customer as cu on p.customer = cu.id')
                    ->where($map)
              ->count();

      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }              

      $this ->assign('transfers',$transfers);
      $this ->display();
    }

    /*新增项目交接记录*/
    public function add_transfer(){
      if(IS_POST){

        $maxid = M("cst_ctp_transfer")
                    ->field('id')
                    ->order('id desc')
                    ->find();

        $date = date("Ymd",time());
                  
        /*项目交接表添加*/
        $data['project_id'] = I('project_id');
        /*自动生成交接编号*/
        $data['transfer_code'] = "JJ".$date.sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
        $data['contract_code'] = I('contract_code');

        /*判断该项目是否已经交接过*/
        $map1['contract_code'] = I('contract_code');
        $res11 = M("cst_ctp_transfer")->where($map1) ->find();
        if($res11){
            $this->error('新增失败,该合同已经交接过！');
        }
        
        $data['project_productlist'] = I('project_productlist');
        $data['project_custom'] = implode(",", I('project_custom'));
        $data['keyCustom'] = I('keyCustom');
        $data['link_phone'] = I('link_phone');
        $data['ProjectRishLevel'] = I('ProjectRishLevel');
        $data['ProjectRishDescribe'] = I('ProjectRishDescribe');
        $data['CustomerExpectLevel'] = I('CustomerExpectLevel');
        $data['Project_opponents'] = I('Project_opponents');
        $data['CustomerITNow'] = I('CustomerITNow');
        $data['IsSI'] = I('IsSI');

        if(I('EnterTime')){
          $data['EnterTime'] = $value['EnterTime'];
        }
        if(I('OpenTime')){
          $data['OpenTime'] = $value['OpenTime'];
        }
        
        $data['salesRole'] = I('salesRole');
        $data['ProjectRole'] = I('ProjectRole');
        $data['ProductRole'] = I('ProductRole');
        $data['TechRole'] = I('TechRole');
        $data['DevRole'] = I('DevRole');

        $data['remark'] = I('remark');
        // $data['accessory'] = I('accessory');
        $data['create_person'] = UID;
        $data['create_time'] = date("Y-m-d H:i:s",time());

        /* 调用文件上传组件上传文件 */
        $File = D('File');
        $info = $File->upload($_FILES, C('DOWNLOAD_UPLOAD')); //TODO:上传到远程服务器
        if($info){
          $data['accessory'] = "http://oa.gdwstech.com/Uploads/Download/".$info['accessory']['savepath'].$info['accessory']['savename'];
        }

        // var_dump($data);
        // die();
        $res = M("cst_ctp_transfer") ->add($data);
        // var_dump(M()->getLastSql());
        // die();
        if($res){
            action_log('contract_transfer_add','transfer',UID,UID);
            $this->success('新增成功！',U('contract_transfer'));
        }else {
            $this->error('新增失败！');
        }
      }else{
        $projects = M('cst_cti_project as pr')
                    ->field('pr.id,pr.project_name')
                    ->join('right join oa_cst_contract as co on co.project_id = pr.id')
                    // ->join('right join oa_cst_customer as cu on pr.customer = cu.customer')
                    ->where('co.status = 3')
                    ->select();
        $this ->assign('projects',$projects);
        // var_dump($projects);
        // var_dump(M()->getLastSql());
        
        /*获取销售经理列表*/
        $map1['ac.group_id'] = 7;
        $SalesRoles = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();

        $this->assign('SalesRoles',$SalesRoles);

        /*获取项目经理列表*/
        $map1['ac.group_id'] = 6;
        $ProjectRoles = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();   
        $this->assign('ProjectRoles',$ProjectRoles);

        /*获取产品经理列表*/
        $map1['ac.group_id'] = 8;
        $ProductRoles = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();   
        $this->assign('ProductRoles',$ProductRoles);

        /*获取实施经理列表*/
        $map1['ac.group_id'] = 9;
        $TechRoles = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();   
        $this->assign('TechRoles',$TechRoles);

        /*获取开发经理列表*/
        $map1['ac.group_id'] = 10;
        $DevRoles = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();   
        $this->assign('DevRoles',$DevRoles);

        /*获取客户列表*/
        $customers = M('cst_customer')->field('id,customer')->order('create_time desc')->select();
        // var_dump($customers);
        $this->assign('customers',$customers);
        
        $this->display();
      }
      
      
    }

    /*Ajax获取项目byCustomer*/
    public function getProjectByCustomer(){

      $map['pr.customer'] = I('customer');
      // $map['pr.customer'] = '375';
      $map['pr.status'] = array('neq','3');
      $projects = M('cst_cti_project as pr')
                    ->field('pr.id,pr.project_name')
                    ->where($map)
                    ->select();
      // var_dump($projects);
      // var_dump(M()->getLastSql());
      // die();
      $this ->ajaxReturn($projects);
    }


    /*Ajax获取项目详情*/
    public function getProjectMess(){

      $map['co.project_id'] = I('project_id');
      $map['co.status'] = 3;
      $projects = M('cst_cti_project as pr')
                    ->field('co.id,co.contract_code')
                    // ->field('pr.id,pr.project_name,cu.customer,co.contract_code,co.contract_fee,pr.project_type,pr.discrebe,co.province,co.contract_productlist')
                    ->join('right join oa_cst_contract as co on co.project_id = pr.id')
                    // ->join('left join oa_cst_customer as cu on pr.customer = cu.id')
                    ->where($map)
                    ->select();

      $this ->ajaxReturn($projects);
    }

    /*Ajax获取合同详情*/
    public function getContractMessByContractId(){

      $map['co.contract_code'] = I('contract_code');
      // $map['co.status'] = 3;
      $projects = M('cst_contract as co')
                    ->field('co.contract_fee,pr.project_type,co.contract_productlist,pr.discrebe')
                    ->join('left join oa_cst_cti_project as pr on pr.id = co.project_id')
                    ->where($map)
                    ->find();

      $this ->ajaxReturn($projects);
    }

    // /*作废项目交接记录*/
    // public function delete_transfer(){
    //   /*获取删除合同数组*/
    //   $idArray = I('ids');
    //   // $uidArray = array(4);
    //   $map['id'] = array('in', $idArray);

    //   $res = M("cst_ctp_transfer")->where($map)->delete();
    //   // var_dump(M()->getLastSql());
    //   if($res){
    //       $this->success('删除成功！',U('contract_transfer'));
    //   }else {
    //       $this->error('删除失败！');
    //   }

    // }

    /*确认项目交接记录*/
    public function sure_contract_transfer(){
      /*获取删除合同数组*/
      $idArray = I('ids');
      // $uidArray = array(4);
      $map['tr.id'] = array('in', $idArray);
      $data['tr.status'] =2;
      $res = M("cst_ctp_transfer as tr") 
                ->field('tr.*,pr.project_name')
                ->join('left join oa_cst_cti_project as pr on pr.id = tr.project_id')
                ->where($map)
                ->select();
      // var_dump(M()->getLastSql());
      // die();
      foreach ($res as $key => $value) {
        $smap['username'] = $value ['salesRole'];
        $salesRole = M('ucenter_member') ->field('email')->where($smap) ->find();

        $smap['username'] = $value ['ProductRole'];
        $ProductRole = M('ucenter_member') ->field('email')->where($smap) ->find();

        $smap['username'] = $value ['DevRole'];
        $DevRole = M('ucenter_member') ->field('email')->where($smap) ->find();

        $smap['username'] = $value ['TechRole'];
        $TechRole = M('ucenter_member') ->field('email')->where($smap) ->find();

        $content = "交接编号：".$value['transfer_code']."<br>项目名称：".$value['project_name']."<br>合同编号：".$value['contract_code']."<br>交接产品：".$value['project_productlist']."<br>销售负责人：".$value['salesRole']."<br>产品经理：".$value['ProductRole']."<br>开发经理：".$value['DevRole']."<br>实施经理：".$value['TechRole']."<br><br>-----详情请在系统中查询！！";
        // $ml = "chenlei@gdwstech.com";
        // SendMail($salesRole['email'],'项目交接通知',$content);
        // SendMail($ProductRole['email'],'项目交接通知','23333');
        // SendMail($DevRole['email'],'项目交接通知','23333');
        // SendMail($TechRole['email'],'项目交接通知','23333');
      }
      // var_dump($res);
      // die();
      $res = M("cst_ctp_transfer as tr")->where($map)->save($data);
      if($res){
          /*拉去项目合同交接信息-》拉去收件人mail*/
          $map3['plan_code'] = I('plan_code');
          $tech_charge_person = M('cst_pj_plan') ->field('tech_charge_person')->where($map3) ->find();
          $map4['username'] = $tech_charge_person['tech_charge_person'];
          $em = M('ucenter_member') ->field('email')->where($map4) ->find();
          $ml = "chenlei@gdwstech.com";
          // SendMail($em['email'],$user['nickname'].'新增实施记录',$content);
          action_log('contract_transfer_sure','transfer',UID,UID);
          $this->success('确认成功！',U('contract_transfer'));
      }else {
          $this->error('确认失败！');
      }


    }


    /*作废项目交接记录*/
    public function cancellation_contract_transfer(){
      /*获取删除合同数组*/
      $idArray = I('ids');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      $data['status'] =3;
      $res = M("cst_ctp_transfer")->where($map)->save($data);
      // var_dump(M()->getLastSql());
      if($res){
          action_log('contract_transfer_cancel','transfer',UID,UID);
          $this->success('作废成功！',U('contract_transfer'));
      }else {
          $this->error('作废失败！');
      }


    }

    /*结束项目交接记录*/
    public function end_transfer(){
      /*获取删除合同数组*/
      $idArray = I('id');
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      /*状态转化为结束*/
      $data['status'] = 4;
      $res = M("cst_ctp_transfer")->where($map)->save();
      if($res){
          $this->success('结束成功！',U('contract_transfer'));
      }else {
          $this->error('结束失败！');
      }
      // var_dump(M()->getLastSql());

    }

    /*交接记录添加之项目选择后详情回显*/
    public function project_remess(){
        $map['project_id'] = I('project_id');
        $res = M('cst_cti_project as p') 
                  ->field('')
                  ->join('left join oa_cst_contract as co on co.project_id = p.id')
                  ->join('left join oa_cst_customer as cu on cu.id = p.customer')
                  ->where($map)
                  ->select();
        $this->ajaxReturn($res);

    }

    /*项目交接记录详情*/
    public function transfer_mess(){
      $map['tr.id'] = I('id');
      $transfer = M('cst_ctp_transfer as tr') 
                ->field('pr.project_name,cu.customer,co.contract_code,pr.project_type,pr.province,tr.project_productlist,tr.project_custom,tr.keyCustom,tr.link_phone,tr.ProjectRishLevel,tr.ProjectRishDescribe,tr.CustomerExpectLevel,tr.Project_opponents,tr.CustomerITNow,tr.IsSI,tr.EnterTime,tr.OpenTime,tr.salesRole,tr.ProductRole,tr.TechRole,tr.DevRole,tr.remark,tr.accessory')
                ->join('left join oa_cst_cti_project as pr on tr.project_id = pr.id')
                ->join('left join oa_cst_customer as cu on cu.id = pr.customer')
                ->join('left join oa_cst_contract as co on co.project_id = pr.id')
                // ->join('left join oa_member as m1 on m1.uid = tr.salesRole')
                // ->join('left join oa_member as m2 on m2.uid = tr.ProjectRole')
                // ->join('left join oa_member as m3 on m3.uid = tr.ProductRole')
                // ->join('left join oa_member as m4 on m4.uid = tr.TechRole')
                // ->join('left join oa_member as m5 on m5.uid = tr.DevRole')
                ->where($map)
                ->find();
      // var_dump(M()->getLastSql());
      // var_dump($transfer);
      // echo 123;
      $this ->assign('transfer',$transfer);
      $this ->display();
    }

}

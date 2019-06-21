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

/*项目管理*/
class ProjectController extends AdminController {

    /*项目列表首页*/
    public function index(){
      $umap['uid'] = UID;
      /*拉去昵称*/
      $nickname =  M('member')->field('nickname')->where($umap)->find();
      $groub =  M('auth_group_access')->field('group_id')->where($umap)->select();
      foreach ($groub as $key3 => $value3) {
        $uuu[] = $value3["group_id"];
      }
      if(in_array(4, $uuu)){
        $map['cp.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
        $this ->assign('gid',4);
      }elseif (in_array(7, $uuu)) {
        if(isset($_GET['charge_person']) ){
          $map['cp.charge_person']   =   array('like', '%'.$_GET['charge_person'].'%');
          $pageindex['charge_person']=$_GET['charge_person'];
        }
        $this ->assign('gid',7);
      }else{
        $pageindex['charge_person']=$_GET['charge_person'];
      }


      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;
     
      if(isset($_GET['project_name']) ){
          $map['cp.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['customer']) ){
          $map['oc.customer']   =   array('like', '%'.$_GET['customer'].'%');
          $pageindex['customer']=$_GET['customer'];
      }
      if(isset($_GET['province']) ){
          $map['cp.province']   =   array('like', '%'.$_GET['province'].'%');
          $pageindex['province']=$_GET['province'];
      }
      if(isset($_GET['project_type']) ){
          $map['cp.project_type']   =  $_GET['project_type'];
      }
      
      if(isset($_GET['status'])){
          $map['cp.status']  =   $_GET['status'];
          $pageindex['status']=$_GET['status'];
          $this ->assign('status',$_GET['status']);
      }else{
          $map['cp.status']  =   array('in', '0,1,2,3');
      }

      if(isset($_GET['sort'])){
          // $map['cp.']  =   $_GET['sort'];
          $sort=$_GET['sort'];
          if($sort == '0'){
              $sort1 = 'cp.create_time desc';
          }elseif($sort == '1'){
              $sort1 = 'cp.update_time desc';
          }elseif($sort == '2'){
              $sort1 = 'cp.begin_time desc';
          }elseif($sort == '3'){
              $sort1 = 'cp.open_time desc';
          }
          $pageindex['sort']=$_GET['sort'];
          $this ->assign('sort',$_GET['sort']);

          $pageindex['sort']=$_GET['sort'];
      }else{
          $sort1 = 'cp.update_time desc,cp.create_time desc';
      }
    
      
      $project = M("cst_cti_project as cp")
                    ->field('cp.*,oc.customer as cus,mem.nickname as cre,mem1.nickname as upone')
                    ->join('left join oa_cst_customer as oc on cp.customer = oc.id')
                    // ->join('left join oa_member as me on me.uid = cp.charge_person')
                    ->join('left join oa_member as mem on mem.uid = cp.creator')
                    ->join('left join oa_member as mem1 on mem1.uid = cp.update_one')
                    ->where($map)
                    ->order($sort1)
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      foreach ($project as $key6 => $value6) {
        $project[$key6]['budget'] = number_format ($value6['budget'] , 2 , '.' , ',' );
      }
      $this->assign('project',$project);
      // var_dump(M()->getLastSql());
      $count=M("cst_cti_project as cp")
              ->join('left join oa_cst_customer as oc on cp.customer = oc.id')
              ->where($map)
              ->count();

      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }
      // var_dump($count);
      // var_dump(M()->getLastSql());
      $this->display();
    }

    /*项目添加*/
    public function add(){
        if(IS_POST){
            $maxid = M("cst_cti_project")
                    ->field('id')
                    ->order('id desc')
                    ->find();
            $data['project_code'] = "PJ".sprintf("%06d", $maxid['id']+1);//生成4位数，不足前面补0   
            $data['project_name'] = I('project_name');
            $mmm['project_name'] = I('project_name');
            $res1 = M("cst_cti_project")->where($mmm)->find();
            // var_dump($res1);
            // die();
            if($res1){
              $this->error('添加失败！该项目|商业名称已存在，不能重复添加！');
            }
            $data['project_type'] = I('project_type');
            $data['customer'] = I('customer');
            $data['province'] = I('province').'-'.I('city');
            $data['purchase_intention'] = implode(",", I('purchase_intention'));
            $data['charge_person'] = implode(',',I('charge_person'));
            $data['creator'] = UID;
            $data['create_time'] = date("Y-m-d H:i:s",time());
            $data['last_time'] = date("Y-m-d H:i:s",time());
            $data['budget'] = I('budget');
            if(I('begin_time')){
              $data['begin_time'] = I('begin_time');
            }
            if(I('begin_time')){
              $data['open_time'] = I('open_time');
            }
            $data['discrebe'] = I('discrebe');
            $data['remark'] = I('remark');
            $data['linkman'] = I('linkman');
            $data['position'] = I('position');
            $data['linkphone'] = I('linkphone');
            $res = M("cst_cti_project")->add($data);
            // var_dump(I());
            // die();
            if(!$res){
                action_log('project_add','project',UID,UID);
                $this->error('添加失败！');
            }else {
                $this->success('添加成功！',U('index'));
            }
        } else {
            /*获取客服列表*/
            $map['status'] = '1';
            $customers = M('cst_customer')->field('id,customer') ->where($map)->select();
            $this->assign('customers',$customers);

            /*获取商务负责人*/
            $map1['ac.group_id'] = array('in','4,7');
            $charge_persons = M('member as me')
                    ->field('me.uid,me.nickname')
                    ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                    ->where($map1)
                    ->select();
            $this->assign('charge_persons',$charge_persons);
            // var_dump($charge_persons);
            // var_dump(M()->getLastSql());
            $map2['status'] = array('in','1,2');
            /*获取产品列表*/
            $products = M('cst_product')
                    ->field('id,product_name')
                    ->where($map2)
                    ->order('product_name asc')
                    ->select();
            $this->assign('products',$products);
            $this->display();
        }

    }

    /*终止项目*/
    public function delete(){
     
      $status = I('request.status');
      $idArray = array_unique((array)I('ids'));
      
      $map['id'] = array('in', $idArray);
      if(I('state')){
        $status = I('state');
      }
      if($status == '-1'){
        $data['status'] = '3';
        $data['Ter_reason'] = I('textarea');
        // $data['id'] = I('id');
        $map['id'] = I('id');
        $res = M("cst_cti_project")->where($map)->save($data);
        // // var_dump(M()->getLastSql());
        if(!$res){
            $dd['success'] = -1;
            $dd['message'] = '终止失败！';
        } else {
            action_log('project_cancel','project',UID,UID);
            $dd['success'] = 1;
            $dd['message'] = '终止成功！';
        }
        $this ->ajaxReturn($dd);
      }elseif($status == '-2'){
        $data['status'] = '1';
        $res = M("cst_cti_project")->where($map)->save($data);
        // var_dump(M()->getLastSql());
        if(!$res){
            $this->error('恢复失败！');
        } else {
            action_log('project_recover','project',UID,UID);
            $this->success('恢复成功！',U('index'));
        }
      }elseif($status == '-3'){
        $data['status'] = '1';
        $res = M("cst_cti_project")->where($map)->save($data);
        // var_dump(M()->getLastSql());
        if(!$res){
            $this->error('确认失败！');
        } else {
            action_log('project_sure','project',UID,UID);
            $this->success('确认成功！',U('index'));
        }
      }
    }

    /*项目更新*/
    public function updata(){
        // $data['product_code'] = 0001;
        $data['project_name'] = I('project_name');
        $data['project_type'] = I('project_type');
        $data['customer'] = I('customer');
        $data['province'] = I('province').'-'.I('city');
        $data['purchase_intention'] = implode(",", I('purchase_intention'));
        $data['charge_person'] = I('charge_person');
        $data['budget'] = I('budget');
        $data['begin_time'] = date("Y-m-d H:i:s",I('begin_time'));
        $data['discrebe'] = I('discrebe');
        $data['update_one'] = I('update_one');
        $data['update_time'] = I('update_time');

        $map['id'] = I('id');
        $res = M("cst_cti_project")->where($map)->add($data);


    }


    /*项目跟踪记录列表*/
    public function tail_list(){
        $mmmp['uid'] = UID;
        $group_id = M('auth_group_access') ->field('group_id') ->where($mmmp) ->find();
        // var_dump($group_id);
        $this ->assign('group_id',$group_id);
        if(IS_POST){
          $map['id'] = I('id');
          $charge_person = M('cst_cti_project') ->field('charge_person') ->where('id='.I('id')) ->find();
          $ccc = I('charge_person');
          $charge_person1 = explode(',', $charge_person['charge_person']);
          foreach ($charge_person1 as $key22 => $value22) {
            if(in_array($value22,$ccc)){
              foreach ($ccc as $key33 => $value33) {
                if($value22 == $value33){
                  unset($charge_person1[$key22]);
                  unset($ccc[$key33]);
                }
              }
            }
          }
          // var_dump($charge_person);
          // var_dump($ccc);
          if(count($charge_person1)==0 && count($ccc)==0){
            // echo "无变动";
          }else{
            // echo "有变动";
            $data['charge_person'] = implode(',',I('charge_person'));
            $data['old_charge_person'] = $charge_person['charge_person'];
          }
          // var_dump($data);
          // die();
          $data['purchase_intention'] = implode(',',I('purchase_intention'));
          $data['budget'] = I('budget');
          $data['linkman'] = I('linkman');
          $data['position'] = I('position');
          $data['linkphone'] = I('linkphone');

          if(I('begin_time')){
            $data['begin_time'] = I('begin_time');
          }
          if(I('begin_time')){
            $data['open_time'] = I('open_time');
          }
          $data['remark'] = I('remark');
          $data['update_one'] = UID;
          $data['update_time'] = date("Y-m-d H:i:s",time());
          $res = M('cst_cti_project as pr')  ->where($map) ->save($data);
          // var_dump(M()->getLastSql());
          // die();
          if($res){
            action_log('project_up','project',UID,UID);
            $this->success('更新成功！',U('tail_list',array('id'=>I('id'))));
          }else{
            $this->error('更新失败！');
          }
        }else{
          /*获取商务负责人*/
          $map111['ac.group_id'] = array('in','4,7');
          $charge_persons = M('member as me')
                  ->field('me.uid,me.nickname')
                  ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                  ->where($map111)
                  ->select();
          $this->assign('charge_persons',$charge_persons);

          /*只显示销售自己跟进的记录*/

          $map1['pr.id'] = I('id');
          $project = M('cst_cti_project as pr') 
                          ->field('pr.Ter_reason,pr.old_charge_person,pr.id,pr.linkphone,pr.linkman,pr.position,pr.open_time,pr.project_code,pr.project_name,pr.project_type,pr.status,pr.province,pr.purchase_intention,pr.budget,pr.begin_time,pr.end_time,cu.customer,pr.remark,pr.charge_person')
                          ->join('left join  oa_cst_customer as cu on pr.customer = cu.id')
                          // ->join('left join oa_member as me on me.uid = pr.charge_person')
                          ->where($map1)
                          ->find();
          $this->assign('project',$project);
          // var_dump(M()->getLastSql());
          // var_dump($project);
          
          $umap['uid'] = UID;
          $groub =  M('auth_group_access')->field('group_id')->where($umap)->select();
          foreach ($groub as $key3 => $value3) {
            $uuu[] = $value3["group_id"];
          }
          if(in_array(4, $uuu)){
            $map['ta.tail_person'] = UID;
          }elseif (in_array(7, $uuu)) {
          }
          $map['ta.project_id'] = I('id');
          $tails = M("cst_cti_project_tail as ta")
                      ->field('ta.*,me.nickname')
                      ->join('left join oa_member as me on me.uid = ta.tail_person')
                      ->where($map)
                      ->order('ta.follow_up_time desc')
                      ->select();
          $this->assign('tails',$tails);
          
          
          /*获取产品列表*/
          $products = M('cst_product as me')
                  ->field('id,product_name')
                  ->order('product_name asc')
                  ->select();
          $this->assign('products',$products);
          // var_dump($products);
          $this->display();
        }
        
    }


    /*项目跟踪记录添加*/
    public function tail_add(){
          if(I('id')){
            /*拉去产品列表*/
            $mm['status'] = '1';
            $products = M('cst_product') 
                                ->field('id,product_name')
                                // ->join('left join oa_member as me on me.uid = pr.charge_person')
                                ->where($mm) 
                                ->order('product_name asc')
                                ->select();
            $this->assign('products',$products);

            // var_dump(I('id'));
            $map['ta.id'] = I('id');
            $res = M("cst_cti_project_tail as ta")
                          ->field('ta.*,pr.project_name,pr.charge_person')
                          ->join('left join oa_cst_cti_project as pr on ta.project_id = pr.id')
                          ->where($map)
                          ->find();
            // var_dump($res);
            // var_dump(M()->getLastSql());
            $this ->assign('project',$res);
            $this ->display();
          }else{
            if(IS_POST){

              if(I('tid')){
                $map1['id'] = I('tid');
                $data['project_id'] = I('project_id');
                $data['intent_product'] = implode(",", I('intent_product'));
                $data['budget'] = I('budget');
                // $data['charge_person'] = I('charge_person');
                // $data['tail_person'] = UID;
                $data['discribe'] = I('discribe');
                $data['fee'] = I('fee');
                $data['follow_up_time'] = date("Y-m-d H:i:s",time());
                $data['tail_person'] = UID;
                $data['progress'] = I('progress');

                $data['start'] = I('start');
                $data['end'] = I('end');
                $data['visit_target'] = I('visit_target');
                $data['visitor'] = I('visitor');
                $data['position'] = I('position');
                $data['contact_way'] = I('contact_way');
                $data['visit_way'] = I('visit_way');
                $data['communication_content'] = I('communication_content');
                if(I('Is_reach')){
                  $data['Is_reach'] = I('Is_reach');
                }

                $res = M("cst_cti_project_tail")->where($map1)->save($data);

                $map['id'] = I('project_id');
                $data1['update_one'] = UID;
                $data1['update_time'] = date("Y-m-d H:i:s",time());
                $res1 = M('cst_cti_project')  ->where($map) ->save($data1);
                if(!$res){
                    $this->error('更新失败！');
                } else {
                    action_log('follow_up','project_tail',UID,UID);
                    $this->success('更新成功！',U('tail_add',array('id'=>I('tid'))));
                }
                
              }else{
                $data['project_id'] = I('project_id');
                $data['intent_product'] = implode(",", I('intent_product'));
                $data['budget'] = I('budget');
                // $data['charge_person'] = I('charge_person');
                // $data['tail_person'] = UID;
                $data['discribe'] = I('discribe');
                $data['fee'] = I('fee');
                $data['follow_up_time'] = date("Y-m-d H:i:s",time());
                $data['tail_person'] = UID;
                $data['progress'] = I('progress');

                $data['start'] = I('start');
                $data['end'] = I('end');
                $data['visit_target'] = I('visit_target');
                $data['visitor'] = I('visitor');
                $data['position'] = I('position');
                $data['contact_way'] = I('contact_way');
                $data['visit_way'] = I('visit_way');
                $data['communication_content'] = I('communication_content');
                if(I('Is_reach')){
                  $data['Is_reach'] = I('Is_reach');
                }

                $res = M("cst_cti_project_tail")->add($data);

                $map['id'] = I('project_id');
                $data1['update_one'] = UID;
                $data1['update_time'] = date("Y-m-d H:i:s",time());
                $res1 = M('cst_cti_project')  ->where($map) ->save($data1);
                if(!$res){
                    $this->error('添加失败！');
                } else {
                    action_log('follow_add','project_tail',UID,UID);
                    $this->success('添加成功！',U('tail_list',array('id'=>I('project_id'))));
                }
              }
            }else {
              $map1['pr.id'] = I('project_id');
              $project = M('cst_cti_project as pr') 
                                  ->field('pr.id as project_id,pr.project_name,pr.charge_person,pr.budget')
                                  // ->join('left join oa_member as me on me.uid = pr.charge_person')
                                  ->where($map1) 
                                  ->find();
              $this->assign('project',$project);

              /*拉去产品列表*/
              $mm['status'] = '1';
              $products = M('cst_product') 
                                  ->field('id,product_name')
                                  // ->join('left join oa_member as me on me.uid = pr.charge_person')
                                  ->where($mm) 
                                  ->order('product_name asc')
                                  ->select();
              $this->assign('products',$products);

              $this->display();
            }
          }
    }

    /*项目跟踪记录删除*/
    public function tail_delete(){
        // var_dump(I('ids'););
        // die();
        /*获取删除合同数组*/
        $data1 = I('ids')['id'];
        $data2 = I('ids')['project_id'];
        // var_dump($data1);
        // var_dump($data2);
        // die();
        $idArray = array_unique($data1);
        // $uidArray = array(4);
        $map['id'] = array('in', $idArray);

        $res = M("cst_cti_project_tail")->where($map)->delete();
        // var_dump(M()->getLastSql());
        if(!$res){
            $this->error('删除失败！');
        } else {
            action_log('follow_del','project_tail',UID,UID);
            $this->success('删除成功！',U('tail_list',array('id'=>$data2)));
        }
    }

}

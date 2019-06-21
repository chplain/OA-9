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

/*客户管理*/
class CustomController extends AdminController {

    /*客户列表首页*/
    public function index(){

      $umap['uid'] = UID;
      /*拉去昵称*/
      $nickname =  M('member')->field('nickname')->where($umap)->find();
      $groub =  M('auth_group_access')->field('group_id')->where($umap)->select();
      foreach ($groub as $key3 => $value3) {
        $uuu[] = $value3["group_id"];
      }
      if(in_array(4, $uuu)){
        // $map['cp.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
        $this ->assign('gid',4);
      }elseif (in_array(7, $uuu)) {
        if(isset($_GET['charge_person']) ){
          // $map['cp.charge_person']   =   array('like', '%'.$_GET['charge_person'].'%');
        }
        $this ->assign('gid',7);
      }

      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;

      
      // $map['cu.customer'] = array('like', '%' . (string)$key1 . '%');
      // $map['cu.linkman'] = array('like', '%' . (string)$key2 . '%');

      if(isset($_GET['customer']) ){
          $map['cu.customer']    =   array('like', '%'.$_GET['customer'].'%');
      }
      if(isset($_GET['linkman']) ){
          $map['cu.linkman']   =   array('like', '%'.$_GET['linkman'].'%');
      }
      $customer = M("cst_customer as cu")
                    ->field('cu.*,m.nickname as cperson,m1.nickname as upperson')
                    ->join('left join oa_member as m on m.uid=cu.create_person')
                    ->join('left join oa_member as m1 on m1.uid=cu.update_person')
                    ->where($map)
                    ->order('create_time asc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      // var_dump($customer);
      // var_dump(M()->getLastSql());
      // $this->display();

      $count=M("cst_customer as cu")
              ->where($map)
              ->count();

      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          // var_dump($page);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }

      $this->assign('customer',$customer);
      $this->display();
    }

    /*新增客户*/
    public function add(){
      if(IS_POST){
        if(I('id')){
          $map['id'] = I('id');
          $data['customer_code'] = I('customer_code');
          $data['customer'] = I('customer');
          $data['linkman'] = I('linkman');
          $data['link_phone'] = I('link_phone');
          $data['wechat'] = I('wechat');
          $data['link_address'] = I('link_address');
          $data['group_visitor'] = I('group_visitor');
          $data['update_person'] = UID;
          $data['update_time'] = date("Y-m-d H:i:s",time());

          $map1['customer'] = I('customer');
          $map1['id'] = array('neq',I('id'));
          $res1 = M("cst_customer")->where($map1)->find();
          // var_dump($res1);
          // die();
          if($res1){
            $this->error('更新失败,该客户名称已被使用！');
          }else{
            $res = M("cst_customer")->where($map)->save($data);
            if(!$res){
                $this->error('更新失败！');
            } else {
                $this->success('更新成功！',U('index'));
            }
          }
        }else{
          /*获取最大uid*/
          $maxid = M("cst_customer")
                        ->field('id')
                        ->order('id desc')
                        ->find();
          
          $data['customer_code'] = "CU".sprintf("%06d", $maxid['id']+1);//生成4位数，不足前面补0   
          $data['customer'] = I('customer');
          $map['customer'] = I('customer');
          $res1 = M("cst_customer")->where($map)->find();
          if($res1){
            $this->error('添加失败,该客户名称已存在！');
          }else{
            $data['linkman'] = I('linkman');
            $data['link_phone'] = I('link_phone');
            $data['wechat'] = I('wechat');
            $data['link_address'] = I('link_address');
            $data['group_visitor'] = I('group_visitor');
            $data['create_person'] = UID;
            $data['create_time'] = date("Y-m-d H:i:s",time());
            $res = M("cst_customer")->add($data);
            if(!$res){
                $this->error('添加失败！');
            } else {
                // echo 12321;
                action_log('Custom_add','customer',UID,UID);
                // die();
                $this->success('添加成功！',U('index'));
            }
          }
        }
          
      }else{
        $map['id'] = I('id');
        $customer = M("cst_customer")->where($map)->find();
        $this->assign('customer',$customer);
        // var_dump($customer);
        $this->display();
      }

    }

    /*客户失效*/
    public function delete(){
      /*获取删除合同数组*/
      $idArray = array_unique((array)I('ids'));
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      $status = I('request.status');
      // $test = I('request.test');
      // var_dump($test);
      // die();
      if($status == '-2'){
        $map1['customer'] = array('in', $idArray);
        $map1['status'] = array('in', '0,1,2,3');
        $is = M('cst_cti_project')->where($map1)->select();
        if($is){
          $this->error('操作失败,该客户存在关联项目,只允许作废客户!');
        }
        // die();
        $res = M("cst_customer")->where($map)->delete();
        // var_dump(M()->getLastSql());
        if(!$res){
            $this->error('删除失败！');
        } else {
            action_log('Custom_del','customer',UID,UID);
            $this->success('删除成功！',U('index'));
        }
      }elseif ($status == '-1') {
        $map1['customer'] = array('in', $idArray);
        $map1['status'] = array('in', '0,1,2');
        $is = M('cst_cti_project')->where($map1)->select();
        if($is){
          $this->error('操作失败,该客户存在关联项目，请先终止关联项目!');
        }
        $data['status'] = "0";
        $res = M("cst_customer")->where($map)->save($data);
        // var_dump(M()->getLastSql());
        if(!$res){
            $this->error('作废失败！');
        } else {
            action_log('Custom_cancel','customer',UID,UID);
            $this->success('作废成功！',U('index'));
        }
      }elseif ($status == '-3') {
        // $map1['customer'] = array('in', $idArray);
        // $map1['status'] = array('in', '0,1,2');
        // $is = M('cst_cti_project')->where($map1)->select();
        // if($is){
        //   $this->error('操作失败,该客户存在关联项目，请先终止关联项目!');
        // }
        $data['status'] = "1";
        $res = M("cst_customer")->where($map)->save($data);
        // var_dump(M()->getLastSql());
        if(!$res){
            $this->error('恢复失败！');
        } else {
            action_log('Custom_recover','customer',UID,UID);
            $this->success('恢复成功！',U('index'));
        }
      }
     
     
    }

    


}

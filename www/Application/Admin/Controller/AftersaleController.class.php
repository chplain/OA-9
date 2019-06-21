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
class AftersaleController extends AdminController {

    // static protected $allow = array( 'verify');

    
    public function index(){
      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;

      if(isset($_GET['customer']) ){
          $map['oc.customer']   =   array('like', '%'.$_GET['customer'].'%');
      }
      if(isset($_GET['project_name']) ){
          $map['pr.project_name']   =   array('like', '%'.$_GET['project_name'].'%');
      }
      if(isset($_GET['province']) ){
          $map['pr.province']   =   array('like', '%'.$_GET['province'].'%');
      }
     
      if(isset($_GET['status'])){
          $map['se.status']  =   $_GET['status'];
      }else{
           $map['se.status']  =   array('in', '0,1,2');
      }

      // var_dump($map);
      $service = M("cst_pj_service as se")
                    ->field('se.id,se.record_code,pr.project_name,se.status,ct.contract_productlist,se.service_type,me.nickname as create_person,se.create_time')
                    ->join('left join oa_cst_cti_project as pr on pr.id = se.project_id')
                    ->join('left join oa_cst_contract as ct on ct.project_id = se.project_id')
                    ->join('left join oa_member as me on me.uid = se.create_person')
                    ->join('left join oa_cst_customer as oc on oc.id = pr.customer')
                    ->where($map)
                    ->order('se.create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->select();
      // var_dump($service);               
      // var_dump(M()->getLastSql());   
      
      $count=M("cst_pj_service as se")
                    ->field('se.id,se.record_code,pr.project_name,se.status,ct.contract_productlist,se.service_type,me.nickname as create_person,se.create_time')
                    ->join('left join oa_cst_cti_project as pr on pr.id = se.project_id')
                    ->join('left join oa_cst_contract as ct on ct.project_id = se.project_id')
                    ->join('left join oa_member as me on me.uid = se.create_person')
                    ->join('left join oa_cst_customer as oc on oc.id = pr.customer')
                    ->where($map)
                    ->order('se.create_time desc')
                    ->page($pageindex['p'], $pagesize)
                    ->count();

      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          // var_dump($page);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }                                   
      $this->assign('service',$service);
      $this ->display(); 
    }

    public function add(){

        if(IS_POST){
          /*判断record_code是否存在,不存在新增售后记录和售后问题*/
          if(I('record_code')){
            $map['record_code'] = I('record_code');

            /*问题记录*/
            $ques = I('data1');
            foreach ($ques as $key => $value) {
              $qmap['que_code'] = $value['que_code'];
              $qres = M("cst_pj_service_que")->where($qmap)->find();
              if(!$qres){
                $qmaxid = M("cst_pj_service_que")
                        ->field('id')
                        ->order('id desc')
                        ->find();
                $que['que_code'] = "QE".date("Ymd",time()).sprintf("%02d", $qmaxid['id']+1);
                $que['record_code'] = I('record_code');
                $que['question_name'] = $value['question_name'];
                $que['discribe'] = $value['discribe'];
                $que['create_person'] = UID;
                $que['create_time'] = date("Y-m-d h:m:s",time());
                $rr = M("cst_pj_service_que")->add($que);
              }
            }

            /*问题处理*/
            $quesdel = I('data2');
            
            foreach ($quesdel as $key2 => $value2) {
              $map11['que_code'] = $value['que_code'];
              $res32 = M("cst_pj_service_deal")->where($map1)->find();
              if(!$res32){
                
                $deal['que_code'] = $value2['que_code'];
                $deal['excute'] = $value2['excute'];
                $deal['result'] = $value2['result'];
                $deal['discribe'] = $value2['discribe'];
                $deal['IsFee'] = $value2['IsFee'];
                $deal['fee'] = $value2['fee'];
                $deal['ex_time'] = $value2['ex_time'];
                $rrd = M("cst_pj_service_deal")->add($deal);
              }
            }

            /*问题回访*/
            $callbacks = I('data3');
            
            foreach ($callbacks as $key4 => $value4) {
              $map114['que_code'] = $value4['que_code'];
              $res33 = M("cst_pj_service_callback")->where($map114)->find();
              if(!$res33){
                
                $callback['que_code'] = $value4['que_code'];
                $callback['customer'] = $value4['customer'];
                $callback['satisfaction_degree'] = $value4['satisfaction_degree'];
                $callback['discribe'] = $value4['discribe'];
                $callback['Visit_man'] = $value4['Visit_man'];
                $callback['Visit_time'] = $value4['Visit_time'];
                $rrd4 = M("cst_pj_service_callback")->add($callback);
              }
            }

            if($rr||$rrd||$rrd4){
                $this->success('更新成功！',U('index'));
            }else {
                $this->error('更新失败！');
            }

          }else{
            $maxid = M("cst_pj_service")
                        ->field('id')
                        ->order('id desc')
                        ->find();
            $data1['record_code'] = "JL".date("Ymd",time()).sprintf("%02d", $maxid['id']+1);//生成2位数，不足前面补0 
            $data1['project_id'] = I('project_id');
            $data1['service_type'] = I('service_type');
            // $data['status'] = '0';
            $data1['create_person'] = UID;
            $data1['create_time'] = date("Y-m-d h:m:s",time());
            $res = M("cst_pj_service")->add($data1);
            if($res){
              $ques = I('data1');
              // var_dump($ques);
              // die();
              foreach ($ques as $key => $value) {
                $data2['record_code'] = "JL".date("Ymd",time()).sprintf("%02d", $maxid['id']+1);
                $maxid1 = M("cst_pj_service_que")
                        ->field('id')
                        ->order('id desc')
                        ->find();
                $data2['que_code'] = "QE".date("Ymd",time()).sprintf("%02d", $maxid1['id']+1);
                $data2['question_name'] = $value['question_name'];
                $data2['introducer'] = $value['introducer'];
                $data2['discribe'] = $value['discribe'];
                $data2['create_person'] = UID;
                $data2['create_time'] = date("Y-m-d h:m:s",time());
                // var_dump($data2);
                $res2 = M("cst_pj_service_que")->add($data2);
              }
              if($res){
                $this->success('添加成功！',U('index'));
              }else {
                  $this->error('添加失败！');
              }
            }

          }
        }else{
          /*拉取完成项目列表*/
          $map['wi.status'] = '2';
          $projects = M('cst_fd_withdraw as wi')
                  ->distinct('true')
                  ->field('wi.project_id,pr.project_name')
                  ->join('left join oa_cst_cti_project as pr on pr.id = wi.project_id')
                  ->select();
          $this ->assign('projects',$projects);

          if(I('id')){
            /*拉去已有信息*/
            $smap['se.id'] = I('id');
            $sevice = M('cst_pj_service as se')
                            ->field('se.id,se.project_id,se.record_code,pr.project_name,ct.contract_productlist,se.service_type')
                            ->join('left join oa_cst_cti_project as pr on pr.id = se.project_id')
                            ->join('left join oa_cst_contract as ct on ct.project_id = se.project_id')
                            ->where($smap)
                            ->find();
            if($sevice['service_type']='0'){
                $sevice['service_type']='使用问题';
            }elseif ($sevice['service_type']='1') {
                $sevice['service_type']='系统需求';
            }else{
                $sevice['service_type']='系统bug';
            }
            // var_dump($sevice);
            $this ->assign('sevice',$sevice);

            /*拉取售后问题*/
            $qmap['qe.record_code'] = $sevice['record_code'];
            $ques = M('cst_pj_service_que as qe')
                            ->field('qe.que_code,qe.question_name,qe.introducer,qe.discribe,me.nickname as create_person,qe.create_time')
                            ->join('left join oa_member as me on me.uid = qe.create_person')
                            ->where($qmap)
                            ->select();
            // var_dump($ques);
            $this ->assign('ques',$ques);

            /*拉去售后处理结果*/
            foreach ($ques as $qkey => $qvalue) {
              $ddata[] = $qvalue['que_code'];
            }
            $dmap['que_code'] = array('in',$ddata);
            $deals = M('cst_pj_service_deal as de')
                            // ->field('qe.que_code,qe.question_name,qe.introducer,qe.discribe,me.nickname as create_person,qe.create_time')
                            // ->join('left join oa_member as me on me.uid = qe.create_person')
                            ->where($dmap)
                            ->select();

            if($deals){
              foreach ($deals as $keyd => $valued) {
                if($valued['IsFee']='0'){
                    $deals[$keyd]['IsFee']='否';
                }elseif ($valued['IsFee']='1') {
                    $deals[$keyd]['IsFee']='是';
                }
              }
            }
            
            // var_dump(M()->getLastSql());
            // var_dump($deals);
            $this ->assign('deals',$deals);

            /*拉取回访记录*/
            $camap['ca.que_code'] = array('in',$ddata);
            $callbacks = M('cst_pj_service_callback as ca')
                            ->field('ca.id,ca.que_code,ca.satisfaction_degree,ca.discribe,ca.Visit_man,ca.Visit_time,cu.customer')
                            ->join('left join oa_cst_customer as cu on cu.id = ca.customer')
                            ->where($camap)
                            ->select();
            // var_dump($callbacks);                
            if($callbacks){
              foreach ($callbacks as $keyc => $valuec) {
                if($valuec['satisfaction_degree']=='0'){
                    $callbacks[$keyc]['satisfaction_degree']='不满意';
                }elseif ($valuec['satisfaction_degree']=='1') {
                    $callbacks[$keyc]['satisfaction_degree']='一般';
                }elseif ($valuec['satisfaction_degree']=='2') {
                    $callbacks[$keyc]['satisfaction_degree']='满意';
                }elseif ($valuec['satisfaction_degree']=='3') {
                    $callbacks[$keyc]['satisfaction_degree']='非常满意';
                }
              }
            }

          }
          $this ->assign('callbacks',$callbacks);
          // var_dump($callbacks);
          $this->display();
        }

    }

    public function delete(){
        // echo "daily";
   //      if(UID){
      // $this->display();
   //      } else {
   //          $this->redirect('Public/login');
   //      }
        $this->display();
    }

}

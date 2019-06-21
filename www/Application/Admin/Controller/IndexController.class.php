<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class IndexController extends AdminController {

    static protected $allow = array( 'verify');

    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        if(UID){

          /*拉去定制开发中的项目*/ 
          $cusmap['status'] = '3';
          $cuspro = M('cst_dev_customization')
                          ->field('DISTINCT project_name')
                          ->where($cusmap)
                          ->order('project_name desc')
                          ->select();
          // var_dump(M()->getLastSql());
          $this ->assign('cuspro',$cuspro);


          /*首先拉取进行中的项目*/
          $map1['status'] = '1';
          $projects1 = M('cst_cti_project')
                        ->field('id,project_name')
                        ->where($map1)
                        ->order('project_name desc')
                        ->select();
          $this->assign('projects1',$projects1);  

          /*登录加载首页后判断项目中新增的项目或者进行中的项目
          一周之内是否有跟踪记录录入
          如果没有则邮件通知相应人员
          */
          /*首先拉去新增和进行中的项目*/
          $status = array('0','1');
          $map['status'] = array('in',$status);
          $projects = M('cst_cti_project as pr')
                        ->field('pr.id,pr.project_name,pr.last_time,pr.project_code,pr.charge_person,pr.send_status')
                        ->where($map)
                        ->select();
          // var_dump($projects);
          $tt = time();
          foreach ($projects as $key => $value) {

              $dd['last_time'] = $tt;
              $mm['id'] = $value['id'];

              if(date('w')==3){
                if( date("Y-m-d",$value['last_time']) !== date("Y-m-d") && ($value['send_status'] == 0)){
                  $dd['send_status'] = 1;
                  // $dd['last_time'] = 1160924414;
                  M('cst_cti_project')->where($mm)->save($dd);

                  $map2['pt.project_id'] = $value['id'];
                  $res2 = M('cst_cti_project_tail as pt') ->field('pt.follow_up_time') ->where($map2) ->order('pt.follow_up_time desc')->find();
                  if(!$res2){
                    $data['nofl'][$key] =  $value;
                  }else{
                    $follow_up_time = strtotime($res2['follow_up_time']);
                    if($follow_up_time+60*60*24*7<time()){
                      $data['nolog'][$key] =  $value;
                    }
                  }

                }else{
                  // echo 44;
                }
              }else{
                // echo 1;
                $dd['send_status'] = 0;
                M('cst_cti_project')->where($mm)->save($dd);
              }

          }
          // var_dump($data);
          if(!$data){

          }else{
            $num1 = count($data['nofl']);
            $num2 = count($data['nolog']);
            $str = '';
            $str1 = '';
            $j = 1;
            foreach ($data['nofl'] as $key1=> $value1) {
              $content.= $value1;
              $j++;
            }
            $i = 1;
            foreach ($data['nolog'] as $key2=> $value2) {
              $content1.= $i.$value2.'<br/>';
              $i++;
            }

            if(date('w')==3){
              /*生成excel文件*/
              $now = '截止'.date('Y-m-d h时i分',time()).'项目跟进提醒';
              $xlsName  = $now;
              $xlsCell  = array(
                  array('project_code','项目编号'),
                  array('project_name','项目名称'),
                  array('charge_person','项目负责人')
              );
              foreach ($data['nofl'] as $keyd => $valued) {
                $dddd['a'][] = $valued;
              }
              foreach ($data['nolog'] as $keyd1 => $valued1) {
                $dddd['b'][] = $valued1;
              }
              // var_dump($dddd);
              $this->exportExcel($xlsName,$xlsCell,$dddd);
              // $href = "http://oa/www/uploads/excel/".$xlsName.".xls";
              $href = "http://192.168.1.221/oa/www/uploads/excel/".$xlsName.".xls";
              $href1 = "<a href='".$href."'>请点击查看excel详情</a>";
              $cc = '未有跟进记录的项目('.$num1.'个):'. '<br/><br/>超过七天没有跟进的项目('.$num2.'个):<br/><br/>详情请查看:' .$href1;
              $ml = "chenlei@gdwstech.com";
              SendMail2($ml,'OA系统项目跟踪提醒',$cc);
            }else{
                echo '当然也不是周末了';
                // echo date('w');
            }

          }
          // die();
            $this->display();
        } else {
            $this->redirect('Public/login');
        }
    }

    /*导出数据到excel中*/
    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle.date('_Ymd_His');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum1 = count($expTableData['a']);
        $dataNum2 = count($expTableData['b']);
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        /*未跟进的项目*/
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','从未跟进的项目');//第一行标题

        // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFFFFFFF');

        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum1;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData['a'][$i][$expCellName[$j][0]]);
                // var_dump($expTableData[$i]);
            }
        }
        // echo 123;
        // die();
        /*七天内未跟进的项目*/
        $i = $i+3;
        $stt1 = 'A'.($i+1);
        $stt2 = $cellName[$cellNum-1].($i+1);
        // var_dump($stt1);
        // var_dump($stt2);
        // die();
        $objPHPExcel->getActiveSheet(0)->mergeCells($stt1.':'.$stt2);//合并单元格
        // echo $expTitle['b'];
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($stt1, '七天内未跟进的项目');//第一行标题

        // echo 123;
        // die();
        // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFFFFFFF');
        // var_dump();
        for($l=0;$l<$cellNum;$l++){
          // var_dump($cellName[$l].'8');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$l].($i+2), $expCellName[$l][1]);
        }
        // echo 321;
        // die();
        // Miscellaneous glyphs, UTF-8
        for($k=0;$k<$dataNum2;$k++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($k+$i+2), $expTableData['b'][$k][$expCellName[$j][0]]);
                // var_dump($expTableData[$i]);
            }
        }





        // header('pragma:public');
        // header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        // header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        // $objWriter->save('php://output');
        $objWriter->save('./Uploads/Excel/'.$xlsTitle.'.xls');
        return 'success';
    }

    /*首页加载读取账号权限*/
    public function getAuth(){

      $map['uid'] = UID;
      $auth = M("auth_group_access") ->field('group_id') ->where($map)->find();
      // $this->ajaxReturn($auth);
      if(UID == '1' || $auth['group_id'] =='13' || $auth['group_id'] =='15'||$auth['group_id'] =='19'){
        $data['status'] = 0;

        /*销售看板*/

        /*销售看板，展示近30天的项目跟进情况，需要展示新增、跟进情况，含汇总数量和折线图表示*/
          $prnum = 0;
          $gznum = 0;
          for($ii=30;$ii>0;$ii--){
            /*拉取30天内的新增项目数量*/
            $firstday = date('Y-m-d', strtotime("-$ii day"))." 00:00:00";
            $jj = $purchase_intention-1;
            $lastday = date('Y-m-d', strtotime("-$jj day"))." 00:00:00";
            $prmap['create_time'] = array('between',array($firstday,$lastday),'AND');
            $prcount = M('cst_cti_project') ->where($prmap)->count();
            $data['sale']['pr']['daynum'][] = $prcount;
            $data['sale']['pr']['day'][] = date('d', strtotime("-$ii day"));
            $prnum = $prnum + $prcount;
            // echo $zxcount;
           
            /*拉取30天内项目跟进的数量*/
            $firstday1 = date('Y-m-d', strtotime("-$ii day"));
            $jj = $ii-1;
            $lastday1 = date('Y-m-d', strtotime("-$jj day"));
            $gzmap['follow_up_time'] = array('between',array($firstday1,$lastday1),'AND');
            $gzcount = M('cst_cti_project_tail') ->where($gzmap)->count();
            $data['sale']['gz1']['daynum'][] = $gzcount;
            $gznum = $gznum + $gzcount;
          }
          $data['sale']['pr']['num'] = $prnum;
          $data['sale']['gz1']['num'] = $gznum;
          // var_dump($zx);



        /*获取跟踪项目*/
          $start_time = date("Y",time());
          $start = $start_time.'-01-01 00:00:00';
          $end = date("Y-m-d",time());
          
          $end = $end.' 23:59:59';
          $map['create_time'] = array('between',array($start,$end),'AND');

          $dd[] = '0';
          $dd[] = '1';
          $map['status'] = array('in',$dd);
          $data['sale']['scount'] = M('cst_cti_project')->where($map)->count();
          // $sql = M()->getLastSql();
          // $data['sale']['sql'] = $sql;
          /*获取当年销售额*/
          $start_time = date("Y",time());
          $start = $start_time.'-01-01 00:00:00';
          $end = date("Y-m-d",time()+60*60*24);
          $end = $end.' 23:59:59';
          $map1['start_time'] = array('between',array($start,$end),'AND');
          $map1['status'] = '3';
          $price = M('cst_contract') ->where($map1)->sum('contract_fee');
          // $data['sale']['price'] = M()->getLastSql;
          $data['sale']['price'] = number_format ($price , 2 , '.' , ',' );
          if(!$price){
            $data['sale']['price'] = '0';
          }

          

          /*获取当年回款金额*/
          $map2['wi.create_time'] = array('between',array($start,$end),'AND');
          $map2['wi.status'] = '2';
          $rprice1 = M('cst_fd_withdraw as wi') 
                      ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = wi.project_id and wi.phases = ph.phases')
                      ->where($map2)
                      ->sum('PhasesFee');

          $kkk['wi.create_time'] = array('between',array($start,$end),'AND');
          $kkk['wi.status'] = '2';
          $kkk['wi.plan_code'] = array('EXP','IS NULL');
          $rprice2 = M('cst_fd_withdraw as wi') 
                      ->join('left join oa_cst_contract as con on con.contract_code = wi.contract_code')
                      ->where($kkk)
                      ->sum('contract_fee');
          $rprice = $rprice1+$rprice2;

          $data['sale']['rprice'] = number_format ($rprice , 2 , '.' , ',' );
          if(!$rprice){
            $data['sale']['rprice'] = '0';
          }

          $pageindex = $_GET["p"];
          if (empty($pageindex)||$pageindex=="0") {
              $pageindex=1;
          }
          $pagesize = PAGESIZE;

          /*获取跟踪记录*/
          $map3['pr.status'] = array('in',$dd);
          $gz = M('cst_cti_project as pr') 
                      ->field('pr.project_name,pr.province,pr.budget,cu.customer,pr.charge_person,pr.purchase_intention')
                      ->join('left join oa_cst_customer as cu on pr.customer = cu.id')
                      ->join('left join oa_member as me on me.uid = pr.charge_person')
                      ->where($map3)
                      ->page($pageindex, $pagesize)
                      ->select();

          if(!$gz){
            $data['sale']['gz']['content'] = array();
            $data['sale']['gz']['count'] = '0';
          }else{
            foreach ($gz as $key6 => $value6) {
              $gz[$key6]['budget'] = number_format ($value6['budget'] , 2 , '.' , ',' );
            }
            $data['sale']['gz']['content'] = $gz;
            $data['sale']['gz']['count'] = $data['sale']['scount'];
            
          }

          /*获取折线图数据*/
          $num = intval(date("m",time()));
          for($i=1;$i<=$num;$i++){
            $m = sprintf("%02d", $i);
            $m1 = sprintf("%02d", $i+1);
            // $data['ze'][$i] = $i;
            $y = date("Y",time());
            $ss = $y.'-01-01 00:00:00';
            $dd = $y.'-'.$m1.'-01 00:00:00';

            /*累计销售额*/
            $map4['start_time'] = array('between',array($ss,$dd),'AND');
            $map4['status'] = '3';
            $price4 = M('cst_contract') ->where($map4)->sum('contract_fee');
            if(!$price4){
              $data['sale']['ze'][] = '0';
            }else{
              $data['sale']['ze'][] = $price4;
            }

            /*累计回款额*/
            $map5['wi.create_time'] = array('between',array($ss,$dd),'AND');
            $rp1 = M('cst_fd_withdraw as wi') 
                        ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = wi.project_id and wi.phases = ph.phases')
                        ->where($map5)
                        ->sum('PhasesFee');

            $kkk1['wi.create_time'] = array('between',array($ss,$dd),'AND');
            $kkk1['wi.status'] = '2';
            $kkk1['wi.plan_code'] = array('EXP','IS NULL');
            $rp2 = M('cst_fd_withdraw as wi') 
                        ->join('left join oa_cst_contract as con on con.contract_code = wi.contract_code')
                        ->where($kkk1)
                        ->sum('contract_fee');
            $rp[$i] = $rp1+$rp2;

            if(!$rp){
              $data['sale']['ze1'][] = '0';
            }else{
              $data['sale']['ze1'][] = $rp[$i];
            }
            $data['sale']['month'][] = $i."月";   

          }

        /*实施看板*/
          /*正在实施项目*/
          $tmap['pl.create_time'] = array('between',array($start,$end),'AND');
          $tmap['pl.status'] = '1';
          $data['tech']['tcout'] = M('cst_pj_plan as pl')
                        ->where($tmap)
                        ->count();
          $data['tech']['sql'] = M()->getLastSql();          
          $data['tech']['phases']['content'] = M('cst_pj_plan_phases as ph') 
                                    ->field('pr.project_name,ph.phases,ph.products,me.nickname as executor,ph.PhasesFee')
                                    ->join('left join oa_cst_pj_plan as pl on pl.project_id = ph.project_id')
                                    ->join('left join oa_cst_cti_project as pr on ph.project_id = pr.id')
                                    ->join('left join oa_member as me on ph.executor = me.uid')
                                    ->where($tmap)
                                    ->select();
          $data['tech']['phases']['pcount'] = M('cst_pj_plan_phases as ph')
                                    ->join('left join oa_cst_pj_plan as pl on pl.project_id = ph.project_id')
                                    ->join('left join oa_cst_cti_project as pr on ph.project_id = pr.id')
                                    ->join('left join oa_member as me on ph.executor = me.uid')
                                    ->where($tmap)
                                    ->count();

          if(!$data['tech']['phases']['content']){
            $data['tech']['phases']['content'] = array(); 
          }
          if(!$data['tech']['phases']['pcount']){
            $data['tech']['phases']['pcount'] = '0'; 
          }                          

          /*获取实施回款金额*/
          $rmap['rd.create_time'] = array('between',array($start,$end),'AND');
          $rmap['rd.status'] = '2';
          $data['tech']['rmoney'] = M('cst_pj_payreminder_rd as rd') ->where($rmap)->sum('rmoney');
          $data['tech']['rmoney'] = number_format ($data['tech']['rmoney'], 2 , '.' , ',' );
          if(!$data['tech']['rmoney']){
            $data['tech']['rmoney'] = '0';
          }

          /*获取实施催款金额*/
          $rmap['rd.create_time'] = array('between',array($start,$end),'AND');
          $rmap['rd.status'] = array('in','0,1');
          $data['tech']['cmoney'] = M('cst_pj_payreminder_rd as rd') ->where($rmap)->sum('rmoney');
          $data['tech']['cmoney'] = number_format ($data['tech']['cmoney'], 2 , '.' , ',' );

          /*获取正在收款项目*/
          $rmap['rd.create_time'] = array('between',array($start,$end),'AND');
          $rmap['rd.status'] = array('in','0,1');
          $data['tech']['ckproject']['content'] = M('cst_pj_payreminder_rd as rd') 
                                                      ->field('pr.project_name,rd.phases,rd.status,rd.rmoney,rd.prompt_result,rd.rpercent,rd.prompt_id,me.nickname as executor')
                                                      ->join('oa_cst_cti_project as pr on pr.id = rd.project_id')
                                                      ->join('left join oa_member as me on rd.executor = me.uid')
                                                      ->where($rmap)
                                                      ->select();

          $data['tech']['ckproject']['ckcount'] = M('cst_pj_payreminder_rd as rd') 
                                                      ->field('pr.project_name,rd.phases,rd.status,rd.rmoney')
                                                      ->join('oa_cst_cti_project as pr on pr.id = rd.project_id')
                                                      ->where($rmap)
                                                      ->count();    

          if(!$data['tech']['ckproject']['content']){
            $data['tech']['ckproject']['content'] = array(); 
          }
          if(!$data['tech']['ckproject']['ckcount']){
            $data['tech']['ckproject']['ckcount'] = '0'; 
          }                                                   

          /*柱状图项目跟踪费用和人天*/      
          $num = intval(date("m",time()));
          for($j1=1;$j1<=$num;$j1++){
            $jj1 = sprintf("%02d", $j1);
            $jj1 = sprintf("%02d", $j1+1);
            // $data['ze'][$i] = $i;
            $y1 = date("Y",time());
            $sss1 = $y1.'-01-01 00:00:00';
            $dds1 = $y1.'-'.$jj1.'-01 00:00:00';

            /*累计销售额*/
            $map81['rd.rd_time'] = array('between',array($sss1,$dds1),'AND');
            $manday1 = M('cst_pj_excute_rd as rd') 
                            ->field('rd.project_id,pr.project_name,rd.phases,rd.products,rd.project_fee,rd.content,rd.executor,rd.manday')
                            ->join('left join oa_cst_cti_project as pr on rd.project_id = pr.id')
                            // ->join('left join oa_member as me on rd.executor = me.uid')
                            ->where($map81)
                            ->select();
           
            $data['tech']['manday']['content'] = $manday1;
            $data['tech']['manday']['count'] = M('cst_pj_excute_rd as rd') 
                                            ->field('rd.project_id,pr.project_name,rd.phases,rd.products,rd.project_fee,rd.content,rd.executor,rd.manday')
                                            ->join('left join oa_cst_cti_project as pr on rd.project_id = pr.id')
                                            // ->join('left join oa_member as me on rd.executor = me.uid')
                                            ->where($map81)
                                            ->count();

            if(!$data['tech']['manday']['content']){
              $data['tech']['manday']['content'] = array(); 
            }
            if(!$data['tech']['manday']['count']){
              $data['tech']['manday']['count'] = '0'; 
            }      
            
            
          } 

        /*售后看板*/
          /*获取已收款的项目或项目阶段*/
          /*财务确认*/
          $mm['wi.status'] = '2';
          $service = M('cst_fd_withdraw as wi')
                                          ->field('wi.updata_time,pr.project_name,wi.phases,ph.etime,ph.products,ph.PhasesFee,wi.create_time')
                                          ->join('left join oa_cst_cti_project as pr on wi.project_id = pr.id')
                                          ->join('left join oa_cst_pj_plan_phases as ph on wi.project_id = ph.project_id and wi.phases = ph.phases')
                                          ->where($mm) 
                                          ->page($pageindex, $pagesize)
                                          ->select();  
          $ccc = M('cst_fd_withdraw as wi')
                                          // ->field('wi.updata_time,pr.project_name,wi.phases,ph.etime,ph.products,ph.PhasesFee')
                                          ->join('left join oa_cst_cti_project as pr on wi.project_id = pr.id')
                                          ->join('left join oa_cst_pj_plan_phases as ph on wi.project_id = ph.project_id and wi.phases = ph.phases')
                                          ->where($mm) 
                                          ->count();  
          if(!$gz){
            $data['after']['content'] = array(); 
            $data['after']['count'] = '0';
          }else{
            $data['after']['content'] = $service; 
            $data['after']['count'] = $ccc;
            
          }
          
        /*开发看板*/
          /*获取正在开发新产品数目*/
          $ndmap['status'] = '1';
          $newdevnum = M('cst_dev_newproduct')->where($ndmap)->count();  
          $data['dev']['dcount'] = $newdevnum; 
          
          /*获取正在开发定制产品数目*/
          $ndmap['status'] = '2';
          $newdevnum1 = M('cst_dev_customization')->where($ndmap)->count();  
          $data['dev']['ccount'] = $newdevnum1;  

           /*获取定制开发人天*/
          $ndmap['status'] = '2';
          $cusmanday = M('cst_dev_customization')->where($ndmap)->sum('manday');  
          $data['dev']['cusmanday'] = $cusmanday;  
          if(!$cusmanday){
            $data['dev']['cusmanday'] = '0';  
          }

          /*获取已到期开发阶段*/
          $pmap['cu.status'] = '2';
          $pmap['ph.etime'] = array('elt',date("Y-m-d h:m:s",time()));
          $devphases = M('cst_cus_dev_phases as ph') 
                             ->field('ph.etime,ph.products,pr.project_name,ph.phases,ph.PhasesFee,me.nickname as dev_role') 
                             ->join('left join oa_cst_dev_customization as cu on cu.plan_code = ph.plan_code') 
                             ->join('left join oa_cst_cti_project as pr on cu.project_id = pr.id') 
                             ->join('left join oa_member as me on me.uid = cu.dev_role') 
                             ->where($pmap) 
                             ->select();
          // $data['dev']['sql'] = M()->getLastSql();
          $devcount = M('cst_cus_dev_phases as ph') 
                             // ->field('') 
                             ->join('left join oa_cst_dev_customization as cu on cu.plan_code = ph.plan_code') 
                             ->join('left join oa_cst_cti_project as pr on cu.project_id = pr.id') 
                             ->join('left join oa_member as me on me.uid = ph.coder') 
                             ->where($pmap) 
                             // ->select();
                             ->count();
          if(!$devphases){
            $data['dev']['devphases']['content'] = array();
            $data['dev']['devphases']['count'] = '0';
          }else{
            $data['dev']['devphases']['content'] = $devphases;                     
            $data['dev']['devphases']['count'] = $devcount;                     
          }

          /*获取开发交付记录*/
          $num = intval(date("m",time()));
          $jj1 = sprintf("%02d", $num+1);
          $y = date("Y",time());
          $sss1 = $y.'-01-01 00:00:00';
          $dds1 = $y.'-'.$jj1.'-01 00:00:00';
          $dtmap['de.del_date'] = array('between',array($sss1,$dds1),'AND');
          $data['dev']['delivery']['content'] = M('cst_dev_delivery as de')
                                                      ->field('de.delivery_code,de.delivery_type,pr.project_name,de.del_date,de.deliver_product,de.dev_role,de.tech_role,cus.eEnd_time,cus.End_time,cus.Need_time')
                                                      ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
                                                      ->join('left join oa_cst_dev_customization as cus on de.contract_code and de.project_id = cus.project_id')
                                                      ->where($dtmap)
                                                      ->order('del_date desc')
                                                      ->page(1,10)
                                                      ->select(); 
          foreach ($data['dev']['delivery']['content'] as $keyd => $valued) {
              if($valued['delivery_type'] == '1'){
                $data['dev']['delivery']['content'][$keyd]['delivery_type'] = '需求新增';
              }elseif ($valued['delivery_type'] == '2') {
                $data['dev']['delivery']['content'][$keyd]['delivery_type'] = 'bug修复';
              }elseif ($valued['delivery_type'] == '3') {
                $data['dev']['delivery']['content'][$keyd]['delivery_type'] = '首次交付';
              }
          }    

          $data['dev']['delivery']['count'] = M('cst_dev_delivery as de')
                                                      ->field('de.delivery_code,pr.project_name,de.del_date,de.deliver_product,de.dev_role,de.tech_role')
                                                      ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
                                                      ->where($dtmap)
                                                      ->count();  

          if(!$data['dev']['delivery']['content']){
            $data['dev']['delivery']['content'] = array();
            
          }
          if(!$data['dev']['delivery']['count']){
            $data['dev']['delivery']['count'] = '0';
            
          }

          /*交付情况对比*/
          $d1 = date('Y-m-d', strtotime("-30 day"))." 00:00:00";
          $d2 = date('Y-m-d', strtotime("-0 day"))." 00:00:00";
          
          /*交付情况对比*/
          /*获取超时交付项目数目*/
          $s1 = "(de.create_time between '".$d1."' and '".$d2."') and  de.del_date > cus.eEnd_time";
          $exNum = M('cst_dev_delivery as de')
                                // ->distinct('delivery_code')
                                ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
                                ->where($s1)
                                ->count();
          $data['dev']['circle']['exNum'] = $exNum;
          $data['dev']['circle']['sql'] = M()->getLastSql();

          /*获取提前交付项目数目*/
          $s2 = "(de.create_time between '".$d1."' and '".$d2."') and  de.del_date < cus.eEnd_time";
          $aheadNum = M('cst_dev_delivery as de')
                                ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
                                ->where($s2)
                                ->count();
          $data['dev']['circle']['aheadNum'] = $aheadNum;
          $map = '';

          /*获取按时交付项目数目*/
          $s3 = "(de.create_time between '".$d1."' and '".$d2."') and  de.del_date = cus.eEnd_time";
          $Num = M('cst_dev_delivery as de')
                                ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
                                ->where($s3)
                                ->count();
          $data['dev']['circle']['Num'] = $Num;
          // $data['dev']['sql'] = M()->getLastSql();


        $this->ajaxReturn($data);

      }else if($auth['group_id'] =='4' || $auth['group_id'] =='7'){
        $data['status'] = 1;

        /*销售看板*/

          $umap['uid'] = UID;
          /*拉去昵称*/
          $nickname =  M('member')->field('nickname')->where($umap)->find();
          $groub =  M('auth_group_access')->field('group_id')->where($umap)->select();
          foreach ($groub as $key3 => $value3) {
            $uuu[] = $value3["group_id"];
          }
          if(in_array(4, $uuu)){
            $map['charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $map1['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $map2['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $map3['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $kkk['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $prmap['charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $gzmap['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
          }

          /*销售看板，展示近30天的项目跟进情况，需要展示新增、跟进情况，含汇总数量和折线图表示*/
          $prnum = 0;
          $gznum = 0;
          for($ii=30;$ii>0;$ii--){
            /*拉取30天内的新增项目数量*/
            $firstday = date('Y-m-d', strtotime("-$ii day"))." 00:00:00";
            $jj = $purchase_intention-1;
            $lastday = date('Y-m-d', strtotime("-$jj day"))." 00:00:00";
            $prmap['create_time'] = array('between',array($firstday,$lastday),'AND');
            $prcount = M('cst_cti_project') ->where($prmap)->count();
            $data['sale']['pr']['daynum'][] = $prcount;
            $data['sale']['pr']['day'][] = date('d', strtotime("-$ii day"));
            $prnum = $prnum + $prcount;
            // echo $zxcount;
           
            /*拉取30天内项目跟进的数量*/
            $firstday1 = date('Y-m-d', strtotime("-$ii day"));
            $jj = $ii-1;
            $lastday1 = date('Y-m-d', strtotime("-$jj day"));
            $gzmap['ta.follow_up_time'] = array('between',array($firstday1,$lastday1),'AND');
            $gzcount = M('cst_cti_project_tail as ta') ->join('left join oa_cst_cti_project as pr on pr.id = ta.project_id')->where($gzmap)->count();
            $data['sale']['gz1']['daynum'][] = $gzcount;
            $gznum = $gznum + $gzcount;
          }
          $data['sale']['pr']['num'] = $prnum;
          $data['sale']['gz1']['num'] = $gznum;
          // var_dump($zx);

          /*销售看板*/
          /*获取跟踪项目*/
          $umap['uid'] = UID;
          /*拉去昵称*/
          $nickname =  M('member')->field('nickname')->where($umap)->find();
          $groub =  M('auth_group_access')->field('group_id')->where($umap)->select();
          foreach ($groub as $key3 => $value3) {
            $uuu[] = $value3["group_id"];
          }
          if(in_array(4, $uuu)){
            $map['charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $map1['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $map2['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $map3['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
            $kkk['pr.charge_person'] = array('like', '%'.$nickname['nickname'].'%');
          }

          $start_time = date("Y",time());
          $start = $start_time.'-01-01 00:00:00';
          $end = date("Y-m-d",time());
          
          $end = $end.' 23:59:59';
          $map['create_time'] = array('between',array($start,$end),'AND');

          $dd[] = '0';
          $dd[] = '1';
          $map['status'] = array('in',$dd);
          $data['sale']['scount'] = M('cst_cti_project')->where($map)->count();
          // $sql = M()->getLastSql();
          // $data['sale']['sql'] = $sql;
          /*获取当年销售额*/
          $start_time = date("Y",time());
          $start = $start_time.'-01-01 00:00:00';
          $end = date("Y-m-d",time());
          $end = $end.' 23:59:59';
          $map1['con.start_time'] = array('between',array($start,$end),'AND');
          $map1['con.status'] = '3';
          $price = M('cst_contract as con') ->join('left join oa_cst_cti_project as pr on pr.id = con.project_id')->where($map1)->sum('con.contract_fee');
          // $data['sale']['sql'] = $price;
          $data['sale']['price'] = number_format ($price , 2 , '.' , ',' );
          if(!$price){
            $data['sale']['price'] = '0';
          }

          /*获取当年回款金额*/
          $map2['wi.create_time'] = array('between',array($start,$end),'AND');
          $map2['wi.status'] = '2';
          $rprice1 = M('cst_fd_withdraw as wi') 
                      ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = wi.project_id and wi.phases = ph.phases')
                      ->join('left join oa_cst_cti_project as pr on pr.id = wi.project_id')
                      ->where($map2)
                      ->sum('PhasesFee');
          $kkk['wi.create_time'] = array('between',array($start,$end),'AND');
          $kkk['wi.status'] = '2';
          $kkk['wi.plan_code'] = array('EXP','IS NULL');
          $rprice2 = M('cst_fd_withdraw as wi') 
                      ->join('left join oa_cst_contract as con on con.contract_code = wi.contract_code')
                      ->join('left join oa_cst_cti_project as pr on pr.id = wi.project_id')
                      ->where($kkk)
                      ->sum('contract_fee');
          $rprice = $rprice1+$rprice2;
          $data['sale']['rprice'] = number_format ($rprice , 2 , '.' , ',' );
          if(!$rprice){
            $data['sale']['rprice'] = '0';
          }

          $pageindex = $_GET["p"];
          if (empty($pageindex)||$pageindex=="0") {
              $pageindex=1;
          }
          $pagesize = PAGESIZE;

          /*获取跟踪记录*/
          $map3['pr.status'] = array('in',$dd);
          $gz = M('cst_cti_project as pr') 
                      ->field('pr.project_name,pr.province,pr.budget,cu.customer,pr.charge_person,pr.purchase_intention')
                      ->join('left join oa_cst_customer as cu on pr.customer = cu.id')
                      ->join('left join oa_member as me on me.uid = pr.charge_person')
                      ->where($map3)
                      ->page($pageindex, $pagesize)
                      ->select();

          if(!$gz){
            $data['sale']['gz']['content'] = array();
            $data['sale']['gz']['count'] = '0';
          }else{
            foreach ($gz as $key6 => $value6) {
              $gz[$key6]['budget'] = number_format ($value6['budget'] , 2 , '.' , ',' );
            }
            $data['sale']['gz']['content'] = $gz;
            $data['sale']['gz']['count'] = $data['sale']['scount'];
            
          }

          /*获取折线图数据*/
          $num = intval(date("m",time()));
          for($i=1;$i<=$num;$i++){
            $m = sprintf("%02d", $i);
            $m1 = sprintf("%02d", $i+1);
            // $data['ze'][$i] = $i;
            $y = date("Y",time());
            $ss = $y.'-01-01 00:00:00';
            $dd = $y.'-'.$m1.'-01 00:00:00';

            /*累计销售额*/
            $map4['start_time'] = array('between',array($ss,$dd),'AND');
            $map4['status'] = '3';
            $price4 = M('cst_contract') ->where($map4)->sum('contract_fee');
            if(!$price4){
              $data['sale']['ze'][] = '0';
            }else{
              $data['sale']['ze'][] = $price4;
            }

          $map2['wi.create_time'] = array('between',array($start,$end),'AND');
          $map2['wi.status'] = '2';
          $rprice1 = M('cst_fd_withdraw as wi') 
                      ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = wi.project_id and wi.phases = ph.phases')
                      ->where($map2)
                      ->sum('PhasesFee');
          $kkk['wi.create_time'] = array('between',array($start,$end),'AND');
          $kkk['wi.status'] = '2';
          $kkk['wi.plan_code'] = array('EXP','IS NULL');
          $rprice2 = M('cst_fd_withdraw as wi') 
                      ->join('left join oa_cst_contract as con on con.contract_code = wi.contract_code')
                      ->where($kkk)
                      ->sum('contract_fee');
          $rprice = $rprice1+$rprice2;
          $data['sale']['rprice'] = number_format ($rprice , 2 , '.' , ',' );
          if(!$rprice){
            $data['sale']['rprice'] = '0';
          }

          /*累计回款额*/
          $map5['wi.create_time'] = array('between',array($ss,$dd),'AND');
          $rp1 = M('cst_fd_withdraw as wi') 
                      ->join('left join oa_cst_pj_plan_phases as ph on ph.project_id = wi.project_id and wi.phases = ph.phases')
                      ->where($map5)
                      ->sum('PhasesFee');

          $kkk1['wi.create_time'] = array('between',array($ss,$dd),'AND');
          $kkk1['wi.status'] = '2';
          $kkk1['wi.plan_code'] = array('EXP','IS NULL');
          $rp2 = M('cst_fd_withdraw as wi') 
                      ->join('left join oa_cst_contract as con on con.contract_code = wi.contract_code')
                      ->where($kkk1)
                      ->sum('contract_fee');
          $rp[$i] = $rp1+$rp2;
          if(!$rp){
            $data['sale']['ze1'][] = '0';
          }else{
            $data['sale']['ze1'][] = $rp[$i];
          }
          $data['sale']['month'][] = $i."月";   

          }

        $this->ajaxReturn($data);
      }else if($auth['group_id'] =='17' || $auth['group_id'] =='14'){
        /*售后看板*/
        $data['status'] = 2;
        /*售后看板*/
          /*售后看板*/
          /*获取已收款的项目或项目阶段*/
          /*财务确认*/
          $mm['wi.status'] = '2';
          $service = M('cst_fd_withdraw as wi')
                                          ->field('wi.updata_time,pr.project_name,wi.phases,ph.etime,ph.products,ph.PhasesFee,wi.create_time')
                                          ->join('left join oa_cst_cti_project as pr on wi.project_id = pr.id')
                                          ->join('left join oa_cst_pj_plan_phases as ph on wi.project_id = ph.project_id and wi.phases = ph.phases')
                                          ->where($mm) 
                                          ->page($pageindex, $pagesize)
                                          ->select();  
          $ccc = M('cst_fd_withdraw as wi')
                                          // ->field('wi.updata_time,pr.project_name,wi.phases,ph.etime,ph.products,ph.PhasesFee')
                                          ->join('left join oa_cst_cti_project as pr on wi.project_id = pr.id')
                                          ->join('left join oa_cst_pj_plan_phases as ph on wi.project_id = ph.project_id and wi.phases = ph.phases')
                                          ->where($mm) 
                                          ->count();
          if(!$gz){
            $data['after']['content'] = array(); 
            $data['after']['count'] = '0';
          }else{
            $data['after']['content'] = $service; 
            $data['after']['count'] = $ccc;
            
          }


        $this->ajaxReturn($data);
      }else if($auth['group_id'] =='3' || $auth['group_id'] =='9'|| $auth['group_id'] =='18'){
        /*实施看板*/
        $data['status'] = 3;
        /*实施看板*/
          /*正在实施项目*/
          $start_time = date("Y",time());
          $start = $start_time.'-01-01 00:00:00';
          $end = date("Y-m-d h:m:s",time()+60*60*24);
          $tmap['pl.create_time'] = array('between',array($start,$end),'AND');
          $tmap['pl.status'] = '1';
          $data['tech']['tcout'] = M('cst_pj_plan as pl')
                        ->where($tmap)
                        ->count();
          $data['tech']['phases']['content'] = M('cst_pj_plan_phases as ph') 
                                    ->field('pr.project_name,ph.phases,ph.products,me.nickname as executor,ph.PhasesFee')
                                    ->join('left join oa_cst_pj_plan as pl on pl.project_id = ph.project_id')
                                    ->join('left join oa_cst_cti_project as pr on ph.project_id = pr.id')
                                    ->join('left join oa_member as me on ph.executor = me.uid')
                                    ->where($tmap)
                                    ->select();

          // $data['tech']['sql'] = M()->getLastSql();
          $data['tech']['phases']['pcount'] = M('cst_pj_plan_phases as ph')
                                    ->join('left join oa_cst_pj_plan as pl on pl.project_id = ph.project_id')
                                    ->join('left join oa_cst_cti_project as pr on ph.project_id = pr.id')
                                    ->join('left join oa_member as me on ph.executor = me.uid')
                                    ->where($tmap)
                                    ->count();
          if(!$data['tech']['phases']['content']){
            $data['tech']['phases']['content'] = array(); 
          }
          if(!$data['tech']['phases']['pcount']){
            $data['tech']['phases']['pcount'] = '0'; 
          }

          /*获取实施回款金额*/
          $rmap['rd.create_time'] = array('between',array($start,$end),'AND');
          $rmap['rd.status'] = '2';
          $data['tech']['rmoney'] = M('cst_pj_payreminder_rd as rd') ->where($rmap)->sum('rmoney');
          $data['tech']['rmoney'] = number_format ($data['tech']['rmoney'], 2 , '.' , ',' );
          if(!$data['tech']['rmoney']){
            $data['tech']['rmoney'] = '0';
          }

          /*获取实施催款金额*/
          $rmap['rd.create_time'] = array('between',array($start,$end),'AND');
          $rmap['rd.status'] = array('in','0,1');
          $data['tech']['cmoney'] = M('cst_pj_payreminder_rd as rd') ->where($rmap)->sum('rmoney');
          $data['tech']['cmoney'] = number_format ($data['tech']['cmoney'], 2 , '.' , ',' );

          /*获取正在收款项目*/
          $rmap['rd.create_time'] = array('between',array($start,$end),'AND');
          $rmap['rd.status'] = array('in','0,1');
          $data['tech']['ckproject']['content'] = M('cst_pj_payreminder_rd as rd') 
                                                      ->field('pr.project_name,rd.phases,rd.status,rd.rmoney,rd.prompt_result,rd.rpercent,rd.prompt_id,me.nickname as executor')
                                                      ->join('oa_cst_cti_project as pr on pr.id = rd.project_id')
                                                      ->join('left join oa_member as me on rd.executor = me.uid')
                                                      ->where($rmap)
                                                      ->select();

          $data['tech']['ckproject']['ckcount'] = M('cst_pj_payreminder_rd as rd') 
                                                      ->field('pr.project_name,rd.phases,rd.status,rd.rmoney')
                                                      ->join('oa_cst_cti_project as pr on pr.id = rd.project_id')
                                                      ->where($rmap)
                                                      ->count();

          if(!$data['tech']['ckproject']['content']){
            $data['tech']['ckproject']['content'] = array(); 
          }
          if(!$data['tech']['ckproject']['ckcount']){
            $data['tech']['ckproject']['ckcount'] = '0'; 
          }                                           
                                                                                            
          /*柱状图项目跟踪费用和人天*/      
          $num = intval(date("m",time()));
          for($j1=1;$j1<=$num;$j1++){
            $jj1 = sprintf("%02d", $j1);
            $jj1 = sprintf("%02d", $j1+1);
            // $data['ze'][$i] = $i;
            $y1 = date("Y",time());
            $sss1 = $y1.'-01-01 00:00:00';
            $dds1 = $y1.'-'.$jj1.'-01 00:00:00';

            /*累计销售额*/
            $map81['rd.rd_time'] = array('between',array($sss1,$dds1),'AND');
            $manday1 = M('cst_pj_excute_rd as rd') 
                            ->field('rd.project_id,pr.project_name,rd.phases,rd.products,rd.project_fee,rd.content,rd.executor,rd.manday')
                            ->join('left join oa_cst_cti_project as pr on rd.project_id = pr.id')
                            // ->join('left join oa_member as me on rd.executor = me.uid')
                            ->where($map81)
                            ->select();
           
            $data['tech']['manday']['content'] = $manday1;
            $data['tech']['manday']['count'] = M('cst_pj_excute_rd as rd') 
                                            ->field('rd.project_id,pr.project_name,rd.phases,rd.products,rd.project_fee,rd.content,rd.executor,rd.manday')
                                            ->join('left join oa_cst_cti_project as pr on rd.project_id = pr.id')
                                            // ->join('left join oa_member as me on rd.executor = me.uid')
                                            ->where($map81)
                                            ->count();
            if(!$data['tech']['manday']['content']){
              $data['tech']['manday']['content'] = array(); 
            }
            if(!$data['tech']['manday']['count']){
              $data['tech']['manday']['count'] = '0'; 
            }      
              
          } 
        $this->ajaxReturn($data);  
      }else if($auth['group_id'] =='8' || $auth['group_id'] =='9' || $auth['group_id'] =='10'){
      
        /*开发看板*/
        $data['status'] = 4;
          /*开发看板*/
          /*获取正在开发新产品数目*/
          $ndmap['status'] = '1';
          $newdevnum = M('cst_dev_newproduct')->where($ndmap)->count();  
          $data['dev']['dcount'] = $newdevnum; 
          
          /*获取正在开发定制产品数目*/
          $ndmap['status'] = '2';
          $newdevnum1 = M('cst_dev_customization')->where($ndmap)->count();  
          $data['dev']['ccount'] = $newdevnum1;  

           /*获取定制开发人天*/
          $ndmap['status'] = '2';
          $cusmanday = M('cst_dev_customization')->where($ndmap)->sum('manday');  
          $data['dev']['cusmanday'] = $cusmanday;  
          if(!$cusmanday){
            $data['dev']['cusmanday'] = '0';  
          }

          /*获取已到期开发阶段*/
          $pmap['cu.status'] = '2';
          $pmap['ph.etime'] = array('elt',date("Y-m-d h:m:s",time()));
          $devphases = M('cst_cus_dev_phases as ph') 
                             ->field('ph.etime,ph.products,pr.project_name,ph.phases,ph.PhasesFee,me.nickname as dev_role') 
                             ->join('left join oa_cst_dev_customization as cu on cu.plan_code = ph.plan_code') 
                             ->join('left join oa_cst_cti_project as pr on cu.project_id = pr.id') 
                             ->join('left join oa_member as me on me.uid = cu.dev_role') 
                             ->where($pmap) 
                             ->select();
          // $data['dev']['sql'] = M()->getLastSql();
          $devcount = M('cst_cus_dev_phases as ph') 
                             // ->field('') 
                             ->join('left join oa_cst_dev_customization as cu on cu.plan_code = ph.plan_code') 
                             ->join('left join oa_cst_cti_project as pr on cu.project_id = pr.id') 
                             ->join('left join oa_member as me on me.uid = ph.coder') 
                             ->where($pmap) 
                             // ->select();
                             ->count();
          if(!$devphases){
            $data['dev']['devphases']['content'] = array();
            $data['dev']['devphases']['count'] = '0';
          }else{
            $data['dev']['devphases']['content'] = $devphases;                     
            $data['dev']['devphases']['count'] = $devcount;                     
          }

          /*获取开发交付记录*/
          $num = intval(date("m",time()));
          $jj1 = sprintf("%02d", $num+1);
          $y = date("Y",time());
          $sss1 = $y.'-01-01 00:00:00';
          $dds1 = $y.'-'.$jj1.'-01 00:00:00';
          $dtmap['de.del_date'] = array('between',array($sss1,$dds1),'AND');
          $data['dev']['delivery']['content'] = M('cst_dev_delivery as de')
                                                      ->field('de.delivery_code,de.delivery_type,pr.project_name,de.del_date,de.deliver_product,de.dev_role,de.tech_role,cus.eEnd_time,cus.End_time,cus.Need_time')
                                                      ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
                                                      ->join('left join oa_cst_dev_customization as cus on de.contract_code and de.project_id = cus.project_id')
                                                      ->where($dtmap)
                                                      ->order('del_date desc')
                                                      ->page(1, 10)
                                                      ->select();
          foreach ($data['dev']['delivery']['content'] as $keyd => $valued) {
              if($valued['delivery_type'] == '1'){
                $data['dev']['delivery']['content'][$keyd]['delivery_type'] = '需求新增';
              }elseif ($valued['delivery_type'] == '2') {
                $data['dev']['delivery']['content'][$keyd]['delivery_type'] = 'bug修复';
              }elseif ($valued['delivery_type'] == '3') {
                $data['dev']['delivery']['content'][$keyd]['delivery_type'] = '首次交付';
              }
          }  
          $data['dev']['delivery']['count'] = M('cst_dev_delivery as de')
                                                      ->field('de.delivery_code,pr.project_name,de.del_date,de.deliver_product,de.dev_role,de.tech_role')
                                                      ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
                                                      ->join('left join oa_cst_dev_customization as cus on de.contract_code and de.project_id = cus.project_id')
                                                      ->where($dtmap)
                                                      ->count();  

          if(!$data['dev']['delivery']['content']){
            $data['dev']['delivery']['content'] = array();
            
          }
          if(!$data['dev']['delivery']['count']){
            $data['dev']['delivery']['count'] = '0';
            
          }

          /*交付情况对比*/
            /*获取超时交付项目数目*/
            $exNum = M('cst_dev_delivery as de')
                                  ->join('left join oa_cst_dev_customization as cu on cu.project_id = de.project_id')
                                  ->where('de.del_date > cu.eEnd_time')
                                  ->count();
            $data['dev']['circle']['exNum'] = $exNum;
            
            /*获取提前交付项目数目*/
            $aheadNum = M('cst_dev_delivery as de')
                                  ->join('left join oa_cst_dev_customization as cu on cu.project_id = de.project_id')
                                  ->where('de.del_date < cu.eEnd_time')
                                  ->count();
            $data['dev']['circle']['aheadNum'] = $aheadNum;

            /*获取按时交付项目数目*/
            $Num = M('cst_dev_delivery as de')
                                  ->join('left join oa_cst_dev_customization as cu on cu.project_id = de.project_id')
                                  ->where('de.del_date = cu.eEnd_time')
                                  ->count();
            $data['dev']['circle']['Num'] = $Num;

        $this->ajaxReturn($data);
      }
  
      
    }


    /*售后面板Ajax搜索*/
    public function ajaxAfter(){
          /*获取已收款的项目或项目阶段*/
          /*财务确认*/
          if(I('project_name')){
            $mm['pr.project_name'] = array('like', '%' . (string)I('project_name') . '%');
          }

          $pageindex = I('page');
          if (empty($pageindex)||$pageindex=="0") {
              $pageindex['p']=1;
          }
          $pagesize = 5;
         
          $mm['wi.status'] = '2';
          $service = M('cst_fd_withdraw as wi')
                                          ->field('wi.updata_time,pr.project_name,wi.phases,ph.etime,ph.products,ph.PhasesFee')
                                          ->join('left join oa_cst_cti_project as pr on wi.project_id = pr.id')
                                          ->join('left join oa_cst_pj_plan_phases as ph on wi.project_id = ph.project_id and wi.phases = ph.phases')
                                          ->where($mm) 
                                          ->page($pageindex['p'], $pagesize)
                                          ->select();  
          $data['after']['content'] = $service;
          if(!$service){
            $data['after']['content'] = array();
          }
          $data['after']['count'] = M('cst_fd_withdraw as wi')
                                          ->field('wi.updata_time,pr.project_name,wi.phases,ph.etime,ph.products,ph.PhasesFee')
                                          ->join('left join oa_cst_cti_project as pr on wi.project_id = pr.id')
                                          ->join('left join oa_cst_pj_plan_phases as ph on wi.project_id = ph.project_id and wi.phases = ph.phases')
                                          ->where($mm) 
                                          ->page($pageindex['p'], $pagesize)
                                          ->count(); 
          $this ->ajaxReturn($data);
          
    }


    /*ajax分页*/
    public function getPageContent(){
      /*获取分页类型*/
      $type = $_GET["type"];
      /*获取页数*/
      $pageindex = $_GET["gzpage"];
      if (empty($pageindex)||$pageindex=="0") {
          $pageindex=1;
      }
      $pagesize = PAGESIZE;
      
      /*获取跟踪记录*/
      if($type == 1){
          $dd[] = '0';
          $dd[] = '1';
          

          /*获取跟踪记录*/
          $map3['pr.status'] = array('in',$dd);
          $gz = M('cst_cti_project as pr') 
                      ->field('pr.project_name,pr.province,pr.budget,cu.customer,pr.charge_person,pr.purchase_intention')
                      ->join('left join oa_cst_customer as cu on pr.customer = cu.id')
                      ->join('left join oa_member as me on me.uid = pr.charge_person')
                      ->where($map3)
                      ->page($pageindex, $pagesize)
                      ->select();
          foreach ($gz as $key6 => $value6) {
            $gz[$key6]['budget'] = number_format ($value6['budget'] , 2 , '.' , ',' );
          }
          // $this ->ajaxReturn(M()->getLastSql());
          $this ->ajaxReturn($gz);
      }elseif ($type == 2) {
        /*获取售后记录*/
        $mm['wi.status'] = '2';
        $service = M('cst_fd_withdraw as wi')
                      ->field('wi.updata_time,pr.project_name,wi.phases,ph.etime,ph.products,ph.PhasesFee,wi.create_time')
                      ->join('left join oa_cst_cti_project as pr on wi.project_id = pr.id')
                      ->join('left join oa_cst_pj_plan_phases as ph on wi.project_id = ph.project_id and wi.phases = ph.phases')
                      ->where($mm) 
                      ->page($pageindex, $pagesize)
                      ->select();  
        $this ->ajaxReturn($service);
      }elseif ($type == 4) {
        # code...
      }elseif ($type == 5) {
        # code...
      }elseif ($type == 6) {
        # code...
      }elseif ($type == 7) {
        $num = intval(date("m",time()));
        $jj1 = sprintf("%02d", $num+1);
        $y = date("Y",time());
        $sss1 = $y.'-01-01 00:00:00';
        $dds1 = $y.'-'.$jj1.'-01 00:00:00';
        $dtmap['de.del_date'] = array('between',array($sss1,$dds1),'AND');
        $data = M('cst_dev_delivery as de')
                        ->field('de.delivery_code,de.delivery_type,pr.project_name,de.del_date,de.deliver_product,de.dev_role,de.tech_role,cus.eEnd_time,cus.End_time,cus.Need_time')
                        ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
                        ->join('left join oa_cst_dev_customization as cus on de.contract_code and de.project_id = cus.project_id')
                        ->where($dtmap)
                        ->order('del_date desc')
                        ->page($pageindex, $pagesize)
                        ->select(); 
        foreach ($data as $keyd => $valued) {
            if($valued['delivery_type'] == '1'){
              $data[$keyd]['delivery_type'] = '需求新增';
            }elseif ($valued['delivery_type'] == '2') {
              $data[$keyd]['delivery_type'] = 'bug修复';
            }elseif ($valued['delivery_type'] == '3') {
              $data[$keyd]['delivery_type'] = '首次交付';
            }
        }   
        $this ->ajaxReturn($data); 
      }

    }

    /*ajax获取对应项目进度*/
    public function getProcessByPid(){
      $data = I('project');
      foreach ($data as $key => $value) {
        $map['id'] = $value;
        $projects[] = M('cst_cti_project')->field('id,project_name')->where($map)->find();
      }
      foreach ($projects as $key1 => $value1) {
        /*获取陌生拜访*/
        $map1['project_id'] = $value1['id'];
        $map1['progress'] = '陌生拜访';
        $se= M('cst_cti_project_tail')->field('start,end')->where($map1)->select();
        // $finn['sql'] = M()->getLastSql();
        $dsum = 0;
        foreach ($se as $key2 => $value2) {
          if( $value2['end'] = $value2['start'] ){
            $dsum = $dsum+1;
          }else{
            $dsum = $dsum+(strtotime($value2['end'])-strtotime($value2['start']))/60/60/24;
          }
        }
        $dd[$value1['project_name']]['陌生拜访'] = $dsum;
        

        /*获取方案提升*/
        $map1['project_id'] = $value1['id'];
        $map1['progress'] = '方案提升';
        $se= M('cst_cti_project_tail')->field('start,end')->where($map1)->select();
        $dsum = 0;
        foreach ($se as $key3 => $value3) {
          if($value3['end'] = $value3['start'] ){
            $dsum = $dsum+1;
          }else{
            $dsum = $dsum+(strtotime($value3['end'])-strtotime($value3['start']))/60/60/24;
          }
        }
        $dd[$value1['project_name']]['方案提升'] = $dsum;

        /*获取项目投标*/
        $map1['project_id'] = $value1['id'];
        $map1['progress'] = '项目投标';
        $se= M('cst_cti_project_tail')->field('start,end')->where($map1)->select();
        $dsum = 0;
        foreach ($se as $key4 => $value4) {
          if( $value4['end'] = $value4['start'] ){
            $dsum = $dsum+1;
          }else{
            $dsum = $dsum+(strtotime($value4['end'])-strtotime($value4['start']))/60/60/24;
          }
        }
        $dd[$value1['project_name']]['项目投标'] = $dsum;

        /*获取商务谈判*/
        $map1['project_id'] = $value1['id'];
        $map1['progress'] = '商务谈判';
        $se= M('cst_cti_project_tail')->field('start,end')->where($map1)->select();
        $dsum = 0;
        foreach ($se as $key5 => $value5) {
          if( $value5['end'] = $value5['start'] ){
            $dsum = $dsum+1;
          }else{
            $dsum = $dsum+(strtotime($value5['end'])-strtotime($value5['start']))/60/60/24;
          }
        }
        $dd[$value1['project_name']]['商务谈判'] = $dsum;

        /*获取合同签订*/
        $map1['project_id'] = $value1['id'];
        $map1['progress'] = '合同签订';
        $se= M('cst_cti_project_tail')->field('start,end')->where($map1)->select();
        $dsum = 0;
        foreach ($se as $key6 => $value6) {
          if( $value6['end'] = $value6['start'] ){
            $dsum = $dsum+1;
          }else{
            $dsum = $dsum+(strtotime($value6['end'])-strtotime($value6['start']))/60/60/24;
          }
        }
        $dd[$value1['project_name']]['合同签订'] = $dsum;
      }

      // $finn['project_name'] =  $projects;
      foreach ($dd as $key7 => $value7) {
        if($value7['陌生拜访'] == 0){
          $finn['a'][] = '';
        }else{
          $finn['a'][] = $value7['陌生拜访'];
        }

        if($value7['方案提升'] == 0){
          $finn['b'][] = '';
        }else{
          $finn['b'][] = $value7['方案提升'];
        }

        if($value7['项目投标'] == 0){
          $finn['c'][] = '';
        }else{
          $finn['c'][] = $value7['项目投标'];
        }

        if($value7['商务谈判'] == 0){
          $finn['d'][] = '';
        }else{
          $finn['d'][] = $value7['商务谈判'];
        }
        if($value7['合同签订'] == 0){
          $finn['e'][] = '';
        }else{
          $finn['e'][] = $value7['合同签订'];
        }
        // $finn['b'][] = $value7['方案提升'];
        // $finn['c'][] = $value7['项目投标'];
        // $finn['d'][] = $value7['商务谈判'];
        // $finn['e'][] = $value7['合同签订'];
      }
      foreach ($projects as $key8 => $value8) {
        $finn['project_name'][] =  $value8['project_name'];
      }

      $this ->ajaxReturn($finn);
    }

    /*ajax获取对应新产品开发状态拉去新产品开发人天*/
    public function getProductByStatus(){
        if(I('status')!=='5'){
          $map['status'] = I('status');
        }else{
          $map['status']= array('in','0,1,2,3,4');
        }
        $res = M('cst_dev_newproduct')->field('product_name,manday,start_time,ac_time')->where($map)->select();
        foreach ($res as $key => $value) {
          $data['a'][] = $value['product_name'];
          if($value['start_time'] =='0000-00-00'||$value['start_time']==''){
            $data['b'][] = 0;
            $data['c'][] = 0;
          }else{
            $data['b'][] = $value['manday'];
            if($value['ac_time']=='0000-00-00'||$value['ac_time']==''){
              $dd = date('Y-m-d');
              $data['c'][] = (strtotime($dd)-strtotime($value['start_time']))/60/60/24;
            }else{
              $data['c'][] = (strtotime($value['ac_time'])-strtotime($value['start_time']))/60/60/24;
            }
          }
        }
        $this ->ajaxReturn($data);
    }

    /*ajax获取对应定制开发状态拉取开发人天*/
    public function getCusByStatus(){
        $map['project_name'] = I('name');
        $res1 = M('cst_dev_customization') ->field('discribe,products,manday,End_time,Need_time')->where($map)->select();

        foreach ($res1 as $key => $value) {
          $data['a'][] = $value['products'];
          $data['b'][] = $value['manday'];
          if($value['Need_time']!==NULL&&$value['Need_time']!=='0000-00-00'&&$value['End_time']!==NULL&&$value['End_time']!=='0000-00-00'){
            $data['c'][] = (strtotime($value['End_time'])-strtotime($value['Need_time']))/60/60/24;
          }else{
            $data['c'][] = 0;
          }
          $data['d'][] = $value['discribe'];
        }
        // $this ->ajaxReturn(M()->getLastSql());
        $this ->ajaxReturn($data);
    }

    /*ajax获取时间段内的交付情况*/
    public function getDelByTime(){
      $d1 = date('Y-m-d H:i:s', strtotime(I('d1')));
      $d2 = date('Y-m-d H:i:s', strtotime(I('d2')));

      /*交付情况对比*/
      /*获取超时交付项目数目*/
      $s1 = "(de.create_time between '".$d1."' and '".$d2."') and  de.del_date > cus.eEnd_time";
      $exNum = M('cst_dev_delivery as de')
                            ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
                            ->where($s1)
                            ->count('de.id');
      $data['dev']['circle']['exNum'] = $exNum;
      $data['dev']['circle']['sql'] = M()->getLastSql();

      // $exPr = M('cst_dev_delivery as de')
      //                       ->field('pr.project_name')
      //                       ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
      //                       ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
      //                       ->where($s1)
      //                       ->select();
      // $data['dev']['circle']['exPr'] = $exPr;


      /*获取提前交付项目数目*/
      $s2 = "(de.create_time between '".$d1."' and '".$d2."') and  de.del_date < cus.eEnd_time";
      $aheadNum = M('cst_dev_delivery as de')
                            ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
                            ->where($s2)
                            ->count();
      $data['dev']['circle']['aheadNum'] = $aheadNum;

      // $aheadPr = M('cst_dev_delivery as de')
      //                       ->field('pr.project_name')
      //                       ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
      //                       ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
      //                       ->where($s2)
      //                       ->select();
      // $data['dev']['circle']['aheadPr'] = $aheadPr;

      /*获取按时交付项目数目*/
      $s3 = "(de.create_time between '".$d1."' and '".$d2."') and  de.del_date = cus.eEnd_time";
      $Num = M('cst_dev_delivery as de')
                            ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
                            ->where($s3)
                            ->count();
      $data['dev']['circle']['Num'] = $Num;

      // $NumPr = M('cst_dev_delivery as de')
      //                       ->field('pr.project_name')
      //                       ->join('left join oa_cst_dev_customization as cus on cus.project_id = de.project_id and cus.contract_code = de.contract_code')
      //                       ->join('left join oa_cst_cti_project as pr on pr.id = de.project_id')
      //                       ->where($s3)
      //                       ->select();
      // $data['dev']['circle']['NumPr'] = $NumPr;

      $this ->ajaxReturn($data);
    }
}
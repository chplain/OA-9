<?php
// +----------------------------------------------------------------------
// | Author: chenlei <714753756@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
// use User\Api\UserApi as UserApi;
use Think\Page;
/**
 * 后台OA控制器
 * @author chenlei <714753756@qq.com>
 */
class ProductController extends AdminController {

    // static protected $allow = array( 'verify');

    
    public function index(){

      $pageindex['p'] = $_GET["p"];
      if (empty($pageindex['p'])||$pageindex['p']=="0") {
          $pageindex['p']=1;
      }
      $pagesize = PAGESIZE;

      $map['pr.status'] = array('in','1,2');
      $product = M("cst_product as pr")
            ->field('pr.*,m.nickname as charge,m1.nickname as creator,m2.nickname as uptor')
            ->join('left join oa_member as m on m.uid=pr.charge_person')
            ->join('left join oa_member as m1 on m1.uid=pr.create_person')
            ->join('left join oa_member as m2 on m2.uid=pr.update_person')
            ->where($map)
            ->page($pageindex['p'],$pagesize)
            ->order('pr.product_name asc')
            ->select();

      $count=M("cst_product as pr")
            ->field('pr.*')
            ->where($map)
            ->count();

      if($count > $pagesize) {
          $page = new \COM\Page($count, $pagesize,$pageindex);
          // var_dump($page);
          $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
          $this->assign('_page', $page->show());
      }

      $this->assign('product',$product);
      // var_dump(M()->getLastSql());
      // var_dump($product);
      // var_dump(UID);
      $this->display();
    }

    public function add(){

      if(IS_POST){
        /*获取最大uid*/
        $maxid = M("cst_product")
                    ->field('id')
                    ->order('id desc')
                    ->find();
        $data['product_code'] = "CP".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
        $data['product_versions'] = I('product_versions');
        $data['product_type'] = I('product_type');
        $data['product_name'] = I('product_name');
        $data['charge_person'] = I('charge_person');
        $data['link_phone'] = I('link_phone');
        $data['create_person'] = UID;
        $data['create_time'] = date("Y-m-d H:i:s",time());
        // var_dump(I());
        // die();
        $mapp['product_name'] = I('product_name');
        $res = M("cst_product") ->where($mapp)->find();
        if($res){
          $this->error('添加失败,该产品已存在！');
        }
        $res = M("cst_product")->add($data);
        if(!$res){
            $this->error('添加失败！');
        } else {
            $this->success('添加成功！',U('index'));
        }
      } else {
        /*拉去产品负责人*/
        $map1['ac.group_id'] = 10;
        $devs = M('member as me')
                ->field('me.uid,me.nickname')
                ->join('left join oa_auth_group_access as ac on ac.uid=me.uid')
                ->where($map1)
                ->select();   
        $this->assign('devs',$devs);
        // var_dump($devs);
        $this->display();
      }
 
    }

    /*产品作废*/
    public function delete(){

      /*获取删除合同数组*/
      $idArray = array_unique((array)I('ids'));
      // $uidArray = array(4);
      $map['id'] = array('in', $idArray);
      $data['status'] = '3';
      $res = M("cst_product")->where($map)->save($data);
      // var_dump(M()->getLastSql());
      if(!$res){
          $this->error('作废失败！');
      } else {
          $this->success('作废成功！',U('index'));
      }
    }


    /*导出数据到excel中*/
    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle.date('_Ymd_His');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));//第一行标题
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        // header('pragma:public');
        // header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        // header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        // $objWriter->save('php://output');
        $objWriter->save('./Uploads/Excel/'.$xlsTitle.'.xls');
        exit;
    }

    function expUser($xlsName='', $xlsCell=array(), $xlsModel=''){//导出Excel
        $xlsName  = "User用户数据表";
        $xlsCell  = array(
            array('id','账号序列'),
            array('nickname','名字'),
            array('mobile','手机号'),
            array('status','状态'),
            array('addtime','创建时间'),
        );
        $xlsModel = M('Member');

        $xlsData  = $xlsModel->Field('id,nickname,mobile,status,addtime')->select();
        foreach ($xlsData as $k => $v)
        {
            $xlsData[$k]['status'] = 1 ? '正常':'锁定';
            $xlsData[$k]['addtime'] = date("Y-m-d H:i:s", $v['addtime']);
        }
        $this->exportExcel($xlsName,$xlsCell,$xlsData);

    }

    /*各种excel表格内容导入并存数据库*/
    public function excel_upload(){
      if(IS_POST){

        /* 调用文件上传组件上传文件 */
        $File = D('File');
        $info = $File->upload($_FILES, C('DOWNLOAD_UPLOAD')); //TODO:上传到远程服务器
        // var_dump($info);
        if($info){   
          // echo 123;
          $filePath = './Uploads/Download/'.$info['filename']['savepath'].$info['filename']['savename'];
          // var_dump($info);
          // var_dump($filePath);
          // die();
          $this->excel_import($filePath,'xlsx');
        }
      }else {
        $this->meta_title = '导入excel文件';
        $this->display();
      }
    }


    //导入数据方法
    public function excel_import($filename, $exts='xlsx'){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        // Vendor("PHPExcel");
        Vendor("PHPExcel.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        if($exts == 'xls'){
          Vendor("PHPExcel.Reader.Excel5");
          $PHPReader=new \PHPExcel_Reader_Excel5();
        }else if($exts == 'xlsx'){
          Vendor("PHPExcel.Reader.Excel2007");
          $PHPReader=new \PHPExcel_Reader_Excel2007();
        }

        // var_dump($PHPReader);
        //载入文件
        $PHPExcel=$PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(4);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        // $allColumn = 'I';
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=2;$currentRow<=$allRow;$currentRow++){
          //从哪列开始，A表示第一列
          for($currentColumn='A';$currentColumn<='N';$currentColumn++){
            //数据坐标
            $address=$currentColumn.$currentRow;
            //读取到的数据，保存到数组$arr中
            $data[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
            // var_dump($currentSheet->getCell($address)->getValue());
          }

        }
      // var_dump($data);
      // die();
      $this->save_import($data);
    }

    //保存导入数据
    public function save_import($data){

      /*合同阶段导入*/
      foreach ($data as $key => $value) {
        $map['project_name'] = $value['A'];
        $rr = M('cst_cti_project') ->field('id')->where($map)->find();
        $data1['project_id'] = $rr['id'];
        $data1['phases'] = $value['D'];
        $data1['fee'] = $value['E'];
        $data1['products'] = $value['B'];
        $data1['stime'] = $value['C'];
        $data1['etime'] = $value['F'];
        // var_dump($data1);
        M('cst_pj_phases')->add($data1);
      }

      /*产品导入*/
        // foreach ($data as $key => $value) {
        //     /*获取最大uid*/
        //     $maxid = M("cst_product")
        //                 ->field('id')
        //                 ->order('id desc')
        //                 ->find();
        //     $data1['product_code'] = "CP".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
        //     $data1['product_name'] = $value['A'];
        //     $data1['product_versions'] = $value['B'];
        //     $data1['remark'] = $value['D'];
        //     $data1['charge_person'] = '30';
        //     $data1['link_phone'] = '18123218996';
        //     $data1['create_person'] = UID;
        //     $data1['create_time'] = date("Y-m-d H:i:s",(time()+$key*10));
        //     $data2['product_name'] = $value['A'];
        //     // var_dump($data1);
        //     $issi = M('cst_product') ->where($data2)->find();
        //     if(!$issi){
        //       M('cst_product') ->add($data1);
        //     }
        // }
        // die();

      /*客户导入*/
        // foreach ($data as $key => $value) {
        //     /*获取最大uid*/
        //     $maxid = M("cst_customer")
        //                   ->field('id')
        //                   ->order('id desc')
        //                   ->find();
            
        //     $data1['customer_code'] = "CU".sprintf("%06d", $maxid['id']+1);//生成4位数，不足前面补0   
        //     $data1['customer'] = $value['A'];
        //     $data1['linkman'] = $value['B'];
        //     $data1['link_phone'] = $value['C'];
        //     $data1['wechat'] = $value['D'];
        //     $data1['link_address'] = $value['E'];
        //     if($value['F'] == '否'){
        //       $data1['group_visitor'] = '0';
        //     }else{
        //       $data1['group_visitor'] = '1';
        //     }
        //     if($value['G'] == '正常'){
        //       $data1['status'] = '1';
        //     }else{
        //       $data1['status'] = '0';
        //     }

        //     $data1['create_person'] = UID;
        //     $data1['create_time'] = date("Y-m-d H:i:s",(time()+$key*10));
        //     // var_dump($data1);
        //     $res = M("cst_customer")->add($data1);
         

        // }
      /*项目导入*/
        // foreach ($data as $k=>$value){
        //     $maxid = M("cst_cti_project")
        //             ->field('id')
        //             ->order('id desc')
        //             ->find();
        //     $data1['project_code'] = "PJ".sprintf("%06d", $maxid['id']+1);//生成4位数，不足前面补0   
        //     $data1['project_name'] = $value['A'];
        //     $data1['project_type'] = '1';

        //     $map1['customer'] = $value['C'];
        //     $customer = M('cst_customer') ->field('id')->where($map1)->find();
        //     $data1['customer'] = $customer['id'];
        //     $data1['purchase_intention'] = $value['D'];
        //     $data1['budget'] = $value['E'];
        //     $data1['begin_time'] = $value['F'];
        //     $data1['open_time'] = $value['G'];
        //     $data1['charge_person'] = $value['H'];
        //     $data1['linkman'] = $value['I'];
        //     $data1['linkphone'] = $value['J'];
        //     $data1['wechat'] = $value['K'];
        //     $data1['province'] = str_replace("+","-",$value['N']);
        //     if($value['M'] == '新增'){
        //       $data1['status'] = '0';
        //     }elseif($value['M'] == '进行中'){
        //       $data1['status'] = '1';
        //     }elseif($value['M'] == '已签约'){
        //       $data1['status'] = '2';
        //     }elseif($value['M'] == '已终止'){
        //       $data1['status'] = '3';
        //     }
        //     $data1['creator'] = UID;
        //     $data1['create_time'] = date("Y-m-d H:i:s",time());
        //     // var_dump($data1);
        //     // $res = M("cst_cti_project")->add($data1);
        //     // /*新增跟踪记录*/
        //     $mapp['project_name'] = $value['A'];
        //     $ss = M("cst_cti_project") ->field('id') ->where($mapp)->find();
        //     // var_dump(M()->getLastSql());
        //     $data2['project_id'] = $ss['id'];
        //     $data2['follow_up_time'] = $value['F'];
        //     $data2['discribe'] = $value['L'];
        //     $data2['tail_person'] = $value['H'];
        //     // var_dump($data2);
        //     if($value['L']){
        //         $res1 = M("cst_cti_project_tail")->add($data2);
        //     }
          
        // }
      /*合同导入*/
      // foreach ($data as $k=>$value){
      //       $map['project_name'] = $value['B'];
      //       $rr = M('cst_cti_project') ->field('id')->where($map)->find();
      //       // var_dump(M()->getLastSql());
      //       $maxid = M("cst_contract")
      //               ->field('id')
      //               ->order('id desc')
      //               ->find();
      //       /*合同表添加*/
      //       $data1['project_id'] = $rr['id'];
           
      //       $data1['intention_contract_code'] = "YX".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
      //       $data1['contract_code'] = "HT".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
      //       /*采购产品列表*/  
      //       $data1['contract_productlist'] = $value['C'];
      //       $data1['contract_type'] = '1';
      //       $data1['start_time'] = $value['D'];
      //       $data1['contract_fee'] = $value['E'];
      //       $data1['charge_person'] = 35;
              
      //       $data1['status'] = 3;  
      //       $data1['create_person'] = UID;  
      //       $data1['create_time'] = date("Y-m-d H:i:s",(time()+$key*10));
      //       // var_dump($data1);
      //       // die();
      //       $res = M('cst_contract') ->add($data1);
      // }
      
          

      /*定制开发计划录入*/
      // foreach ($data as $key => $value) {
      //   if($value['A'] !=""){
      //     $map['project_name'] = $value['A'];
      //     $res1 = M('cst_cti_project')->field('id') ->where($map)->find();
      //     $maxid = M("cst_dev_customization")
      //               ->field('id')
      //               ->order('id desc')
      //               ->find();
      //     /*新产品表添加*/
      //     $data1['plan_code'] = "KF".date("Ymd",time()).sprintf("%05d", $maxid['id']+1);//生成4位数，不足前面补0 
      //     $data1['project_id'] = $res1['id'];
      //     $data1['project_name'] = $value['A'];
      //     $data1['products'] = $value['B'];
      //     $data1['discribe'] = $value['D'];
      //     $data1['Need_time'] = $value['E'];
      //     $data1['Sure_time'] = $value['F'];
      //     $data1['eEnd_time'] = $value['G'];
      //     $data1['End_time'] = $value['H'];
      //     $data1['pr_manday'] = $value['I'];
      //     $data1['dev_manday'] = $value['J'];
      //     $data1['te_manday'] = $value['K'];
      //     $data1['manday'] = $data1['pr_manday']+$data1['dev_manday']+$data1['te_manday'];
      //     if($value['M'] =="需求分析中"){
      //       $data1['status'] = '1';
      //     }elseif($value['M'] =="已交付"){
      //       $data1['status'] = '2';
      //     }elseif($value['M'] =="已暂停"){
      //       $data1['status'] = '3';
      //     }elseif($value['M'] =="已终止"){
      //       $data1['status'] = '4';
      //     }else{
      //       $data1['status'] = '0';
      //     }
      //     $data1['remark'] = $value['N'];
      //     $data1['pr_role'] = $value['O'];
      //     $data1['dev_role'] = '30';
      //     $res = M('cst_dev_customization') ->add($data1);
      //     // var_dump($data1);
      //   }else{
      //     if($value['B'] !="" && $value['D'] !=""){
      //       $maxid = M("cst_dev_customization")
      //               ->field('id')
      //               ->order('id desc')
      //               ->find();
      //       /*新产品表添加*/
      //       $data1['plan_code'] = "KF".date("Ymd",time()).sprintf("%05d", $maxid['id']+1);//生成4位数，不足前面补0 
      //       $data1['project_id'] = $data1['project_id'];
      //       $data1['project_name'] = $data1['project_name'];
      //       $data1['products'] = $value['B'];
      //       $data1['discribe'] = $value['D'];
      //       $data1['Need_time'] = $value['E'];
      //       $data1['Sure_time'] = $value['F'];
      //       $data1['eEnd_time'] = $value['G'];
      //       $data1['End_time'] = $value['H'];
      //       $data1['pr_manday'] = $value['I'];
      //       $data1['dev_manday'] = $value['J'];
      //       $data1['te_manday'] = $value['K'];
      //       $data1['manday'] = $data1['pr_manday']+$data1['dev_manday']+$data1['te_manday'];
      //       if($value['M'] =="需求分析中"){
      //         $data1['status'] = '1';
      //       }elseif($value['M'] =="已交付"){
      //         $data1['status'] = '2';
      //       }elseif($value['M'] =="已暂停"){
      //         $data1['status'] = '3';
      //       }elseif($value['M'] =="已终止"){
      //         $data1['status'] = '4';
      //       }else{
      //         $data1['status'] = '0';
      //       }
      //       $data1['remark'] = $value['N'];
      //       $data1['pr_role'] = $value['O'];
      //       $data1['dev_role'] = '30';
      //       $res = M('cst_dev_customization') ->add($data1);
      //       // var_dump($data1);
      //     }

      //   }
      //   // var_dump($data1);
      // }

      
      // /*录入新产品计划*/
      // foreach ($data as $key => $value) {
      //   if($value['B'] !=""){
      //       // echo 132;
      //       // die();
      //       $maxid = M("cst_dev_newproduct")
      //                   ->field('id')
      //                   ->order('id desc')
      //                   ->find();
      //       /*新产品表添加*/
      //       $data['plan_code'] = "KF".date("Ymd",time()).sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
      //       $data['product_type'] = '2'; 
      //       $data['product_name'] = $value['B']; 
      //       $data['start_time'] = $value['E'];
      //       $data['end_time'] = $value['F']; 
      //       $data['manday'] = '31'; 
      //       $data['ac_time'] = $value['G']; 
      //       $data['dev_role'] = '30'; 
      //       $data['describe'] = $value['C'];
      //       $data['create_person'] = UID;
      //       $data['create_time'] = date("Y-m-d h:m:s",time());
      //       if($value['J'] =="正常"){
      //         $data['status'] = '1';
      //       }elseif($value['J'] =="延期"){
      //         $data['status'] = '4';
      //       }elseif($value['J'] =="完成"){
      //         $data['status'] = '2';
      //       }elseif($value['J'] =="暂停"){
      //         $data['status'] = '3';
      //       }
      //       $data['remark'] = $value['K'];
      //       $data['product_charge'] = $value['L'];
           
      //       $res = M('cst_dev_newproduct') ->add($data);
      //   }else{
      //     // echo 233;
      //     // die();
      //     $data1['plan_code'] = $data['plan_code'];
      //     $data1['product'] = $data['product_name'];
      //     $data1['product_jd'] = $value['D'];
      //     $data1['stime'] = $value['E'];
      //     $data1['etime'] = $value['F'];
      //     $data1['milestone'] = $value['H'];
      //     if($value['I'] =="未开始"){
      //       $data1['status'] = '0';
      //     }elseif($value['I'] =="进行中"){
      //       $data1['status'] = '1';
      //     }elseif($value['I'] =="已完成"){
      //       $data1['status'] = '2';
      //     }elseif($value['I'] =="已终止"){
      //       $data1['status'] = '3';
      //     }
      //     $res2 = M('cst_new_dev_phases') ->add($data1);

      //   }
      // }

      // var_dump($data);
      // die();



    
      /*录取产品（已去重）*/

        /*循环去‘、’*/
        // foreach ($data as $k=>$v){
        //   $data[$k] = explode('、',$v['D']);
        // }
        // foreach ($data as $key => $value) {
        //   foreach ($value as $key1 => $value1) {
        //     /*获取最大uid*/
        //     $maxid = M("cst_product")
        //                 ->field('id')
        //                 ->order('id desc')
        //                 ->find();
        //     $data['product_code'] = "CP".sprintf("%04d", $maxid['id']+1);//生成4位数，不足前面补0 
        //     $data['product_versions'] = '3.1';
        //     $data['product_name'] = $value1;
        //     $data['charge_person'] = '30';
        //     $data['link_phone'] = '18123218996';
        //     $data['create_person'] = UID;
        //     $data['create_time'] = date("Y-m-d H:i:s",time());
        //     $data1['product_name'] = $value1;
        //     $issi = M('cst_product') ->where($data1)->find();
        //     if(!$issi){
        //       M('cst_product') ->add($data);
        //     }
        //   }
        // }
        
      /*录取客户信息*/
      //   foreach ($data as $key => $value) {
      //     $map['customer'] = str_replace('项目','',$value['C']);
      //     $issi = M('cst_customer') ->where($map)->find();
      //     if(!$issi){

      //       /*获取最大uid*/
      //       $maxid = M("cst_customer")
      //                     ->field('id')
      //                     ->order('id desc')
      //                     ->find();
            
      //       $data['customer_code'] = "CU".sprintf("%06d", $maxid['id']+1);//生成4位数，不足前面补0   
      //       $data['customer'] = $map['customer'];
      //       $data['linkman'] = $value['W'];
      //       $data['link_phone'] = $value['X'];
      //       $data['wechat'] = '';
      //       $data['link_address'] = $value['B'];
      //       $data['group_visitor'] = '0';
      //       $data['create_person'] = UID;
      //       $data['create_time'] = date("Y-m-d H:i:s",time());
      //       $res = M("cst_customer")->add($data);
      //     }

      //   }
        


        // var_dump($data);
        // die();
      
      /*录取项目*/
        /*循环替换'、'为','*/
        // foreach ($data as $k=>$v){
        //   $map['project_name'] = $v['C'];
        //   $issi = M('cst_cti_project') ->where($map)->find();
        //   if(!$issi){
        //     $maxid = M("cst_cti_project")
        //             ->field('id')
        //             ->order('id desc')
        //             ->find();
        //     $data1['project_code'] = "PJ".sprintf("%06d", $maxid['id']+1);//生成4位数，不足前面补0   
        //     $data1['project_name'] = $v['C'];
        //     $data1['project_type'] = '1';
        //     $map1['customer'] = str_replace('项目','',$v['C']);
        //     $customer = M('cst_customer') ->field('id')->where($map1)->find();
        //     $data1['customer'] = $customer['id'];
        //     $data1['province'] = $v['B'];
        //     $data1['purchase_intention'] = str_replace('、',',',$v['D']);
        //     $data1['charge_person'] = $v['V'];
        //     $data1['creator'] = UID;
        //     $data1['create_time'] = date("Y-m-d H:i:s",time());
        //     $data1['budget'] = $v['E'];
        //     $data1['begin_time'] = $v['F'];
        //     $data1['open_time'] = $v['H'];
        //     // $data['discrebe'] = I('discrebe');
        //     // $data['remark'] = I('remark');
        //     $res = M("cst_cti_project")->add($data1);
        //   }
        // }
      }


    }

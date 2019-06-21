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
class ItemController extends AdminController {

    // static protected $allow = array( 'verify');

    
    public function index(){
      $product = M("item")->select();
      var_dump($product);
      // var_dump(UID);
      $this->display();
    }

    /*项目添加*/
    public function add(){
        $data['product_code'] = 0001;
        $data['item_name'] = I['item_name'];
        $data['province'] = I['province'].'-'.I['city'];
        $data['purchase_intention'] = implode(",", I['purchase_intention']);
        $data['charge_person'] = I['charge_person'];
        $data['creator'] = UID;
        $data['create_time'] = date("Y-m-d H:i:s",time());
        $data['budget'] = I['budget'];
        $data['begin_time'] = date("Y-m-d H:i:s",I['begin_time']);
        $data['discrebe'] = I['discrebe'];
        $res = M("item")->add($data);

    }

    /*项目删除*/
    public function delete(){
        echo "daily";
   
    }

    /*项目更新*/
    public function updata(){
        $data['product_code'] = 0001;
        $data['product_versions'] = I['product_versions'];
        $data['product_name'] = I['product_name'];
        $data['charge_person'] = I['charge_person'];
        $data['link_phone'] = I['link_phone'];
        $data['create_person'] = UID;
        $data['create_time'] = date("Y-m-d H:i:s",time());
        $res = M("product")->add($data);

    }

    /*项目跟踪记录添加*/
    public function tail_add(){
        $data['product_code'] = 0001;
        $data['item_name'] = I['item_name'];
        $data['province'] = I['province'].'-'.I['city'];
        $data['purchase_intention'] = implode(",", I['purchase_intention']);
        $data['charge_person'] = I['charge_person'];
        $data['creator'] = UID;
        $data['create_time'] = date("Y-m-d H:i:s",time());
        $data['budget'] = I['budget'];
        $data['begin_time'] = date("Y-m-d H:i:s",I['begin_time']);
        $data['discrebe'] = I['discrebe'];
        $res = M("item")->add($data);

    }

    /*项目跟踪记录删除*/
    public function tail_delete(){
        $data['product_code'] = 0001;
        $data['item_name'] = I['item_name'];
        $data['province'] = I['province'].'-'.I['city'];
        $data['purchase_intention'] = implode(",", I['purchase_intention']);
        $data['charge_person'] = I['charge_person'];
        $data['creator'] = UID;
        $data['create_time'] = date("Y-m-d H:i:s",time());
        $data['budget'] = I['budget'];
        $data['begin_time'] = date("Y-m-d H:i:s",I['begin_time']);
        $data['discrebe'] = I['discrebe'];
        $res = M("item")->add($data);

    }



}

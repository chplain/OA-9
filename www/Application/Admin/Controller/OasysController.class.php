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
class OasysController extends AdminController {

    // static protected $allow = array( 'verify');

    
    public function index(){
        // echo 123;
   //      if(UID){
      // $this->display();
   //      } else {
   //          $this->redirect('Public/login');
   //      }
        $this->display();
    }

    public function weekly(){
        echo "weekly";
   //      if(UID){
      // $this->display();
   //      } else {
   //          $this->redirect('Public/login');
   //      }
        // $this->display();
    }

    public function daily(){
        echo "daily";
   //      if(UID){
      // $this->display();
   //      } else {
   //          $this->redirect('Public/login');
   //      }
        // $this->display();
    }

}

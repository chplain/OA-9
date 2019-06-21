<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo ($meta_title); ?>|GDWS-OA管理平台</title>
  <link href="/OA/www/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/base.css" media="all">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/common.css" media="all">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/module.css">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/style.css" media="all">
  <!-- <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/new.css" media="all"> -->
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
  <!--[if lt IE 9]>
  <script type="text/javascript" src="/OA/www/Public/static/jquery-1.10.2.min.js"></script>
  <![endif]--><!--[if gte IE 9]><!-->
  <script type="text/javascript" src="/OA/www/Public/static/jquery-2.0.3.min.js"></script>
  <script type="text/javascript" src="/OA/www/Public/Admin/js/jquery.mousewheel.js"></script>
  <!--<![endif]-->
  
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/mainLayout.css" media="all">
  <style>
    .modal-open {
      overflow: hidden;
    }

    .sys_dialog {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      z-index: 1050;
      display: none;
      overflow: hidden;
      -webkit-overflow-scrolling: touch;
      outline: 0;
    }

    .sys_dialog.in {
      display: block;
    }

    .dialog_backdrop {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background-color: #000;
      opacity: .5;
      z-index: 100;
    }

    .dialog_wrap {
      width: 500px;
      background: white;
      border-radius: 5px;
      overflow: hidden;
      position: absolute;
      top: 50%;
      left: 50%;
      z-index: 200;
      transition: all ease 0.5s;
      transform: translate(-50%, -100%);
      opacity: 0;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.12);
    }

    .dialog_wrap.slipUp {
      transform: translate(-50%, -50%);
      opacity: 1;
    }

    .dialog-header {
      height: 50px;
      line-height: 50px;
      text-align: center;
      border-bottom: 1px solid #ccc;
      font-size: 18px;
      background: #353535;
      color: white;
    }

    .dialog-content {
      padding: 20px;
      position: relative;
    }

    .textarea {
      width: 100%;
      height: 150px;
      border: 1px solid #ccc;
      color: gray;
      padding: 5px;
      border-radius: 4px;
    }

    .tips {
      font-size: 12px;
      text-align: right;
      position: absolute;
      bottom: 25px;
      right: 15px;
      color: #ccc;
    }

    .dialog-footer {
      text-align: center;
      padding: 20px 0;
    }

    .dialog-footer button {
      min-width: 80px;
      height: 35px;
      border-radius: 4px;
      padding: 0 5px;
      box-sizing: border-box;
      border: 1px solid #dcdfe6;
      background: #fff;
      color: #606266;
      cursor: pointer;
    }

    .dialog-footer .confirm {
      margin-left: 15px;
      background: #0b6cbc;
      border: 1px solid #0b6cbc;
      color: white;
    }

  </style>

  
</head>
<body>
<!-- 头部 -->
<?php $__base_menu__ = $__controller__->getMenus(); ?>
<div class="header">
  <!-- Logo -->
  <span class="logo"></span>
  <!-- /Logo -->

  <!-- 主导航 -->
  <ul class="main-nav">
    <?php if(is_array($__base_menu__["main"])): $i = 0; $__LIST__ = $__base_menu__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
  </ul>
  <!-- /主导航 -->

  <!-- 用户栏 -->
  <div class="user-bar">
    <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
    <ul class="nav-list user-menu hidden">
      <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
      <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
      <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
      <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
    </ul>
  </div>
</div>
<!-- /头部 -->

<!-- 边栏 -->
<div class="sidebar">
  <!-- 子导航 -->
  
    <div id="subnav" class="subnav">
      <?php if(!empty($_extra_menu)): ?>
        <?php echo extra_menu($_extra_menu,$__base_menu__); endif; ?>
      <?php if(is_array($__base_menu__["child"])): $i = 0; $__LIST__ = $__base_menu__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
        <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
          <ul class="side-sub-menu">
            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                <a class="item" href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
              </li><?php endforeach; endif; else: echo "" ;endif; ?>
          </ul><?php endif; ?>
        <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
  
  <!-- /子导航 -->
</div>
<!-- /边栏 -->

<!-- 内容区 -->
<div id="main-content">
  <div id="top-alert" class="fixed alert alert-error" style="display: none;">
    <button class="close fixed" style="margin-top: 4px;">&times;</button>
    <div class="alert-content">这是内容</div>
  </div>
  <div id="main" class="main">
    
  <div class="breadcrumb">
    <span>您的位置:</span>
    <div>
      <a href="<?php echo u('contract/intention');?>">合同管理</a>
      <span class="divider">/</span>
    </div>
    <div>项目维护</div>
  </div>


    
  <div class="search_box">
    <?php if($gid == 7): ?><div class="items">
        <spn>商务负责人：</spn>
        <input type="text" name="charge_person" class="search-input" value="<?php echo I('charge_person');?>">
      </div><?php endif; ?>
    <?php if($gid == ''): ?><div class="items">
        <spn>商务负责人：</spn>
        <input type="text" name="charge_person" class="search-input" value="<?php echo I('charge_person');?>">
      </div><?php endif; ?>

    <div class="items">
      <span>客户名称：</span>
      <input type="text" name="customer" class="search-input" value="<?php echo I('customer');?>">
    </div>
    <div class="items">
      <spn>项目|商业名称：</spn>
      <input type="text" name="project_name" class="search-input" value="<?php echo I('project_name');?>">
    </div>
    <div class="items">
      <span>地区：</span>
      <input type="text" name="province" class="search-input" value="<?php echo I('province');?>">
    </div>
    <div class="items">
      <span>状态：</span>
      <div class="drop-down status">
        <!-- <span><?php echo ($status); ?></span> -->
        <span class="sort-txt" data="<?php echo ($status); ?>">
          <?php if(get_status_title3($status) == ''): ?>所有
          <?php else: ?>
            <?php echo get_status_title3($status); endif; ?>
        </span>
        <i class="arrow arrow-down"></i>
        <ul class="nav-list hidden">
          <li><a href="javascript:;" value="">所有</a></li>
          <li><a href="javascript:;" value="0">新增</a></li>
          <li><a href="javascript:;" value="1">进行中</a></li>
          <li><a href="javascript:;" value="2">已签约</a></li>
          <li><a href="javascript:;" value="3">已终止</a></li>
        </ul>
      </div>
    </div>

    <div class="items">
      <span>排序方式：</span>
      <div class="drop-down sort">
        <!-- <span><?php echo ($sort); ?></span> -->
        <span class="sort-txt" data="<?php echo ($sort); ?>" style="width: 60px;">
          <?php if(get_status_title2($sort) == ''): ?>所有
            <?php else: ?>
            <?php echo get_status_title2($sort); endif; ?>
        </span>
        <i class="arrow arrow-down"></i>
        <ul class="nav-list hidden">
          <li><a href="javascript:;" value="0">创建时间</a></li>
          <li><a href="javascript:;" value="1">更新时间</a></li>
          <li><a href="javascript:;" value="2">开始时间</a></li>
          <li><a href="javascript:;" value="3">开业时间</a></li>
        </ul>
      </div>
    </div>

    <div class="items">
      <a class="sch-btn" href="javascript:void (0);" id="search" url="<?php echo U('index');?>"><i class="btn-search"></i></a>
    </div>
    <div class="items action_btn_box">
      <button class="btn" id="action_add" url="<?php echo U('project/add');?>">新 增</button>
      <?php if($gid == 7 or $gid == ''): ?><button class="btn ajax-post confirm" target-form="ids" url="<?php echo U('delete',array('status'=>-3));?>">确 认</button>
		    <button class="btn terminate" data-target="ids">终 止</button>
        <!--      <button class="btn ajax-post confirm" target-form="ids" url="<?php echo U('delete',array('status'=>-1));?>">终 止</button>-->
        <button class="btn ajax-post confirm" target-form="ids" url="<?php echo U('delete',array('status'=>-2));?>">恢 复</button><?php endif; ?>
    </div>
  </div>
  <!-- 数据列表 -->
  <div class="data-table">
    <table class="">
      <thead>
      <tr>
        <th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
        <th>项目编号</th>
        <th>项目|商业名称</th>
        <th>项目类型</th>
        <th>状态</th>
        <th>客户名称</th>
        <!-- <th>地区</th> -->
        <th>意向产品</th>
        <th>项目预估(元)</th>
        <th>开始日期</th>
        <th>开业日期</th>
        <th>销售负责人</th>
        <th>创建人</th>
        <th>创建时间</th>
        <th>更新人</th>
        <th>更新时间</th>
        <th>操作</th>
      </tr>
      </thead>
      <tbody>
      <?php if($project != ''): if(is_array($project)): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="<?php echo ($vo["id"]); ?>"/></td>
            <td class=""><?php echo ($vo["project_code"]); ?></td>
            <td class="" style="max-width: 110px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;" title="<?php echo ($vo["project_name"]); ?>"><?php echo ($vo["project_name"]); ?></td>
            <td class=""><?php echo ($vo["project_type"]); ?></td>

            <td>
              <?php if($vo["status"] == 0): ?>新增
                <?php elseif($vo["status"] == 1): ?>
                进行中
                <?php elseif($vo["status"] == 2): ?>
                已签约
                <?php else: ?>
                已终止<?php endif; ?>
            </td>


            <td class="" style="max-width: 110px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;" title="<?php echo ($vo["cus"]); ?>"><?php echo ($vo["cus"]); ?></td>
            <!-- <td class=""><?php echo ($vo["province"]); ?></td> -->
            <td class="" style="max-width: 110px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;" title="<?php echo ($vo["purchase_intention"]); ?>"><?php echo ($vo["purchase_intention"]); ?></td>
            <td class=""><?php echo ($vo["budget"]); ?></td>
            <td class=""><?php echo ($vo["begin_time"]); ?></td>
            <td class=""><?php echo ($vo["open_time"]); ?></td>
            <td class=""><?php echo ($vo["charge_person"]); ?></td>
            <td class=""><?php echo ($vo["cre"]); ?></td>
            <td class=""><?php echo ($vo["create_time"]); ?></td>
            <td class=""><?php echo ($vo["upone"]); ?></td>
            <td class=""><?php echo ($vo["update_time"]); ?></td>
            <td>
              <a href="<?php echo U('Project/tail_list?id='.$vo['id']);?>">项目跟进</a>
            </td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <?php else: ?>
        <tr>
          <td colspan="16">
            <div class="no_data">没有数据</div>
          </td>
        </tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <!-- 分页 -->
  <div class="page"><?php echo ($_page); ?></div>
  <!-- /分页 -->
  <div class="sys_dialog">
    <div class="dialog_backdrop"></div>
    <div class="dialog_wrap">
      <div class="dialog-header">终止原因</div>
      <div class="dialog-content">
        <textarea class="textarea" maxlength="200" placeholder="请填写终止原因"></textarea>
        <p class="tips">请填写200字以内</p>
      </div>
      <div class="dialog-footer">
        <button class="cancel">取消</button>
        <button class="confirm">确定</button>
      </div>
    </div>
  </div>

  </div>
  <div class="cont-ft">
    <div class="copyright">
      <div class="fl">感谢使用<a href="http://www.gdwstech.com" target="_blank">GDWS</a>oa管理平台</div>
      <div class="fr">V3.0.1</div>
    </div>
  </div>
</div>
<!-- /内容区 -->
<script type="text/javascript">
  (function () {
    var ThinkPHP = window.Think = {
      "ROOT": "/OA/www", //当前网站地址
      "APP": "/OA/www", //当前项目地址
      "PUBLIC": "/OA/www/Public", //项目公共目录地址
      "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
      "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
      "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
    }
  })();
</script>
<script type="text/javascript" src="/OA/www/Public/static/think.js"></script>
<script type="text/javascript" src="/OA/www/Public/Admin/js/common.js"></script>
<script type="text/javascript">
  +function () {
    var $window = $(window), $subnav = $("#subnav"), url;
    $window.resize(function () {
      $("#main").css("min-height", $window.height() - 130);
    }).resize();

    /* 左边菜单高亮 */
    url = window.location.pathname + window.location.search;
    url = url.replace(".html", "")
      .replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)/, "");
    $subnav.find("a[href^='" + url + "']").parent().addClass("current");

    /* 左边菜单显示收起 */
    $("#subnav").on("click", "h3", function () {
      var $this = $(this);
      $this.find(".icon").toggleClass("icon-fold");
      $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").prev("h3").find("i").addClass("icon-fold").end().end().hide();
    });

    $("#subnav h3 a").click(function (e) {
      e.stopPropagation()
    });

    /* 头部管理员菜单 */
    $(".user-bar").mouseenter(function () {
      var userMenu = $(this).children(".user-menu ");
      userMenu.removeClass("hidden");
      clearTimeout(userMenu.data("timeout"));
    }).mouseleave(function () {
      var userMenu = $(this).children(".user-menu");
      userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
      userMenu.data("timeout", setTimeout(function () {
        userMenu.addClass("hidden")
      }, 100));
    });

    /* 表单获取焦点变色 */
    $("form").on("focus", "input", function () {
      $(this).addClass('focus');
    }).on("blur", "input", function () {
      $(this).removeClass('focus');
    });
    $("form").on("focus", "textarea", function () {
      $(this).closest('label').addClass('focus');
    }).on("blur", "textarea", function () {
      $(this).closest('label').removeClass('focus');
    });

    // 导航栏超出窗口高度后的模拟滚动条
    var sHeight = $(".sidebar").height();
    var subHeight = $(".subnav").height();
    var diff = subHeight - sHeight; //250
    var sub = $(".subnav");
    if (diff > 0) {
      $(window).mousewheel(function (event, delta) {
        if (delta > 0) {
          if (parseInt(sub.css('marginTop')) > -10) {
            sub.css('marginTop', '0px');
          } else {
            sub.css('marginTop', '+=' + 10);
          }
        } else {
          if (parseInt(sub.css('marginTop')) < '-' + (diff - 10)) {
            sub.css('marginTop', '-' + (diff - 10));
          } else {
            sub.css('marginTop', '-=' + 10);
          }
        }
      });
    }
  }();
</script>

  <script src="/OA/www/Public/static/message/message.js"></script>
  <script type="text/javascript">

    $(function() {
    
      var parants = $('.sys_dialog');

      $('.terminate').click(function(e) {
        e.stopPropagation();

        var name = $(this).data('target');
        var form = $('.' + name).serializeArray();
        if (form.length != 0) {
          if (form.length > 1) {
            $.message({
              message: '一次只能对一个项目进行终止操作',
              type: 'warning',
            });
          } else {
            $('body').addClass('modal-open');
            parants.addClass('in');
            setTimeout(function() {
              $('.dialog_wrap').toggleClass('slipUp');
            }, 100);
          }
        } else {
          updateAlert('请选择需要终止的项目');
          setTimeout(function() {
            $('#top-alert').find('button').click();
          }, 1500);
        }
      });

      // 弹框取消
      $('.cancel', parants).click(function() {
        closeModal();
      });

      // 弹框确定
      $('.confirm', parants).click(function() {
        let val = $('.textarea', parants).val();
        if (val) {
          var name = $('.terminate').data('target');
          var value = $('.' + name).serializeArray()[0].value;
          console.log(12313);
          $.ajax({
            type: 'post',
            url: "<?php echo U('Project/delete');?>",
            data: {
              id: value,
              textarea: val,
              state: '-1',
            },
            success: function(res) {
              console.log(res);
              if (res.success ==1) {
                $.message({
                  message: res.message,
                  type: 'success',
                });
                closeModal();
                setTimeout(function () {
                  location.reload();  //实现页面重新加载
                },400)
                // sleep(3000);
              } else {
                $.message({
                  message: res.message,
                  type: 'error',
                });
              }
            },
          });
        } else {
          $.message({
            message: '请填写终止原因',
            type: 'warning',
          });
        }
      });

      // 点击其他地方关闭
      $(document).click(function(event) {
        var _con = $('.dialog_wrap');  // 设置目标区域
        if (!_con.is(event.target) && _con.has(event.target).length === 0 && parants.hasClass('in')) {
          console.log(event.target);
          closeModal();
        }
      });

      // esc按钮关闭
      $(document).keyup(function(event) {
        if (event.keyCode === 27 && parants.hasClass('in')) {
          closeModal();
        }
      });

      // 关闭弹框
      function closeModal() {
        $('.dialog_wrap').toggleClass('slipUp');
        setTimeout(function() {
          $('.sys_dialog').removeClass('in');
          $('body').removeClass('modal-open');
          $('.textarea', parants).val('');
        }, 100);
      }

      function sleep(numberMillis) {
        var now = new Date();
        var exitTime = now.getTime() + numberMillis;
        while (true) {
          now = new Date();
          if (now.getTime() > exitTime)
            return;
        }
      }


      $('#action_add').click(function() {
        window.location.href = $(this).attr('url');
      });

      //搜索功能
      $('#search').click(function() {
        var url = $(this).attr('url'),
          _status = $('.status .sort-txt').attr('data'),
          _sort = $('.sort .sort-txt').attr('data'),
          query = $('.search_box').find('input').serialize();

        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
        query = query.replace(/^&/g, '');
        if (query) {
          if (url.indexOf('?') > 0) {
            url += '&' + query;
          } else {
            url += '?' + query;
          }
        }
        if (_status != '') {
          if (url.indexOf('?') > 0) {
            url += '&status=' + _status;
          } else {
            url += '?status=' + _status;
          }
        }

        if (_sort != '') {
          if (url.indexOf('?') > 0) {
            url += '&sort=' + _sort;
          } else {
            url += '?sort=' + _sort;
          }
        }
        window.location.href = url;
      });

      /* 状态搜索子菜单 */
      $('.search_box').find('.drop-down').hover(function() {
        $(this).find('.nav-list').removeClass('hidden');
      }, function() {
        $(this).find('.nav-list').addClass('hidden');
      });

      $('.nav-list li').find('a').each(function() {
        $(this).click(function() {
          var text = $(this).text();
          let parents = $(this).parents('.drop-down');
          $('.sort-txt', parents).text(text).attr('data', $(this).attr('value'));
          $('.nav-list', parents).addClass('hidden');
        });
      });

      //回车搜索
      $('.search-input').keyup(function(e) {
        if (e.keyCode === 13) {
          $('#search').click();
          return false;
        }
      });
    });
  </script>

</body>
</html>
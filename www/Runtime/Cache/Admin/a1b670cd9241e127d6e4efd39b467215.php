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
  
  <link rel="stylesheet" href="/OA/www/Public/static/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/OA/www/Public/static/bootstrap/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="/OA/www/Public/static/datetimepicker/css/datetimepicker.css">
  <link rel="stylesheet" href="/OA/www/Public/static/datetimepicker/css/datetimepicker_blue.css">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/mainLayout.css" media="all">
  <style>
    .newly {
      width: 100%;
      display: flex;
      justify-content: flex-start;
      padding-top: 15px;
    }

    .sub-items {
      width: 490px;
    }

    .sub-items.left {
      padding-right: 100px;
      border-right: 1px solid #ccc;
    }

    .sub-items.right {
      padding-left: 100px;
    }

    .sub-title {
      padding-bottom: 15px;
      margin-top: 10px;
      font-size: 18px;
      color: #6e6b73;
      border-bottom: 1px solid #ccc;
      clear: both;
    }

    .form-vertical .edit_info input[type="text"] {
      width: 260px;
    }

    .form-vertical .edit_label {
      width: 90px;
    }

    .edit_info textarea {
      width: 260px;
    }

    .form-vertical .edit_info .form_datetime > .date {
      width: 109px;
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
      <a href="<?php echo u('custom/index');?>">合同管理</a>
      <span class="divider">/</span>
    </div>
    <div>
      <a href="<?php echo u('index');?>">项目维护</a>
      <span class="divider">/</span>
    </div>
    <div>
      <a href="<?php echo u('tail_list');?>">项目跟进</a>
      <span class="divider">/</span>
    </div>
    <div><?php echo ($info['id']?'编辑':'新增'); ?>项目跟踪记录</div>
  </div>


    
  <form action="/oa/www/admin/project/tail_add/id/787.html" method="post" class="form-vertical">
    <div class="newly">
      <div class="sub-items left">
        <div class="sub-title">项目基础信息</div>
        <div class="edit_items">
          <label class="edit_label">项目|商业名称：</label>
          <div class="preview">
            <input type="hidden" name="project_id" value="<?php echo ($project["project_id"]); ?>">
            <p><?php echo ($project["project_name"]); ?></p>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">销售负责人：</label>
          <div class="preview">
            <input type="hidden" name="charge_person" value="<?php echo ($project["charge_person"]); ?>">
            <p><?php echo ($project["charge_person"]); ?></p>
          </div>
        </div>
        
        <div class="edit_items">
          <label class="edit_label">本次意向产品</label>
          <div class="edit_info">
            <select name="intent_product[]" class="selectpicker" data-width="150" title="请选择" multiple data-size="6" data-live-search="true" data-live-search-placeholder="Search">
              <?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["product_name"]); ?>" <?php if(in_array( $vo['product_name'],explode(",",$project['intent_product']))){ echo 'selected';}?>><?php echo ($vo["product_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">本次预估(元)：</label>
          <div class="edit_info">
            <input type="text" name="budget" value="<?php echo ($project["budget"]); ?>">
            <!-- <p><?php echo ($project["budget"]); ?></p> -->
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">情况说明：</label>
          <div class="edit_info">
            <textarea name="discribe"><?php echo ($project["discribe"]); ?></textarea>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">商务费用：</label>
          <div class="edit_info">
            <input type="text" name="fee" value="<?php echo ($project["fee"]); ?>">
          </div>
        </div>
      </div>
      <div class="sub-items right">
        <div class="sub-title">项目跟进详情</div>

        <div class="edit_items">
          <label class="edit_label">进展阶段：</label>
          <div class="edit_info">
            <select name="progress" class="selectpicker" data-width="150" title="请选择" multiple data-size="6">
              <option value="陌生拜访" <?php if($project['progress'] =='陌生拜访'){echo 'selected';}?>>陌生拜访</option>
              <option value="方案提升" <?php if($project['progress'] =='方案提升'){echo 'selected';}?>>方案提升</option>
              <option value="项目投标" <?php if($project['progress'] =='项目投标'){echo 'selected';}?>>项目投标</option>
              <option value="商务谈判" <?php if($project['progress'] =='商务谈判'){echo 'selected';}?>>商务谈判</option>
              <option value="合同签订" <?php if($project['progress'] =='合同签订'){echo 'selected';}?>>合同签订</option>
            </select>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">开始日期：</label>
          <div class="edit_info">
            <div class="date form_datetime">
              <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
              <input type="text" class="date" name="start" value='<?php echo ($project["start"]); ?>' placeholder="选择日期" readonly>
            </div>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">结束日期：</label>
          <div class="edit_info">
            <div class="date form_datetime">
              <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
              <input type="text" class="date" name="end" value='<?php echo ($project["end"]); ?>' placeholder="选择日期" readonly>
            </div>
          </div>
        </div>

		    <div class="edit_items">
            <label class="edit_label">拜访目标：</label>
            <div class="edit_info">
                <input type="text" name="visit_target" value="<?php echo ($project["visit_target"]); ?>">
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">拜访人员：</label>
            <div class="edit_info">
                <input type="text" name="visitor" value="<?php echo ($project["visitor"]); ?>">
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">职位：</label>
            <div class="edit_info">
                <input type="text" name="position" value="<?php echo ($project["position"]); ?>">
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">联系方式：</label>
            <div class="edit_info">
                <input type="text" name="contact_way" value="<?php echo ($project["contact_way"]); ?>">
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">拜访方式：</label>
            <div class="edit_info">
                <input type="text" name="visit_way" value="<?php echo ($project["visit_way"]); ?>">
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">沟通内容：</label>
            <div class="edit_info">
                <textarea name="communication_content"><?php echo ($project["communication_content"]); ?></textarea>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">是否达成：</label>
            <div class="edit_info">
                <input type="radio"  name="Is_reach" value="0" <?php if($project['Is_reach'] == '0'){echo 'checked';} ?>>否
            </div>
            <div class="edit_info">
                <input type="radio" name="Is_reach" value="1" <?php if($project['Is_reach'] == '1'){echo 'checked';} ?>>是
            </div>
        </div>

        <div class="edit_btn">
            <input type="hidden" name="tid" value="<?php echo ((isset($project["id"]) && ($project["id"] !== ""))?($project["id"]):''); ?>">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-vertical">确 定</button>
          <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
      </div>
    </div>
  </form>

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

  <script src="/OA/www/Public/static/bootstrap/js/bootstrap.min.js"></script>
  <script src="/OA/www/Public/static/bootstrap/js/bootstrap-select.min.js"></script>
  <script src="/OA/www/Public/static/datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="/OA/www/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
  <script type="text/javascript">
    // Think.setValue("type", <?php echo ((isset($info["type"]) && ($info["type"] !== ""))?($info["type"]):0); ?>);
    // Think.setValue("group", <?php echo ((isset($info["group"]) && ($info["group"] !== ""))?($info["group"]):0); ?>);
    //导航高亮
    $('.side-sub-menu').find('a[href="<?php echo U('User / index;');?>"]').closest('li').addClass('current');
    // 日期插件
    $('.form_datetime').datetimepicker({
      format: 'yyyy-mm-dd',
      language: 'zh-CN',
      minView: 2,
      autoclose: true,
      todayBtn: true,
    });
  </script>

</body>
</html>
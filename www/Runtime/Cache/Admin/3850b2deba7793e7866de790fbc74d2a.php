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
    <style type="text/css">
    .edit_label {
        width: 75px;
    }
    .action_btn {
        margin-top: 50px;
    }

    .preview_textarea {
        width: 455px!important;
    }

    .preview_textarea p {
        height: auto!important;
        min-height: 34px;
        font-size: 12px;
        line-height: 20px;
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
    <div>
      <a href="<?php echo u('contract/contract_transfer');?>">项目交接</a>
      <span class="divider">/</span>
    </div>
    <div>项目交接记录详情</div>
  </div>


    
<div class="newly preview_h_box ">
    <div class="edit_title">项目信息</div>
    <div class="edit_main">
        <div class="edit_items">
            <label class="edit_label">项目名称</label>
            <div class="preview">
              <p><?php echo ($transfer["project_name"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">客户名称</label>
            <div class="preview preview_more">
              <p><?php echo ($transfer["customer"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">合同编号</label>
            <div class="preview">
              <p><?php echo ($transfer["contract_code"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">项目类型</label>
            <div class="preview">
                <p><?php echo ($transfer["project_type"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">项目地区</label>
            <div class="preview">
                <p><?php echo ($transfer["province"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">采购产品</label>
            <div class="preview preview_more">
                <p><?php echo ($transfer["project_productlist"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">定制开发</label>
            <div class="preview preview_more">
                <p><?php echo ($transfer["project_custom"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">关键客户人</label>
            <div class="preview">
                <p><?php echo ($transfer["keyCustom"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">联系电话</label>
            <div class="preview">
                <p><?php echo ($transfer["link_phone"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">项目风向评估</label>
            <div class="preview">
                <?php if($transfer["ProjectRishLevel"] == 0): ?><p>低</p>
                <?php elseif($transfer["ProjectRishLevel"] == 1): ?>
                    <p>中</p>
                <?php elseif($transfer["ProjectRishLevel"] == 2): ?>
                    <p>高</p><?php endif; ?>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">项目风险</label>
            <div class="preview preview_textarea">
                <p><?php echo ($transfer["ProjectRishDescribe"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">客户期望</label>
            <div class="preview">
                <?php if($transfer["CustomerExpectLevel"] == 0): ?><p>低</p>
                <?php elseif($transfer["CustomerExpectLevel"] == 1): ?>
                    <p>中</p>
                <?php elseif($transfer["CustomerExpectLevel"] == 2): ?>
                    <p>高</p><?php endif; ?>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">竞争对手情况</label>
            <div class="preview ">
                <p><?php echo ($transfer["Project_opponents"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">客户IT现状</label>
            <div class="preview preview_textarea">
                <p><?php echo ($transfer["CustomerITNow"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">是否系统集成</label>
            <div class="preview">
                <?php if($transfer["IsSI"] == 0): ?><p>否</p>
                <?php elseif($transfer["IsSI"] == 1): ?>
                    <p>是</p><?php endif; ?>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">进场日期</label>
            <div class="preview">
                <p><?php echo ($transfer["EnterTime"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">开业日期</label>
            <div class="preview">
                <p><?php echo ($transfer["OpenTime"]); ?></p>
            </div>
        </div>
    </div>
    <div class="edit_title">项目组成员信息</div>
    <div class="edit_main">
        <div class="edit_items">
            <label class="edit_label">销售经理</label>
            <div class="preview">
                <p><?php echo ($transfer["salesRole"]); ?></p>
            </div>
        </div>
        <!-- <div class="edit_items">
            <label class="edit_label">项目经理</label>
            <div class="preview">
                <p><?php echo ($transfer["ProjectRole"]); ?></p>
            </div>
        </div> -->
        <div class="edit_items">
            <label class="edit_label">产品经理</label>
            <div class="preview">
                <p><?php echo ($transfer["ProductRole"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">实施经理</label>
            <div class="preview">
                <p><?php echo ($transfer["TechRole"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">开发经理</label>
            <div class="preview">
                <p><?php echo ($transfer["DevRole"]); ?></p>
            </div>
        </div>
    </div>
    <div class="edit_title">其他信息</div>
    <div class="edit_main">
        <div class="edit_items">
            <label class="edit_label">备注</label>
            <div class="preview preview_textarea">
                <p><?php echo ($transfer["remark"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">附件</label>
            <div class="preview">
                <!-- <p><?php echo ($transfer["accessory"]); ?></p> -->
                <a target="view_window" href='<?php echo ($transfer["accessory"]); ?>' class="updown">附件下载</a>
            </div>
        </div>
    </div>
    <div class="edit_btn action_btn">
        <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
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

</body>
</html>
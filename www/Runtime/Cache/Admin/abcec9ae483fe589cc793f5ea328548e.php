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
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/mainLayout.css">

  
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
      <a href="<?php echo u('tech/index');?>">实施管理</a>
      <span class="divider">/</span>
    </div>
    <div>
      <a href="<?php echo u('tech/pj_complete');?>">实施验收维护</a>
      <span class="divider">/</span>
    </div>
    <div><?php echo ($info['id']?'编辑':'新增'); ?>实施项目交付记录</div>
  </div>


    
  <div class="newly">
    <form action="/oa/www/admin/tech/add_project_complete.html" method="post" enctype="multipart/form-data" class="form-vertical">
      <div class="edit_items">
        <label class="edit_label">交付编号</label>
        <div class="edit_info">
          <input readonly placeholder="系统自动生成" type="text" value='<?php echo ($project["project_code"]); ?>'/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">项目名称</label>
        <div class="edit_info">
          <select id="project_id" name="project_id" class="selectpicker" data-width="172" title="请选择" data-size="6" data-live-search="true" data-live-search-placeholder="Search">
            <?php if(is_array($projects)): $i = 0; $__LIST__ = $projects;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["project_id"]); ?>"><?php echo ($vo["project_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
        </div>
      </div>

      <div class="edit_items">
        <label class="edit_label">计划编号</label>
        <div class="edit_info">
          <select id="plan_code" name="plan_code" class="selectpicker" data-width="172" title="请选择" data-size="6">
            <option disabled>没有数据</option>
          </select>
        </div>
      </div>

      <div class="edit_items">
        <label class="edit_label">计划阶段</label>
        <div class="edit_info">
          <select id="phases" name="phases" class="selectpicker" data-width="172" title="请选择" data-size="6">
          </select>
        </div>
      </div>

      <div class="edit_items">
        <label class="edit_label">是否延期交付</label>
        <div class="edit_info">
          <select id="IsDelay" name="IsDelay" class="selectpicker" data-width="172" title="请选择" data-size="6">
            <option value="0">否</option>
            <option value="1">是</option>
          </select>
        </div>
      </div>
      <div class="edit_items delayWhy" style="display: none">
        <label class="edit_label">延期交付原因</label>
        <div class="edit_info">
          <textarea name="DelayWhy"></textarea>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">验收单</label>
        <div class="edit_info">
          <!-- <div class="upload"> -->
            <!-- <span class="tips">点击上传图片</span> -->
            <!-- <input type="file" name="file" class="uploadFile"> -->
            <input type="file" name="file" >
          <!-- </div> -->
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">实际交付时间</label>
        <div class="edit_info">
          <div class="date form_datetime">
            <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
            <input type="text" class="date" name="ac_rtime" placeholder="选择日期" readonly>
          </div>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">实施人员</label>
        <div class="edit_info">
          <select id="executor" name="executor" class="selectpicker" data-width="172" title="请选择" data-size="6">
            <?php if(is_array($techs)): $i = 0; $__LIST__ = $techs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["uid"]); ?>"><?php echo ($vo["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">备注</label>
        <div class="edit_info">
          <textarea name="remark"></textarea>
        </div>
      </div>

      <div class="edit_btn">
        <input type="hidden" name="id" value="<?php echo ((isset($info["id"]) && ($info["id"] !== ""))?($info["id"]):''); ?>">
        <button class="btn submit-btn"  type="submit">确 定</button>
        <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
      </div>
    </form>
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

  <script src="/OA/www/Public/static/bootstrap/js/bootstrap.min.js"></script>
  <script src="/OA/www/Public/static/bootstrap/js/bootstrap-select.min.js"></script>
  <script src="/OA/www/Public/static/datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="/OA/www/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>

  <script type="text/javascript">
    $(function () {
      //导航高亮
      $('.side-sub-menu').find('a[href="<?php echo U('tech/pj_complete');?>"]').closest('li').addClass('current');

      // 日期插件
      $('.form_datetime').datetimepicker({
        format: 'yyyy-mm-dd',
        language: "zh-CN",
        minView: 2,
        autoclose: true,
        todayBtn: true
      });

      $("#project_id").change(function () {
        var project_id = $(this).val();

        // Ajax提交数据
        $.ajax({
          url: "<?php echo U('Tech/getPlanCode');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"project_id": project_id},  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            console.log(data);
            $("#plan_code").empty();
            let str = ``;
            data.map(item => {
              str += `<option value='${item.plan_code}'>${item.plan_code}</option>`;
            });
            $("#plan_code").append(str).selectpicker('refresh');
          },
        });
      });

      $("#plan_code").change(function () {
        var project_id = $(this).val();

        // Ajax提交数据
        $.ajax({
          url: "<?php echo U('Tech/get_phases');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"plan_code": project_id},  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            $("#phases").empty();
            let str = ``;
            data.map(item => {
              str += `<option value='${item.phases}'>第${item.phases}阶段</option>`;
            });
            $("#phases").append(str).selectpicker('refresh');
          },
        });
      });

      $("#IsDelay").change(function () {
        var IsDelay = $(this).val();
        console.log(IsDelay);
        if (IsDelay == 0) {
          $(".delayWhy").hide(600);
        } else {
          $(".delayWhy").show(600);
        }
      });

      $(".upload").on('change', '.uploadFile', function () {
        var objUrl = getObjectURL(this.files[0]);//获取文件信息
        if (objUrl) {
          $(this).parent().find('img').remove();
          $(this).parent().find('.tips').hide();
          $(this).parent().append("<img src=\"" + objUrl + "\" alt='预览'/>");
        }
      });

      function getObjectURL(file) {
        var url = null;
        if (window.createObjectURL != undefined) {
          url = window.createObjectURL(file);
        } else if (window.URL != undefined) { // mozilla(firefox)
          url = window.URL.createObjectURL(file);
        } else if (window.webkitURL != undefined) { // webkit or chrome
          url = window.webkitURL.createObjectURL(file);
        }
        return url;
      }
    });
  </script>

</body>
</html>
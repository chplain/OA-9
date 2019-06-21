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
      <a href="<?php echo u('tech/index');?>">实施管理</a>
      <span class="divider">/</span>
    </div>
    <div>
      <a href="<?php echo u('tech/execut_log');?>">实施工作记录</a>
      <span class="divider">/</span>
    </div>
    <div><?php echo ($info['id']?'编辑':'新增'); ?>实施工作记录</div>
  </div>


    
  <form action="/oa/www/admin/tech/work_log_add/id/3.html" method="post" class="form-vertical">
    <div class="newly">
      <div class="sub-items left">
        <div class="sub-title">计划查询</div>
        <div class="edit_items">
          <label class="edit_label">项目|商业名称</label>
          <div class="edit_info">
            <select id="project_id" name="project_id" class="selectpicker" data-width="172" title="请选择" data-size="6" data-live-search="true" data-live-search-placeholder="Search">
              <?php if(is_array($project)): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><option <?php if($work_log['project_name'] == $vo1['project_name']){echo 'selected';} ?> value="<?php echo ($vo1["project_id"]); ?>"><?php echo ($vo1["project_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">工作计划</label>
          <div class="edit_info">
            <select id="workplan" name="workplan" class="selectpicker" data-width="172" title="请选择" data-size="6">
              <?php if($mid != ''): ?><option disabled selected><?php echo ($work_log["plan_code"]); ?>第<?php echo ($work_log["phases"]); ?>阶段</option>
              <?php else: ?>
                <option disabled>没有数据</option><?php endif; ?>
            </select>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">计划</label>
          <div class="edit_info">
            <select id="workplan_phases" name="workplan_phases" class="selectpicker" data-width="172" title="请选择" data-size="6">
              <?php if($mid != ''): ?><option disabled selected>计划<?php echo ($work_log["wphases"]); ?></option>
              <?php else: ?>
                <option disabled>没有数据</option><?php endif; ?>
            </select>
          </div>
        </div>
        
        <div class="edit_items">
            <label class="edit_label">实施产品</label>
            <div class="preview">
              <p id="products"><?php echo ($work_log["products"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">派遣人员</label>
            <div class="preview">
              <p id="executor"><?php echo ($work_log["executor"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">指定日期</label>
          <div class="preview">
            <?php if($mid != ''): ?><p id="time"><?php echo ($work_log["stime"]); ?>至<?php echo ($work_log["etime"]); ?></p>
            <?php else: ?>
              <p id="time"></p><?php endif; ?>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">具体工作安排</label>
          <div class="edit_info">
            <textarea name="content" readonly="" id="remark"><?php echo ($work_log["remark"]); ?></textarea>
          </div>
        </div>
        
        <!-- <div class="edit_btn">
          <input type="hidden" name="id" value="<?php echo ((isset($info["id"]) && ($info["id"] !== ""))?($info["id"]):''); ?>">
          <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-vertical">确 定</button>
          <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div> -->
      </div>
      <div class="sub-items right">
        <div class="sub-title">工作日志编辑</div>
          <div class="edit_items">
            <label class="edit_label">完成情况</label>
            <div class="edit_info">
              <select id="workplan_phases" name="status" class="selectpicker" data-width="172" title="请选择" data-size="6">
                <option <?php if($work_log['status'] == '1'){echo 'selected';} ?> value="1">进行中</option>
                <option <?php if($work_log['status'] == '2'){echo 'selected';} ?> value="2">已完成</option>
              </select>
            </div>
          </div>
          <div class="edit_items">
            <label class="edit_label">工作内容</label>
            <div class="edit_info">
              <textarea name="discribe"><?php echo ($work_log["discribe"]); ?></textarea>
            </div>
          </div>
          <!-- <div class="edit_items">
            <label class="edit_label">报销费用(元)</label>
            <div class="edit_info">
              <input type="text" class="text input-large" name="fee" value="<?php echo ($work_log["fee"]); ?>">
            </div>
          </div> -->
          <div class="edit_items">
            <label class="edit_label">下一步计划</label>
            <div class="edit_info">
              <textarea name="next_plan"><?php echo ($work_log["next_plan"]); ?></textarea>
            </div>
          </div>
          
          <div class="edit_btn">
            <input type="hidden" name="id" value="<?php echo ((isset($info["id"]) && ($info["id"] !== ""))?($info["id"]):''); ?>">
            <?php if($mid != ''): else: ?>
              <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-vertical">确 定</button><?php endif; ?>
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
    $(function () {
      //导航高亮
      $('.side-sub-menu').find('a[href="<?php echo U('tech/work_log');?>"]').closest('li').addClass('current');

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
          url: "<?php echo U('Tech/get_wpByProject');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"project_id": project_id},  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            console.log(data);
            $("#workplan").empty();
            let str = ``;
            data.map(item => {
              str += `<option value='${item.id}'>${item.plan_code}第${item.phases}阶段</option>`;
            });
            $("#workplan").append(str).selectpicker('refresh');
          },
        });
      });

      $("#workplan").change(function () {
        var workplan = $(this).val();
        $("#workplan_phases").empty();
        // Ajax提交数据
        $.ajax({
          url: "<?php echo U('Tech/get_wpPhasesByWid');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"workplan": workplan},  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            console.log(data);
            let str = ``;
            data.map(item => {
              str += `<option value='${item.id}'>计划${item.phases}</option>`;
            });
            $("#workplan_phases").append(str).selectpicker('refresh');
          },
        });
      });

      $("#workplan_phases").change(function () {
        var wid = $("#workplan").val();
        var phases = $(this).val();
        // console.log(wid);
        // return;
        // $("#workplan_phases").empty();
        // Ajax提交数据
        $.ajax({
          url: "<?php echo U('Tech/get_wpPhasesMessByPhases');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"phases": phases,
                  "wid": wid
                },  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            console.log(data);
            $('#executor').html(data.executor);
            $('#time').html(data.stime+'至'+data.etime);
            $('#products').html(data.products);
            $('#remark').val(data.remark);
          },
        });
      });
      
    });
  </script>

</body>
</html>
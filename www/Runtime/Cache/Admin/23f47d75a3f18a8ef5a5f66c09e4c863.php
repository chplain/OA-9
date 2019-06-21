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
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/new.css">

  
  <style>
    

    .updown {
      display: inline-block;
      line-height: 35px;
      color: #646464;
    }

    .updown:hover {
      color: #7d61dd;
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
      <a href="<?php echo u('dev/newdevlist');?>">开发管理</a>
      <span class="divider">/</span>
    </div>
    <div>
      <a href="<?php echo u('dev/customization');?>">定制开发计划</a>
      <span class="divider">/</span>
    </div>
    <div><?php echo ($info['id']?'编辑':'新增'); ?>定制开发计划</div>
  </div>


    
<form action="/oa/www/admin/dev/customizationmess/id/818.html" method="post" class="form-horizontal">
  <div class="newly preview_h_box">
      <div class="edit_title">项目信息</div>
      <div class="edit_items">
        <label class="edit_label">计划编号：</label>
        <div class="preview">
          <p><?php echo ($customization["plan_code"]); ?></p>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">客户名称：</label>
        <div class="preview">
          <p><?php echo ($customization["cum"]); ?></p>
        </div>
      </div>

      <div class="edit_items">
        <label class="edit_label">项目|商业名称：</label>
        <div class="preview">
          <p><?php echo ($customization["project_name"]); ?></p>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">合同编号：</label>
        <div class="preview">
          <p><?php echo ($customization["contract_code"]); ?></p>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">定制产品：</label>
        <div class="preview">
          <p><?php echo ($customization["products"]); ?></p>
        </div>
      </div>
      </br>

      
      <div class="edit_items">
        <label class="edit_label">需求提交日期：</label>
        <div class="preview">
          <p><?php echo ($customization["Need_time"]); ?></p>
        </div>
      </div>

      <div class="edit_items">
        <label class="edit_label">产品确认日期：</label>
        <div class="preview">
          <p><?php echo ($customization["Sure_time"]); ?></p>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">预计完成日期：</label>
        <div class="preview">
          <p><?php echo ($customization["eEnd_time"]); ?></p>
        </div>
      </div>
      <!-- <div class="edit_items">
        <label class="edit_label">实际完成日期：</label>
        <div class="preview">
          <p><?php echo ($customization["End_time"]); ?></p>
        </div>
      </div> -->
      <div class="edit_items">
        <label class="edit_label">实际完成日期：</label>
        <div class="edit_info">
          <div class="date form_datetime">
            <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
            <input type="text" value="<?php echo ($customization["End_time"]); ?>" class="date" name="End_time" placeholder="选择日期"  value="">
          </div>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">状态：</label>
        <div class="edit_info">
          <select name="status" class="selectpicker" data-width="172" title="请选择" data-size="6">
              <?php if($customization["status"] == 0): ?><option selected value="0">新增</option>
              <?php else: ?>
                <option  value="0">新增</option><?php endif; ?>

              <?php if($customization["status"] == 1): ?><option selected value="1">需求分析中</option>
              <?php else: ?>
                <option  value="1">需求分析中</option><?php endif; ?>

              <?php if($customization["status"] == 2): ?><option selected value="2">开发中</option>
              <?php else: ?>
                <option  value="2">开发中</option><?php endif; ?>

              <?php if($customization["status"] == 3): ?><option selected value="3">已交付</option>
              <?php else: ?>
                <option  value="3">已交付</option><?php endif; ?>

              <?php if($customization["status"] == 4): ?><option selected value="4">已暂停</option>
              <?php else: ?>
                <option  value="4">已暂停</option><?php endif; ?>

              <?php if($customization["status"] == 5): ?><option selected value="5">已终止</option>
              <?php else: ?>
                <option  value="5">已终止</option><?php endif; ?>
          </select>
        </div>
      </div>
      </br>
      <div class="edit_items">
        <label class="edit_label">产品人天：</label>
        <div class="preview">
          <p><?php echo ($customization["pr_manday"]); ?></p>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">开发人天：</label>
        <div class="preview">
          <p><?php echo ($customization["dev_manday"]); ?></p>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">测试人天：</label>
        <div class="preview">
          <p><?php echo ($customization["te_manday"]); ?></p>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">产品负责人:</label>
        <div class="preview">
          <p><?php echo ($customization["pr_role"]); ?></p>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">开发负责人:</label>
        <div class="preview">
          <p><?php echo ($customization["dev_role"]); ?></p>
        </div>
      </div>
      
      <div class="edit_title">其他信息</div>
      <div class="edit-box remarks">
        <div class="cell">
          <div class="edit_items">
            <label class="edit_label">备注：</label>
            <div class="edit_info">
              <textarea name="remark" style="width: 400px;height: 200px;"><?php echo ($customization["remark"]); ?></textarea>
            </div>
          </div>
          <!-- <div class="edit_btn">
            <span class="add-btn" data-index="1" data-sign="remarks">+</span>
          </div> -->
        </div>
      </div>
      <div class="edit-box uploadWrap">
        <div class="cell">
          <div class="edit_items">
            <label class="edit_label">附件：</label>
            <div class="edit_info">
              <?php if($customization["accessory"] == ''): ?><input type="file" name="accessory" >
              <?php else: ?>
                <a target="view_window" href='<?php echo ($customization["accessory"]); ?>' class="updown">附件下载</a>
                <input type="file" name="accessory" ><?php endif; ?>
            </div>
          </div>
          <!-- <div class="edit_btn">
            <span class="add-btn" data-index="1" data-sign="upload">+</span>
          </div> -->
        </div>
      </div>
      <div class="edit_btn action_btn">
        <input type="hidden" name="id" value="<?php echo ((isset($customization["id"]) && ($customization["id"] !== ""))?($customization["id"]):''); ?>">
        <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
        <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
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
  <script type="text/javascript" src="/OA/www/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
  <script type="text/javascript" src="/OA/www/Public/Admin/js/artTemplate.js"></script>
  <script id="list1" type="text/html">
    {{if sign == 'remarks'}}
    <div class="cell">
      <div class="edit_items">
        <label class="edit_label">备注{{index+1}}：</label>
        <div class="edit_info">
          <textarea name="data1[{{index}}][remark]"></textarea>
        </div>
      </div>
      <div class="edit_btn">
        {{if index < 3}}
        <span class="add-btn" data-index="{{index+1}}" data-sign="remarks">+</span>
        {{/if}}
        <span class="del-btn" data-index="{{index+1}}" data-sign="remarks">-</span>
      </div>
    </div>
    {{else}}
    <div class="cell">
      <div class="edit_items">
        <label class="edit_label">附件上传：</label>
        <div class="edit_info">
          <div class="upload">
            <span class="tips">点击上传图片</span>
            <input type="file" name="data2[{{index}}]['accessory']" class="uploadFile">
          </div>
        </div>
      </div>
      <div class="edit_btn">
        {{if index < 3}}
        <span class="add-btn" data-index="{{index+1}}" data-sign="upload">+</span>
        {{/if}}
        <span class="del-btn" data-index="{{index+1}}" data-sign="upload">-</span>
      </div>
    </div>
    {{/if}}
  </script>
  <script type="text/javascript">
    $(function () {
      $('.side-sub-menu').find('a[href="<?php echo U('dev/customization');?>"]').closest('li').addClass('current');

      // 日期插件
      $('.form_datetime').datetimepicker({
        format: 'yyyy-mm-dd',
        language: "zh-CN",
        minView: 2,
        autoclose: true,
        todayBtn: true
      });

      $("#customer").change(function () {
        var customer = $(this).val();
        // Ajax提交数据
        // return;
        $.ajax({
          url: "<?php echo U('Dev/getProject');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"customer": customer},  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            $("#project_id").empty();
            console.log(data);
            let str = '';
            data.map(item => {
              str += `<option value='${item.id}'>${item.project_name}</option>`;
            });
            $("#project_id").append(str);
            $('select.selectpicker').selectpicker('refresh');
          },
        });
      });

      function DX(n) {
        if (!/^(0|[1-9]\d*)(\.\d+)?$/.test(n))
          return "数据非法";
        return '一二三四五六七八九'.charAt(n);
      }

      // 绑定添加信息点击事件
      $('.edit-box').on('click', '.add-btn', function () {
        var index = $(this).data('index'), sign = $(this).data('sign');
        $(this).parent().hide();
        var html = template('list1', {sign: sign, index: index, upper: DX(index)});
        if (sign == 'charge') {
          $('.fee').append(html);
          $('select.selectpicker').selectpicker('refresh');
        } else if (sign == 'remarks') {
          $('.remarks').append(html);
        } else {
          $('.uploadWrap').append(html);
        }
      });

      // 添加删除事件
      $('.edit-box').on('click', '.del-btn', function () {
        var index = $(this).data('index'), sign = $(this).data('sign');

        let parant = '';
        if (sign == 'charge') {
          parant = $('.fee').find(".cell").eq(index - 2);
        } else if (sign == 'remarks') {
          parant = $('.remarks').find(".cell").eq(index - 2);
        } else {
          parant = $('.uploadWrap').find(".cell").eq(index - 2);
        }

        parant.find('.edit_btn').show();
        $(this).parents('.cell').remove();
      });

      // 图片上传预览
      $(".edit-box").on('change', '.uploadFile', function () {
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
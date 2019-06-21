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

  
    <style type="text/css">
        .edit_label {
            width: 90px;
        }

        .edit_title2 {
            border:none;
            border-top: 1px solid #ccc;
            padding: 20px 0 15px;
        }

        .action_btn {
            margin-top: 20px;
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
      <a href="<?php echo u('index');?>">项目维护</a>
      <span class="divider">/</span>
    </div>
    <div>项目跟进</div>
  </div>


    
<div class="newly preview_h_box ">
    <form action="/oa/www/admin/project/tail_list/id/937.html" method="post" class="form-horizontal">
        <div class="edit_title">基础信息</div>
        <div class="edit_items">
            <label class="edit_label">项目编号：</label>
            <div class="preview">
              <p><?php echo ($project["project_code"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">项目|商业名称：</label>
            <div class="preview">
              <p><?php echo ($project["project_name"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">项目类型：</label>
            <div class="preview">
                <p><?php echo ($project["project_type"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">状态：</label>
            <div class="preview">
                <?php if($project["status"] == 0): ?><p>新增</p>
                <?php elseif($project["status"] == 1): ?>
                    <p>进行中</p>
                <?php elseif($project["status"] == 2): ?>
                    <p>已签约</p>
                <?php elseif($project["status"] == 3): ?>
                    <p>已终止</p><?php endif; ?>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">客户名称：</label>
            <div class="preview">
                <p style="font-size: 12px;line-height: 16px"><?php echo ($project["customer"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">地区：</label>
            <div class="preview">
                <p><?php echo ($project["province"]); ?></p>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">意向产品：</label>
            <div class="edit_info">
                <select name="purchase_intention[]" class="selectpicker purchase_intention" data-width="170" title="请选择" multiple data-size="10">
                    <?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["product_name"]); ?>"><?php echo ($vo["product_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">项目预估(元)：</label>
            <div class="edit_info">
              <input type="text" name="budget" value='<?php echo ($project["budget"]); ?>'>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">联系人：</label>
            <div class="edit_info">
              <input type="text" name="linkman" value='<?php echo ($project["linkman"]); ?>'>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">职位：</label>
            <div class="edit_info">
              <input type="text" name="position" value='<?php echo ($project["position"]); ?>'>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">联系电话：</label>
            <div class="edit_info">
              <input type="text" name="linkphone" value='<?php echo ($project["linkphone"]); ?>'>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">开始日期：</label>
            <div class="edit_info">
              <div class="date form_datetime">
                  <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
                  <input type="text" class="date" name="begin_time" value='<?php echo ($project["begin_time"]); ?>' placeholder="选择日期" readonly>
              </div>
            </div>
        </div>
        <div class="edit_items">
            <label class="edit_label">开业日期：</label>
            <div class="edit_info">
              <div class="date form_datetime">
                  <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
                  <input type="text" class="date" name="open_time" value='<?php echo ($project["open_time"]); ?>' placeholder="选择日期" readonly>
              </div>
            </div>
        </div><!-- 
        <div class="edit_items">
            <label class="edit_label">销售负责人：</label>
            <div class="edit_info">
              <input type="text" name="charge_person" value='<?php echo ($project["charge_person"]); ?>'>
            </div>
        </div> -->

        <div class="edit_items">
            <label class="edit_label">销售负责人：</label>
            <div class="edit_info">
              <select name="charge_person[]" class="selectpicker" data-width="150" data-size="6" title="请选择" multiple>
                <?php if(is_array($charge_persons)): $i = 0; $__LIST__ = $charge_persons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo1["nickname"]); ?>" <?php if(in_array( $vo1['nickname'],explode(",",$project['charge_person']))){ echo 'selected';}?>><?php echo ($vo1["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
        </div>
        <?php if($project["old_charge_person"] != ''): ?><div class="edit_items">
                <label class="edit_label">历史负责人：</label>
                <div class="edit_info">
                  <input type="text" name="old_charge_person" value='<?php echo ($project["old_charge_person"]); ?>'>
                </div>
            </div><?php endif; ?>
        <?php if($project["Ter_reason"] != ''): ?><div class="edit_items">
                <label class="edit_label">终止原因：</label>
                <div class="edit_info">
                  <textarea readonly  name="Ter_reason"><?php echo ($project["Ter_reason"]); ?></textarea>
                </div>
            </div><?php endif; ?>
        <div class="edit_items">
            <label class="edit_label">备注：</label>
            <div class="edit_info">
                <textarea name="remark"><?php echo ($project["remark"]); ?></textarea>
            </div>
        </div>
        
        <div class="action_btn">
            <input type="hidden" name="id" value="<?php echo ((isset($project["id"]) && ($project["id"] !== ""))?($project["id"]):''); ?>">
            <?php if($group_id["group_id"] == 7): ?><button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">保 存</button><?php endif; ?>
            <?php if($group_id["group_id"] == ''): ?><button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">保 存</button><?php endif; ?>
            
        </div>
    </form>
    <!-- 标题栏 -->
    <div class="edit_title edit_title2">项目跟踪记录</div>
    <div class="search-form cf text-right">
        <button class="btn" id="action_add" url="<?php echo U('project/tail_add',array('project_id'=>$project['id']));?>">新 增</button>
       <!--  <button class="btn ajax-post" target-form="ids" url="<?php echo u('setstatus',array('status'=>1));?>" >启 用</button>
        <button class="btn ajax-post" target-form="ids" url="<?php echo u('setstatus',array('status'=>0));?>">禁 用</button> -->
        <button class="btn ajax-post confirm" target-form="ids" url="<?php echo U('tail_delete');?>">删 除</button>

    </div>
    <!-- 数据列表 -->
    <div class="data-table">
    <table class="">
        <thead>
            <tr>
            <th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
            <th>项目名称</th>
            <th>销售负责人</th>
            <th>状态</th>
            <th>本次意向产品</th>
            <th>本次项目预估(元)</th>
            <!-- <th>开始日期</th> -->
            <th>情况说明</th>
            <th>商务费用(元)</th>
            <th>跟进人</th>
            <th>跟进日期</th>
            <th>操作</th>
            
            </tr>
        </thead>
        <tbody>
            <input class="ids" type="hidden" name="ids[project_id]" value="<?php echo ($project["id"]); ?>" />
            <?php if(is_array($tails)): $i = 0; $__LIST__ = $tails;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td><input class="ids" type="checkbox" name="ids[id][]" value="<?php echo ($vo["id"]); ?>" />
                        
                    </td>
                    <td class=""><?php echo ($project["project_name"]); ?></td>
                    <td class=""><?php echo ($project["charge_person"]); ?></td>
                    <td>
                        <?php if($vo["status"] == 0): ?>进行中
                        <?php else: ?>
                            完成<?php endif; ?>
                    </td>
                    <td class="" style="max-width: 110px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;" title="<?php echo ($vo["intent_product"]); ?>"><?php echo ($vo["intent_product"]); ?></td>
                    <td class=""><?php echo ($vo["budget"]); ?></td>
                    <td class="" style="max-width: 150px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;" title="<?php echo ($vo["discribe"]); ?>"><?php echo ($vo["discribe"]); ?></td>
                    <td class=""><?php echo ($vo["fee"]); ?></td>
                    <td class=""><?php echo ($vo["nickname"]); ?></td>
                    <td class=""><?php echo ($vo["follow_up_time"]); ?></td>
                    <td>
                      <a href="<?php echo U('Project/tail_add?id='.$vo['id']);?>">详情</a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table> 
        
    </div>
    <!-- 分页 -->
    <div class="page"><?php echo ($_page); ?></div>
    <!-- /分页 -->
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
$(function(){
    // 多选框默认值赋值
    var defaultSelect = '<?php echo ($project["purchase_intention"]); ?>';
    if (defaultSelect) {
        $('select.purchase_intention').selectpicker('val',defaultSelect.split(',')).trigger("change");
    }

    // 日期插件
      $('.form_datetime').datetimepicker({
        format: 'yyyy-mm-dd',
        language: "zh-CN",
        minView: 2,
        autoclose: true,
        todayBtn: true
      });

    $("#action_add").click(function(){
        window.location.href = $(this).attr('url');
    })

    //搜索功能
    $("#search").click(function() {
        var url = $(this).attr('url');
        var query = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
        query = query.replace(/^&/g, '');
        if (url.indexOf('?') > 0) {
            url += '&' + query;
        } else {
            url += '?' + query;
        }
        window.location.href = url;
    });
    //回车搜索
    $(".search-input").keyup(function(e) {
        if (e.keyCode === 13) {
            $("#search").click();
            return false;
        }
    });
})
</script>

</body>
</html>
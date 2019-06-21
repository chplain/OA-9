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
    .edit_items{width: 200px;}
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
      <a href="<?php echo u('tech/index');?>">实施计划维护</a>
      <span class="divider">/</span>
    </div>
    <div><?php echo ($info['id']?'编辑':'新增'); ?>实施计划</div>
  </div>


    
  <div class="newly">
    <!-- 标题栏 -->
    <form action="/oa/www/admin/tech/plan_mess/id/26.html" method="post" class="form-horizontal">
      <div class="edit_title">项目信息</div>
      <div class="edit_items">
        <label class="edit_label">计划编号：</label>
        <div class="edit_info">
          <input readonly placeholder="系统自动生成" name="plan_code" type="text" value='<?php echo ($plan["plan_code"]); ?>'/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">客户名称：</label>
        <div class="edit_info">
          <input readonly placeholder="" type="text" value='<?php echo ($plan["customer"]); ?>'/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">项目|商业名称：</label>
        <div class="edit_info">
          <input readonly placeholder=""  type="text" value='<?php echo ($plan["project_name"]); ?>'/>
          <input readonly placeholder="" name="project_id" type="hidden" value='<?php echo ($plan["pid"]); ?>'/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">合同编号：</label>
        <div class="edit_info">
          <input readonly placeholder="" name="contract_code" type="text" value='<?php echo ($plan["contract_code"]); ?>'/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">采购产品：</label>
        <div class="edit_info">
          <input readonly id="products" name="contract_productlist" type="text" value='<?php echo ($plan["products"]); ?>'/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">项目费用(元)：</label>
        <div class="edit_info">
          <input readonly id="fee" type="text" name="contract_fee" value='<?php echo ($plan["contract_fee"]); ?>'/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">项目地区：</label>
        <div class="edit_info">
          <input readonly id="province" type="text" name="province" value='<?php echo ($plan["province"]); ?>'/>
        </div>
      </div>

      <div class="edit_items">
        <label class="edit_label">进场日期：</label>
        <div class="edit_info">
          <div class="date form_datetime">
            <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
            <input type="text" class="date" id="EnterTime" name="EnterTime" placeholder="选择日期" value='<?php echo ($plan["EnterTime"]); ?>' readonly>
          </div>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">开业日期：</label>
        <div class="edit_info">
          <div class="date form_datetime">
            <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
            <input type="text" class="date" id="OpenTime" name="OpenTime" placeholder="选择日期" readonly value='<?php echo ($plan["OpenTime"]); ?>'>
          </div>
        </div>
      </div>

      <div class="edit_items">
          <label class="edit_label">关键客户人：</label>
          <div class="edit_info">
            <input readonly id="keyCustom" name="keyCustom" type="text" value='<?php echo ($plan["keyCustom"]); ?>'/>
          </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">联系电话：</label>
        <div class="edit_info">
          <input type="text" class="text" id="link_phone" name="link_phone" readonly value='<?php echo ($plan["link_phone"]); ?>'>
        </div>
      </div>
      <div class="edit_items">
          <label class="edit_label">项目风险评估：</label>
          <div class="edit_info">
            <input readonly type="text" id="ProjectRishLevel" name="ProjectRishLevel" value="<?php echo ($plan["ProjectRishLevel"]); ?>"/>
          </div>
      </div>
      <div class="edit_items">
          <label class="edit_label">项目风险：</label>
          <div class="edit_info">
            <textarea id="ProjectRishDescribe"><?php echo ($plan["ProjectRishDescribe"]); ?></textarea>
          </div>
      </div>
      <div class="edit_items">
          <label class="edit_label">客户期望：</label>
          <div class="edit_info">
            <input readonly type="text" id="CustomerExpectLevel" name="CustomerExpectLevel" value="<?php echo ($plan["CustomerExpectLevel"]); ?>"/>
          </div>
      </div>

      <div class="edit_items">
        <label class="edit_label">竞争对手情况：</label>
        <div class="edit_info">
          <textarea name="Project_opponents"><?php echo ($plan["Project_opponents"]); ?></textarea>
        </div>
      </div>

      <div class="edit_items">
        <label class="edit_label">客户IT现状：</label>
        <div class="edit_info">
          <textarea id="CustomerITNow"><?php echo ($plan["CustomerITNow"]); ?></textarea>
        </div>
      </div>
      <div class="edit_items">
          <label class="edit_label">是否系统集成：</label>
          <div class="edit_info">
            <input readonly type="text" id="IsSI" name="IsSI" value="<?php echo ($plan["IsSI"]); ?>" />
          </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">备注：</label>
        <div class="edit_info">
          <textarea id="remark" style="width: 300px;height: 100px"><?php echo ($plan["remark"]); ?></textarea>
        </div>
      </div>
      <br>
      <div class="edit_items">
        <label class="edit_label">商务负责人:</label>
        <div class="edit_info">
          <input readonly type="text" id="salesRole" name="salesRole" value="<?php echo ($plan["charge_person"]); ?>"/>
        </div>
      </div>
      <div class="edit_items">

        <label class="edit_label">销售经理：</label>
          <div class="edit_info">
            <input readonly type="text" id="SalesRole" name="SalesRole" value="<?php echo ($plan["SalesRole"]); ?>"/>
          </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">产品经理：</label>
        <div class="edit_info">
          <input readonly type="text" id="ProductRole" name="ProductRole" value="<?php echo ($plan["ProductRole"]); ?>"/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">实施经理：</label>
        <div class="edit_info">
          <input readonly type="text" id="TechRole" name="TechRole" value="<?php echo ($plan["TechRole"]); ?>"/>
        </div>
      </div>
      <div class="edit_items">
        <label class="edit_label">开发经理：</label>
        <div class="edit_info">
          <input readonly type="text" id="DevRole" name="DevRole" value="<?php echo ($plan["DevRole"]); ?>"/>
        </div>
      </div>
      <div class="edit_title">实施计划</div>
      <div class="edit-box fee">
        <?php if(is_array($phases)): $a = 0; $__LIST__ = $phases;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($a % 2 );++$a;?><div class="cell">
            <p class="sub-title">第<?php echo ($vo1["phases"]); ?>阶段：</p>
            <div class="content-box">
              <div class="edit_items">
                <label class="edit_label">实施产品：</label>
                <div class="edit_info">
                  <select name="data[<?php echo ($a-1); ?>][products][]" class="selectpicker" data-width="172" title="请选择" multiple data-size="6">
                    <?php if($products): if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo7): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo7["product_name"]); ?>" <?php if(in_array( $vo7['product_name'],explode(",",$vo1['products']))){ echo 'selected';}?>><?php echo ($vo7["product_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                      <?php else: ?>
                      <option disabled>没有数据</option><?php endif; ?>
                  </select>
                </div>
              </div>
              <div class="edit_items">
                <label class="edit_label">实施期间：</label>
                <div class="edit_info">
                  <div class="date form_datetime">
                    <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
                    <input type="text" class="date" name="data[<?php echo ($a-1); ?>][stime]" placeholder="选择日期" readonly value="<?php echo ($vo1["stime"]); ?>">
                  </div>
                  <span class="spacing">至</span>
                  <div class="date form_datetime">
                    <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
                    <input type="text" class="date" name="data[<?php echo ($a-1); ?>][etime]" placeholder="选择日期" readonly value="<?php echo ($vo1["etime"]); ?>">
                  </div>
                </div>
              </div>
              <div class="edit_items">
                <label class="edit_label">实施人员：</label>
                <div class="edit_info">
                  <select name="data[<?php echo ($a-1); ?>][executor][]" class="selectpicker" data-width="172" multiple title="请选择" data-size="6" data-live-search="true" data-live-search-placeholder="Search">
                    <?php if(is_array($techs)): $i = 0; $__LIST__ = $techs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo5): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo5["nickname"]); ?>" <?php if(in_array( $vo5['nickname'],explode(",",$vo1['executor']))){ echo 'selected';}?>><?php echo ($vo5["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                  </select>
                </div>
              </div>
              <div class="edit_items">
                <label class="edit_label">是否是收款周期：</label>
                <div class="edit_info">
                  <?php if($vo1['IsPayPhases'] == '0'): ?><label for="no<?php echo ($a-1); ?>" class="radio_box">
                        <input type="radio" id="no<?php echo ($a-1); ?>" name="data[<?php echo ($a-1); ?>][IsPayPhases]" checked value="0">
                        <span class="radio-bg"></span>
                        <sapn class="radio-text">否</sapn>
                      </label>
                      <label for="yes<?php echo ($a-1); ?>" class="radio_box">
                        <input type="radio" id="yes<?php echo ($a-1); ?>" name="data[<?php echo ($a-1); ?>][IsPayPhases]"  value="1">
                        <span class="radio-bg"></span>
                        <span class="radio-text">是</span>
                      </label>
                  <?php elseif($vo1['IsPayPhases'] == '1'): ?>
                      <label for="no<?php echo ($a-1); ?>" class="radio_box">
                        <input type="radio" id="no<?php echo ($a-1); ?>" name="data[<?php echo ($a-1); ?>][IsPayPhases]" value="0">
                        <span class="radio-bg"></span>
                        <sapn class="radio-text">否</sapn>
                      </label>
                      <label for="yes<?php echo ($a-1); ?>" class="radio_box">
                        <input type="radio" id="yes<?php echo ($a-1); ?>" name="data[<?php echo ($a-1); ?>][IsPayPhases]" checked value="1">
                        <span class="radio-bg"></span>
                        <span class="radio-text">是</span>
                      </label><?php endif; ?>
                </div>
              </div>
              <div class="edit_items">
                <label class="edit_label">收款金额(元)：</label>
                <div class="edit_info">
                  <input type="text" name="data[<?php echo ($a-1); ?>][PhasesFee]" value="<?php echo ($vo1["PhasesFee"]); ?>">
                </div>
              </div>
              <div class="edit_items">
                <label class="edit_label">预计回款日期：</label>
                <div class="edit_info">
                  <div class="date form_datetime">
                    <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
                    <input type="text" class="date" name="data[<?php echo ($a-1); ?>][rtdate]"  placeholder="选择日期" readonly value="<?php echo ($vo1["rtdate"]); ?>">
                  </div>
                </div>
              </div>
              <div class="edit_items">
                <label class="edit_label">预计回款比例(%)：</label>
                <div class="edit_info">
                  <input type="text" name="data[<?php echo ($a-1); ?>][rpercent]" value="<?php echo ($vo1["rpercent"]); ?>">
                </div>
              </div>
              <div class="edit_items">
                <label class="edit_label">预计回款金额(w)：</label>
                <div class="edit_info">
                  <input type="text" name="data[<?php echo ($a-1); ?>][rmoney]" value="<?php echo ($vo1["rmoney"]); ?>">
                </div>
              </div>

              <div class="edit_items">
                <label class="edit_label">备注：</label>
                <div class="edit_info">
                  <textarea name="data[<?php echo ($a-1); ?>][remark]"><?php echo ($vo1["remark"]); ?></textarea>
                </div>
              </div>
              <div class="edit_btn" <?php if(count($phases) !== $a) { echo 'style="display: none;"';}?>>
                <span class="add-btn" data-index="<?php echo ($a); ?>" data-sign="charge">+</span>
                <span class="del-btn" data-index="<?php echo ($a); ?>" data-sign="charge">-</span>
              </div>
            </div>
          </div><?php endforeach; endif; else: echo "" ;endif; ?>
      </div>
      <div class="edit_title">其他信息</div>
      <div class="edit-box remarks">
        <div class="cell">
          <div class="edit_items">
            <label class="edit_label">备注：</label>
            <div class="edit_info">
              <textarea name="remark" style="width: 300px;height: 150px;"><?php echo ($plan["rem"]); ?></textarea>
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
              <?php if($plan["accessory"] == ''): ?><input type="file" name="accessory" >
              <?php else: ?>
                <a target="view_window" href='<?php echo ($plan["accessory"]); ?>' class="updown">附件下载</a>
                <input type="file" name="accessory" ><?php endif; ?>
          </div>
          <!-- <div class="edit_btn">
            <span class="add-btn" data-index="1" data-sign="upload">+</span>
          </div> -->
        </div>
      </div>
      <div class="edit_btn action_btn">
        <input type="hidden" name="id" value="<?php echo ((isset($plan["id"]) && ($plan["id"] !== ""))?($plan["id"]):''); ?>">
        <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
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
  <script type="text/javascript" src="/OA/www/Public/Admin/js/artTemplate.js"></script>
  <script id="list1" type="text/html">
    {{if sign == 'charge'}}
    <div class="cell">
      <p class="sub-title">第{{upper}}阶段：</p>
      <div class="content-box">
        <div class="edit_items">
          <label class="edit_label">实施产品：</label>
          <div class="edit_info">
            <select name="data[{{index}}][products][]" class="selectpicker" data-width="172" title="请选择" multiple data-size="6">
              <?php if($products): if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo2["product_name"]); ?>"><?php echo ($vo2["product_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                <option disabled>没有数据</option><?php endif; ?>
            </select>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">实施期间：</label>
          <div class="edit_info">
            <div class="date form_datetime">
              <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
              <input type="text" class="date" name="data[{{index}}][stime]" placeholder="选择日期" readonly>
            </div>
            <span class="spacing">至</span>
            <div class="date form_datetime">
              <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
              <input type="text" class="date" name="data[{{index}}][etime]" placeholder="选择日期" readonly>
            </div>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">实施人员：</label>
          <div class="edit_info">
            <select name="data[{{index}}][executor][]" class="selectpicker" data-width="172" multiple title="请选择" data-size="6" data-live-search="true" data-live-search-placeholder="Search">
              <?php if(is_array($techs)): $i = 0; $__LIST__ = $techs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo3["nickname"]); ?>"><?php echo ($vo3["nickname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">是否是收款周期：</label>
          <div class="edit_info">

            <label for="no{{index}}" class="radio_box">
              <input type="radio" id="no{{index}}" name="data[{{index}}][IsPayPhases]" checked value="0">
              <span class="radio-bg"></span>
              <sapn class="radio-text">否</sapn>
            </label>

            <label for="yes{{index}}" class="radio_box">
              <input type="radio" id="yes{{index}}" name="data[{{index}}][IsPayPhases]" value="1">
              <span class="radio-bg"></span>
              <span class="radio-text">是</span>
            </label>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">收款金额(元)：</label>
          <div class="edit_info">
            <input type="text" name="data[{{index}}][PhasesFee]">
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">预计回款日期：</label>
          <div class="edit_info">
            <div class="date form_datetime">
              <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
              <input type="text" class="date" name="data[{{index}}][rtdate]" placeholder="选择日期" readonly>
            </div>
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">预计回款比例(%)：</label>
          <div class="edit_info">
            <input type="text" name="data[{{index}}][rpercent]">
          </div>
        </div>
        <div class="edit_items">
          <label class="edit_label">预计回款金额(w)：</label>
          <div class="edit_info">
            <input type="text" name="data[{{index}}][rmoney]">
          </div>
        </div>

        <div class="edit_items">
          <label class="edit_label">备注：</label>
          <div class="edit_info">
            <textarea name="data[{{index}}][remark]"></textarea>
          </div>
        </div>
        <div class="edit_btn">
          {{if index < 5}}
          <span class="add-btn" data-index="{{index+1}}" data-sign="charge">+</span>
          {{/if}}
          <span class="del-btn" data-index="{{index+1}}" data-sign="charge">-</span>
        </div>
      </div>
    </div>
    {{else if sign == 'remarks'}}
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
      $('.side-sub-menu').find('a[href="<?php echo U('tech/index');?>"]').closest('li').addClass('current');

      // 日期插件
      $('.form_datetime').datetimepicker({
        format: 'yyyy-mm-dd',
        language: "zh-CN",
        minView: 2,
        autoclose: true,
        todayBtn: true
      });

      $("#customer").change(function () {
        // $("#project_id").empty();
        $("#contract_code").empty();
        var customer = $(this).val();
        // Ajax提交数据
        // return;
        $.ajax({
          url: "<?php echo U('Tech/get_transferProject');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"customer": customer},  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            $("#project_id").empty();
            $('#contract_code').html('<option disabled>没有数据</option>').selectpicker('refresh');
            $("#products").val('');
            $("#fee").val('');
            $("#province").val('');
            $("#start_time").val('');
            $("#end_time").val('');
            $("#salesRole").val('');
            $("#TechRole").val('');
            $("#OpenTime").val('');
            $("#keyCustom").val('');
            $("#link_phone").val('');
            $("#EnterTime").val('');
            $("#CustomerITNow").val('');
            $("#ProjectRishDescribe").val('');
            let str = '';
            data.map(item => {
              str += `<option value='${item.id}'>${item.project_name}</option>`;
            });
            $("#project_id").append(str).selectpicker('refresh');

          },
        });
      });

      $("#project_id").change(function () {
        var project_id = $(this).val();
        // Ajax提交数据
        $.ajax({
          url: "<?php echo U('Tech/getContractCode');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"project_id": project_id},  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            console.log(data);
            $("#contract_code").empty();
            let str = '';
            data.map(item => {
              str += `<option value='${item.contract_code}'>${item.contract_code}</option>`;
            });
            $("#contract_code").append(str).selectpicker('refresh');
            $("#products").val('');
            $("#fee").val('');
            $("#province").val('');
            $("#start_time").val('');
            $("#end_time").val('');
            $("#salesRole").val('');
            $("#TechRole").val('');
            $("#OpenTime").val('');
            $("#keyCustom").val('');
            $("#link_phone").val('');
            $("#EnterTime").val('');
            $("#CustomerITNow").val('');
            $("#ProjectRishDescribe").val('');
            // $("#products").val(data.contract_productlist);
            // $("#fee").val(data.contract_fee);
            // $("#province").val(data.province);
            // $("#start_time").val(data.start_time);
            // $("#end_time").val(data.end_time);
            // $("#salesRole").val(data.sal);
            // $("#TechRole").val(data.TechRole);
            // $("#OpenTime").val(data.OpenTime);
            // $("#keyCustom").val(data.keyCustom);
            // $("#link_phone").val(data.link_phone);
            // $("#EnterTime").val(data.EnterTime);
            // $("#CustomerITNow").val(data.CustomerITNow);
            // $("#ProjectRishDescribe").val(data.ProjectRishDescribe);
          },
        });

      });

      $("#contract_code").change(function () {
        var project_id = $(this).val();
        // Ajax提交数据
        $.ajax({
          url: "<?php echo U('Tech/get_transferProjectMess');?>",    // 提交到controller的url路径
          type: "post",    // 提交方式
          data: {"contract_code": project_id},  // data为String类型，必须为 Key/Value 格式。
          dataType: "json",    // 服务器端返回的数据类型
          success: function (data) {
            console.log(data);
            // $("#contract_code").empty();
            // let str = '';
            // data.map(item => {
            //   str += `<option value='${item.contract_code}'>${item.contract_code}</option>`;
            // });
            // $("#contract_code").append(str).selectpicker('refresh');
            $("#products").val(data.project_productlist);
            $("#fee").val(data.contract_fee);
            $("#province").val(data.province);
            $("#EnterTime").val(data.EnterTime);
            $("#OpenTime").val(data.OpenTime);
            $("#salesRole").val(data.charge_person);
            $("#keyCustom").val(data.keyCustom);
            $("#link_phone").val(data.link_phone);
            $("#CustomerITNow").val(data.CustomerITNow);
            $("#ProjectRishDescribe").val(data.ProjectRishDescribe);
            $("#TechRole").val(data.TechRole);
          },
        });

      });


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

      // 绑定添加信息点击事件
      $('.edit-box').on('click', '.add-btn', function () {
        var index = $(this).data('index'), sign = $(this).data('sign');
        $(this).parent().hide();
        var html = template('list1', {sign: sign, index: index, upper: index+1});
        if (sign == 'charge') {
          $('.fee').append(html);
          // 日期插件
          $('.form_datetime').datetimepicker({
            format: 'yyyy-mm-dd',
            language: "zh-CN",
            minView: 2,
            autoclose: true,
            todayBtn: true
          });
          $('select.selectpicker').selectpicker('refresh')
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
      })

      function DX(n) {
        if (!/^(0|[1-9]\d*)(\.\d+)?$/.test(n))
          return "数据非法";
        return '一二三四五六七八九'.charAt(n);
      }
    });
  </script>



</body>
</html>
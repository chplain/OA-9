<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo ((isset($meta_title) && ($meta_title !== ""))?($meta_title):'OA后台管理'); ?></title>
  <link href="/OA/www/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/base.css" media="all">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/module.css">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/style.css" media="all">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
  <link rel="stylesheet" href="/OA/www/Public/static/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/OA/www/Public/static/bootstrap/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="/OA/www/Public/static/datetimepicker/css/datetimepicker.css">
  <link rel="stylesheet" href="/OA/www/Public/static/datetimepicker/css/datetimepicker_blue.css">
  <link rel="stylesheet" href="/OA/www/Public/Admin/css/pagination.css">
  <link rel="stylesheet" type="text/css" href="/OA/www/Public/Admin/css/index.css">

  <!--[if lt IE 9]>
  <script type="text/javascript" src="/OA/www/Public/static/jquery-1.10.2.min.js"></script>
  <![endif]--><!--[if gte IE 9]><!-->
  <script type="text/javascript" src="/OA/www/Public/static/jquery-2.0.3.min.js"></script>
  <script type="text/javascript" src="/OA/www/Public/Admin/js/jquery.mousewheel.js"></script>
  <!--<![endif]-->

  <script type="text/javascript" src="/OA/www/Public/Admin/js/echarts.common.min.js"></script>
  <script type="text/javascript" src="/OA/www/Public/Admin/js/artTemplate.js"></script>
  <script src="/OA/www/Public/Admin/js/jquery.pagination.js"></script>
  <script src="/OA/www/Public/static/datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="/OA/www/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
</head>
<body>
<div class="wrap">
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

  <!-- 内容区 -->
  <div class="content">
    <div class="tab sale">
      <p class="title" id="saletitle">销售看板</p>
      <div class="sys_icon"></div>
      <div class="tabs_items">
        <div class="statistic">
          <a href="<?php echo U('Project/index');?>" class="items blue">
            <p class="subtitle">跟踪项目</p>
            <p class="num">0</p>
          </a>
          <a href="<?php echo U('contract/official');?>" class="items pink">
            <p class="subtitle">销售金额(元)</p>
            <p class="num">0</p>
          </a>
          <a class="items orange">
            <p class="subtitle">回款金额(元)</p>
            <p class="num">0</p>
          </a>
        </div>
        <p class="table-title">跟踪项目</p>
        <div class="data-table">
          <table>
            <thead>
            <tr>
              <th></th>
              <th class="">项目名称</th>
              <th class="">项目地址</th>
              <th class="">客户名称</th>
              <th class="">项目预估(元)</th>
              <th class="">意向产品</th>
              <th class="">商务负责人</th>
            </tr>
            </thead>
            <tbody class="list"></tbody>
          </table>
        </div>
        <div class="paging"></div>
        <p class="chart-title">累计销售和收款</p>
        <div class="chart-panel"></div>
        <div class="chart-title">
          <span style="margin-right: 15px;">销售项目进度表</span>
          <select class="selectpicker process" data-width="172" title="请选择" multiple data-size="6" data-live-search="true" data-live-search-placeholder="Search">
            <?php if(is_array($projects1)): $i = 0; $__LIST__ = $projects1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["project_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
        </div>
        <div class="chart-panel statistics-chart"></div>
        <p class="chart-title">30内项目跟进情况<span class="count_num" style="color: red;"></span></p>
        <div class="chart-panel summation"></div>
      </div>
    </div>
    <div class="tab after">
      <p class="title">售后看板</p>
      <div class="sys_icon"></div>
      <div class="tabs_items">
        <div class="search">
          <input type="text" placeholder="请输入名称" class="search_input">
          <button type="button" class="search_btn"><i class="btn-search"></i></button>
        </div>
        <div class="data-table">
          <table>
            <thead>
            <tr>
              <th></th>
              <th class="">项目完成时间</th>
              <th class="">项目名称</th>
              <th class="">项目阶段</th>
              <th class="">实施工作内容（最新）</th>
              <th class="">催款金额(元)</th>
            </tr>
            </thead>
            <tbody class="list"></tbody>
          </table>
        </div>
        <div class="paging"></div>
      </div>
    </div>
    <div class="tab achieve">
      <p class="title">实施看板</p>
      <div class="sys_icon"></div>
      <div class="tabs_items">
        <div class="statistic">
          <a href="<?php echo U('tech/index');?>" class="items blue">
            <p class="subtitle">正在实施项目</p>
            <p class="num">0</p>
          </a>
          <a href="<?php echo U('tech/payreminder');?>" class="items pink">
            <p class="subtitle">回款金额(元)</p>
            <p class="num">0</p>
          </a>
          <a href="<?php echo U('tech/payreminder');?>" class="items orange">
            <p class="subtitle">催款金额(元)</p>
            <p class="num">0</p>
          </a>
        </div>
        <p class="table-title">正在实施项目</p>
        <div class="data-table">
          <table>
            <thead>
            <tr>
              <th></th>
              <th class="">项目名称</th>
              <th class="">项目阶段</th>
              <th class="">实施工作内容</th>
              <th class="">实施人员</th>
              <th class="">待收款金额(元)</th>
            </tr>
            </thead>
            <tbody class="list">
          </table>
        </div>
        <div class="paging page"></div>
        <p class="table-title">正在收款项目</p>
        <div class="data-table">
          <table>
            <thead>
            <tr>
              <th></th>
              <th class="">催款单号</th>
              <th class="">项目名称</th>
              <th class="">项目阶段</th>
              <th class="">催款金额(元)</th>
              <th class="">催款比例</th>
              <th class="">催款人员</th>
              <th class="">催款情况</th>
              <th class=""></th>
            </tr>
            </thead>
            <tbody class="list1"></tbody>
          </table>
        </div>
        <div class="paging page1"></div>

        <p class="table-title">实施费用和人天</p>
        <div class="data-table">
          <table>
            <thead>
            <tr>
              <th></th>
              <!-- <th class="">催款单号</th> -->
              <th class="">项目名称</th>
              <th class="">项目阶段</th>
              <th class="">涉及产品</th>
              <th class="">报销费用(元)</th>
              <th class="">工作内容</th>
              <th class="">实施人员</th>
              <th class="">实施人天</th>
              <th class=""></th>
            </tr>
            </thead>
            <tbody class="list2"></tbody>
          </table>
        </div>
        <div class="paging page2"></div>
        <!-- <p class="chart-title">项目费用和人天</p>
        <div class="chart-panel"></div> -->
      </div>
    </div>
    <div class="tab tech">
      <p class="title">开发看板</p>
      <div class="sys_icon"></div>
      <div class="tabs_items">
        <div class="statistic">
          <a href="<?php echo U('dev/newdevlist');?>" class="items blue">
            <p class="subtitle">正在开发产品</p>
            <p class="num">0</p>
          </a>
          <a href="<?php echo U('dev/customization');?>" class="items pink">
            <p class="subtitle">正在定制化开发项目</p>
            <p class="num">0</p>
          </a>
          <a href="<?php echo U('dev/customization');?>" class="items orange">
            <p class="subtitle">定制化开发人天</p>
            <p class="num">0</p>
          </a>
        </div>
        <p class="table-title">已到期开发阶段</p>
        <div class="data-table">
          <table>
            <thead>
            <tr>
              <th></th>
              <th class="">项目完成时间</th>
              <th class="">项目名称</th>
              <th class="">项目阶段</th>
              <th class="">涉及产品</th>
              <th class="">开发负责人</th>
              <th class="">开发费用</th>
            </tr>
            </thead>
            <tbody class="list"></tbody>
          </table>
        </div>
        <div class="paging page"></div>
        <p class="table-title">开发交付记录</p>
        <div class="data-table">
          <table>
            <thead>
            <tr>
              <th></th>
              <th class="">交付编号</th>
              <th class="">项目名称</th>
              <th class="">交付类型</th>
              <th class="">需求提交日期</th>
              <th class="">预计完成日期</th>
              <th class="">实际完成日期</th>
              <th class="">交付日期</th>
              <th class="">交付产品</th>
              <th class="">开发负责人</th>
              <th class="">实施负责人</th>
            </tr>
            </thead>
            <tbody class="list1"></tbody>
          </table>
        </div>
        <div class="paging page1"></div>
        <div class="chart-title">
          <span style="margin-right: 15px;">新产品开发人天数统计</span>
          <select class="selectpicker newpro" data-width="120" title="请选择" data-size="6" data-live-search="true" data-live-search-placeholder="Search">
            <option value="5">所有</option>
            <option value="0">新增</option>
            <option value="1">正常</option>
            <option value="2">完成</option>
            <option value="3">暂停</option>
            <option value="4">延期</option>
            <!-- <?php if(is_array($projects1)): $i = 0; $__LIST__ = $projects1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["project_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?> -->
          </select>
        </div>
        <div class="chart-panel newproduct"></div>

        <div class="chart-title">
          <span style="margin-right: 15px;">定制开发人天数统计</span>
          
          <select class="selectpicker cusdev" data-width="172" title="请选择" data-size="6" data-live-search="true" data-live-search-placeholder="Search">
            <?php if(is_array($cuspro)): $i = 0; $__LIST__ = $cuspro;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?><option value="<?php echo ($s["project_name"]); ?>"><?php echo ($s["project_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
        </div>
        <div class="chart-panel cus"></div>
        
        <div class="chart-title">
          <span style="margin-right: 15px;">项目交付情况比对（默认最近一个月）</span><br>
          <div class="edit_items">
            <label class="edit_label">开始日期</label>
            <div class="edit_info">
              <div class="date form_datetime">
                  <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
                  <input type="text" class="date d1" name="open_time" placeholder="选择日期" readonly style='width: 110px;'>
              </div>
            </div>
            <label class="edit_label">截止日期</label>
            <div class="edit_info">
              <div class="date form_datetime">
                  <span class="add-on"><i class="icon-th glyphicon glyphicon-calendar"></i></span>
                  <input type="text" class="date d2" name="open_time" placeholder="选择日期" readonly style='width: 110px;'>
              </div>
            </div>
          </div>
        </div>

        <div class="chart-panel delivery"></div>
      </div>
    </div>
  </div>
  <!-- /内容区 -->

  <!-- 底部版权 -->
  <div class="footer">
    <div class="copyright"> ©2019 <a href="http://www.gdwstech.com">gdwstech.com</a> 成都高德维斯信息科技有限公司版权所有</div>
  </div>
  <!-- /底部版权 -->
</div>

<!-- 销售看板列表模板 -->
<script src="/OA/www/Public/static/bootstrap/js/bootstrap.min.js"></script>
<script src="/OA/www/Public/static/bootstrap/js/bootstrap-select.min.js"></script>
<script id="saleList" type="text/html">
  {{if content.length > 0}}
  {{each content as v index}}
  <tr>
    <td>{{index+1}}.</td>
    <td>{{v.project_name}}</td>
    <td>{{v.province}}</td>
    <td>{{v.customer}}</td>
    <td>{{v.budget}}</td>
    <td>{{v.purchase_intention}}</td>
    <td>{{v.charge_person}}</td>
  </tr>
  {{/each}}
  {{else}}
  <tr>
    <td colspan="6">
      <div class="no_data">没有数据</div>
    </td>
  </tr>
  {{/if}}
</script>

<!-- 售后看板列表模板 -->
<script id="afterList" type="text/html">
  {{if content}}
  {{each content as v index}}
  <tr>
    <td>{{index+1}}</td>
    <td>{{v.create_time}}</td>
    <td>{{v.project_name}}</td>
    <td>{{v.phases}}</td>
    <td>{{v.products}}</td>
    <td>{{v.PhasesFee}}</td>
  </tr>
  {{/each}}
  {{else}}
  <tr>
    <td colspan="6">
      <div class="no_data">没有数据</div>
    </td>
  </tr>
  {{/if}}
</script>

<!-- 正在实施项目列表模板 -->
<script id="achieveList" type="text/html">
  {{if content.length > 0}}
  {{each content as v index}}
  <tr>
    <td>{{index+1}}.</td>
    <td>{{v.project_name}}</td>
    <td>{{v.phases}}</td>
    <td>{{v.products}}</td>
    <td>{{v.executor}}</td>
    <td>{{v.PhasesFee}}</td>
  </tr>
  {{/each}}
  {{else}}
  <tr>
    <td colspan="6">
      <div class="no_data">没有数据</div>
    </td>
  </tr>
  {{/if}}
</script>

<!-- 正在收款项目列表模板 -->
<script id="achieveList1" type="text/html">
  {{if content.length > 0}}
  {{each content as v index}}
  <tr>
    <td>{{index+1}}.</td>
    <td>{{v.prompt_id}}</td>
    <td>{{v.project_name}}</td>
    <td>{{v.phases}}</td>
    <td>{{v.rmoney}}</td>
    <td>{{v.rpercent}}%</td>
    <td>{{v.executor}}</td>
    <td>{{v.prompt_result}}</td>
  </tr>
  {{/each}}
  {{else}}
  <tr>
    <td colspan="8">
      <div class="no_data">没有数据</div>
    </td>
  </tr>
  {{/if}}
</script>

<!-- 实施费用和人天 -->
<script id="achieveList2" type="text/html">
  {{if content.length > 0}}
  {{each content as v index}}
  <tr>
    <td>{{index+1}}.</td>
    <td>{{v.project_name}}</td>
    <td>{{v.phases}}</td>
    <td>{{v.products}}</td>
    <td>{{v.project_fee}}</td>
    <td>{{v.content}}</td>
    <td>{{v.executor}}</td>
    <td>{{v.manday}}</td>
  </tr>
  {{/each}}
  {{else}}
  <tr>
    <td colspan="8">
      <div class="no_data">没有数据</div>
    </td>
  </tr>
  {{/if}}
</script>

<!-- 已到期开发阶段列表模板 -->
<script id="techList" type="text/html">
  {{if content.length > 0}}
  {{each content as v index}}
  <tr>
    <td>{{index+1}}.</td>
    <td>{{v.etime}}</td>
    <td>{{v.project_name}}</td>
    <td>{{v.phases}}</td>
    <td>{{v.products}}</td>
    <td>{{v.dev_role}}</td>
    <td>{{v.PhasesFee}}</td>
    <!-- <td>{{v.prompt_result}}</td> -->
  </tr>
  {{/each}}
  {{else}}
  <tr>
    <td colspan="7">
      <div class="no_data">没有数据</div>
    </td>
  </tr>
  {{/if}}
</script>

<!-- 开发交付记录列表模板 -->
<script id="techList1" type="text/html">
  {{if content.length > 0}}
  {{each content as v index}}
  <tr>
    <td>{{index+1}}.</td>
    <td>{{v.delivery_code}}</td>
    <td>{{v.project_name}}</td>
    <td>{{v.delivery_type}}</td>
    <td>{{v.Need_time}}</td>
    <td>{{v.eEnd_time}}</td>
    <td>{{v.End_time}}</td>
    <td>{{v.del_date}}</td>
    <td>{{v.deliver_product}}</td>
    <td>{{v.dev_role}}</td>
    <td>{{v.tech_role}}</td>
  </tr>
  {{/each}}
  {{else}}
  <tr>
    <td colspan="7">
      <div class="no_data">没有数据</div>
    </td>
  </tr>
  {{/if}}
</script>

<script type="text/javascript">
  $(function () {
    var myChart1, myChart2, myChart3, myChart4, myChart5, myChart6, myChart7;

    var option1 = {
      color: ['#f4b400', '#0f9d58'],
      legend: {
        align: 'left',
        left: 100,
      },
      tooltip: {
        trigger: 'axis',
      },
      xAxis: {
        type: 'category',
        boundaryGap: false,
        silent: false,
        axisTick: {show: false},
        data: [],
      },
      yAxis: {
        axisLine: {
          show: false,
        },
        axisTick: {
          show: false,
        },
        axisLabel: {
          textStyle: {
            color: '#999',
          },
        },
        type: 'value',
      },
      series: [],
    };
    var option2 = {
      color: ['#f4b400', '#0f9d58', '#db4437', '#4285f4'],
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow',
        },
      },
      legend: {
        align: 'left',
        left: 100,
      },
      xAxis: {
        type: 'category',
        silent: false,
        axisTick: {show: false},
        data: [],
      },
      yAxis: {
        axisLine: {
          show: false,
        },
        axisTick: {
          show: false,
        },
        axisLabel: {
          textStyle: {
            color: '#999',
          },
        },
      },
      series: [],
    };
    var option3 = {
      color: ['#4285f4', '#0f9d58', '#db4437'],
      tooltip: {
        trigger: 'item',
        formatter: '{b}: {c} ({d}%)',
      },
      legend: {
        orient: 'vertical',
        x: 100,
      },
      series: [{
        type: 'pie',
        radius: ['50%', '90%'],
        avoidLabelOverlap: false,
        label: {
          normal: {
            show: false,
            position: 'center',
          },
          emphasis: {
            show: true,
            textStyle: {
              fontSize: '30',
              fontWeight: 'bold',
            },
          },
        },
        labelLine: {
          normal: {
            show: false,
          },
        },
        data: [],
      }],
    };
    var option4 = {
      color: ['#04c4f9', '#0fffdc', '#ffeb2f', '#ff1300'],
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow',
        },
      },
      legend: {
        data: ['陌生拜访', '方案提升', '项目投标', '商务谈判', '合同签订'],
      },
      grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true,
      },
      xAxis: {
        type: 'value',
        axisTick: {show: false}
      },
      yAxis: {
        type: 'category',
        axisLine: {
          show: false,
        },
        axisTick: {
          show: false,
        },
        axisLabel: {
          textStyle: {
            color: '#999',
          },
        },
        data: [],
      },
      series: [],
    };
    var option5 = {
      color: ['#f4b400', '#0f9d58'],
      legend: {
        align: 'left',
        left: 100,
      },
      tooltip: {
        trigger: 'axis',
      },
      xAxis: {
        type: 'category',
        boundaryGap: false,
        silent: false,
        axisTick: {show: false},
        data: [],
      },
      yAxis: {
        axisLine: {
          show: false,
        },
        axisTick: {
          show: false,
        },
        axisLabel: {
          textStyle: {
            color: '#999',
          },
        },
        type: 'value',
      },
      series: [],
    };
    var option6 = {
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow'
        }
      },
      legend: {
        data: ['预计人天', '实际人天']
      },
      grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
      },
      xAxis: {
        type: 'category',
        silent: false,
        axisTick: {show: false},
        axisLabel: {
          interval: 0,
          rotate: "-35"
        },
        data: []
      },
      yAxis: {
        axisLine: {
          show: false,
        },
        axisTick: {
          show: false,
        },
        axisLabel: {
          textStyle: {
            color: '#999',
          },
        },
        type: 'value'
      },
      series: []
    };
    var option7 = {
      tooltip: {
        trigger: 'axis',
        extraCssText: 'max-width: 35%;white-space:pre-wrap;',
        axisPointer: {
          type: 'shadow'
        },
        formatter: function (params) {
          var res = params[0].name + '<br/>'
          for (var i = 0, length = params.length; i < length; i++) {
             res += params[i].seriesName + '：' + params[i].value + '<br/>'
           }
           return res
        }
      },
      legend: {
        data: ['预计人天', '实际人天']
      },
      grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
      },
      xAxis: {
        type: 'category',
        silent: false,
        axisTick: {show: false},
        axisLabel: {
          interval: 0,
          rotate: "-35"
        },
        data: []
      },
      yAxis: {
        axisLine: {
          show: false,
        },
        axisTick: {
          show: false,
        },
        axisLabel: {
          textStyle: {
            color: '#999',
          },
        },
        type: 'value'
      },
      series: []
    };

    // 日期插件
    $('.form_datetime').datetimepicker({
      format: 'yyyy-mm-dd',
      language: "zh-CN",
      minView: 2,
      autoclose: true,
      todayBtn: true
    });

    $(".d1,.d2").change(function () {
      $.ajax({
        type: 'post',    // 提交方式
        url: "<?php echo U('Index/getDelByTime');?>",    // 提交到controller的url路径
        data: {
          d1: $('.d1').val(),
          d2: $('.d2').val(),
        },
        success: function (data) {
          myChart3 = echarts.init($('.delivery', $('.tech'))[0]);
          option3.series[0].data = [{
            name: '提前',
            value: data.dev.circle.aheadNum,
            status: 1,
            startTime: $('.d1').val(),
            endTime: $('.d2').val()
          }, {
            name: '按时',
            value: data.dev.circle.Num,
            status: 2,
            startTime: $('.d1').val(),
            endTime: $('.d2').val()
          }, {
            name: '超时',
            value: data.dev.circle.exNum,
            status: 3,
            startTime: $('.d1').val(),
            endTime: $('.d2').val()
          }];

          myChart3.setOption(option3);
        },
      });
    });

    $(".newpro").change(function () {
      $.ajax({
        type: 'post',    // 提交方式
        url: "<?php echo U('Index/getProductByStatus');?>",    // 提交到controller的url路径
        data: {
          status: $(this).val(),
        },
        success: function (data) {
          option6.xAxis.data = data.a;
          option6.series = [{
            name: '预计人天',
            type: 'bar',
            barMaxWidth: 30,
            data: data.b
          }, {
            name: '实际人天',
            type: 'bar',
            barMaxWidth: 30,
            data: data.c
          }];

          myChart6 = echarts.init($('.newproduct', $('.tech'))[0]);
          myChart6.setOption(option6);
        },
      });
    });

    $(".cusdev").change(function () {
      $.ajax({
        type: 'post',    // 提交方式
        url: "<?php echo U('Index/getCusByStatus');?>",    // 提交到controller的url路径
        data: {
          name: $(this).val(),
        },
        success: function (data) {
          console.log(data);
          option7.xAxis.data = data.a;
          option7.tooltip
          option7.series = [{
            name: '预计人天',
            type: 'bar',
            barMaxWidth: 30,
            data: data.b
          }, {
            name: '实际人天',
            type: 'bar',
            barMaxWidth: 30,
            data: data.c
          }, {
            name: '额外信息',
            type: 'bar',
            barMaxWidth: 30,
            data: data.d
          }];

          myChart7 = echarts.init($('.cus', $('.tech'))[0]);
          myChart7.setOption(option7);
        },
      });
    });

    $(".process").change(function () {
      $.ajax({
        type: 'post',    // 提交方式
        url: "<?php echo U('Index/getProcessByPid');?>",    // 提交到controller的url路径
        data: {
          project: $(this).val(),
        },
        success: function (data) {
          option4.yAxis.data = data.project_name;
          option4.series = [{
            name: '陌生拜访',
            type: 'bar',
            stack: '总量',
            barMaxWidth: 30,
            label: {
              normal: {
                show: true,
                position: 'insideRight',
              },
            },
            data: data.a
          }, {
            name: '方案提升',
            type: 'bar',
            stack: '总量',
            barMaxWidth: 30,
            label: {
              normal: {
                show: true,
                position: 'insideRight',
              },
            },
            data: data.b
          }, {
            name: '项目投标',
            type: 'bar',
            stack: '总量',
            barMaxWidth: 30,
            label: {
              normal: {
                show: true,
                position: 'insideRight',
              },
            },
            data: data.c
          }, {
            name: '商务谈判',
            type: 'bar',
            stack: '总量',
            barMaxWidth: 30,
            label: {
              normal: {
                show: true,
                position: 'insideRight',
              },
            },
            data: data.d
          }, {
            name: '合同签订',
            type: 'bar',
            stack: '总量',
            label: {
              normal: {
                show: true,
                position: 'insideRight',
              },
            },
            data: data.e
          }];

          myChart4 = echarts.init($('.statistics-chart')[0]);
          myChart4.setOption(option4);
        },
      });
    });

    $.ajax({
      url: '<?php echo U('Index/getAuth');?>',    // 提交到controller的url路径
      type: 'post',    // 提交方式
      dataType: 'json',    // 服务器端返回的数据类型
      success: function (data) {
        var status = data.status;

        var num = status - 1;
        if (num < 0) {
          $('.content .tab').show();
        } else {
          $('.content .tab').eq(num).show().siblings().hide();
        }

        // 销售看板数据
        if (status === 0 || status === 1) {
          var parent1 = $('.sale');
          $('.blue .num', parent1).text(data.sale.scount);
          $('.pink .num', parent1).text(data.sale.price);
          $('.orange .num', parent1).text(data.sale.rprice);

          var html1 = template('saleList', data.sale.gz);
          $('.list', parent1).html(html1);

          $('.paging', parent1).pagination({
            mode: 'fixed',
            totalData: data.sale.gz.count,
            showData: 10,
            isHide: true,
            callback: function (p) {
              $.ajax({
                type: 'get',
                url: '<?php echo U('Index/getPageContent');?>',
                data: {
                  type: 1,
                  gzpage: p.getCurrent(),
                },
                success: function (data) {
                  var list1 = template('saleList', {'content': data});
                  $('.list', parent1).html(list1);
                },
              });
            },
          });

          option1.xAxis.data = data.sale.month;
          option1.series = [{
            name: '销售',
            type: 'line',
            data: data.sale.ze,
          }, {
            name: '收款',
            type: 'line',
            data: data.sale.ze1,
          }];

          myChart1 = echarts.init($('.chart-panel', parent1)[0]);
          myChart1.setOption(option1);

          $('.count_num').html('(新增总数：' + data.sale.pr.num + '  跟进总数：' + data.sale.gz1.num + ')')
          option5.xAxis.data = data.sale.pr.day;
          option5.series = [{
            name: '新增',
            type: 'line',
            data: data.sale.pr.daynum,
          }, {
            name: '跟进',
            type: 'line',
            data: data.sale.gz1.daynum,
          }];

          myChart5 = echarts.init($('.summation')[0]);
          myChart5.setOption(option5);
        }

        // 售后看板数据
        if (status === 0 || status === 2) {
          var parent2 = $('.after');
          var html2 = template('afterList', data.after);
          $('.list', parent2).html(html2);

          $('.paging', parent2).pagination({
            mode: 'fixed',
            totalData: data.after.count,
            showData: 10,
            isHide: true,
            callback: function (p) {
              $.ajax({
                type: 'get',
                url: '<?php echo U('Index/getPageContent');?>',
                data: {
                  type: 2,
                  gzpage: p.getCurrent(),
                },
                success: function (data) {
                  var list2 = template('afterList', {'content': data});
                  $('.list', parent2).html(list2);
                },
              });
            },
          });
        }

        // 实施看板数据
        if (status === 0 || status === 3) {
          var parent3 = $('.achieve');
          $('.blue .num', parent3).text(data.tech.tcout);
          $('.pink .num', parent3).text(data.tech.rmoney);
          $('.orange .num', parent3).text(data.tech.cmoney || '0');

          var html3 = template('achieveList', data.tech.phases);
          $('.list', parent3).html(html3);
          var html4 = template('achieveList1', data.tech.ckproject);
          $('.list1', parent3).html(html4);
          var achieveHtml = template('achieveList2', data.tech.manday);
          $('.list2', parent3).html(achieveHtml);

          $('.page', parent3).pagination({
            mode: 'fixed',
            totalData: data.tech.phases.pcount,
            showData: 10,
            isHide: true,
            callback: function (p) {
              $.ajax({
                type: 'get',
                url: '<?php echo U('Index/getPageContent');?>',
                data: {
                  gzpage: p.getCurrent(),
                },
                success: function (data) {
                  var list3 = template('achieveList', {'content': data});
                  $('.list', parent3).html(list3);
                },
              });
            },
          });
          $('.page1', parent3).pagination({
            mode: 'fixed',
            totalData: data.tech.ckproject.ckcount,
            showData: 10,
            isHide: true,
            callback: function (p) {
              $.ajax({
                type: 'get',
                url: '<?php echo U('Index/getPageContent');?>',
                data: {
                  gzpage: p.getCurrent(),
                },
                success: function (data) {
                  var list4 = template('achieveList1', {'content': data});
                  $('.list1', parent3).html(list4);
                },
              });
            },
          });
          $('.page2', parent3).pagination({
            mode: 'fixed',
            totalData: data.tech.manday.count,
            showData: 10,
            isHide: true,
            callback: function (p) {
              $.ajax({
                type: 'get',
                url: '<?php echo U('Index/getPageContent');?>',
                data: {
                  gzpage: p.getCurrent(),
                },
                success: function (data) {
                  var list5 = template('achieveList2', {'content': data});
                  $('.list2', parent3).html(list5);
                },
              });
            },
          });
        }

        // 开发看板数据
        if (status === 0 || status === 4) {
          var parent4 = $('.tech');
          $('.blue .num', parent4).text(data.dev.dcount);
          $('.pink .num', parent4).text(data.dev.ccount);
          $('.orange .num', parent4).text(data.dev.cusmanday);

          var html5 = template('techList', data.dev.devphases);
          $('.list', parent4).html(html5);
          var html6 = template('techList1', data.dev.delivery);
          $('.list1', parent4).html(html6);

          $('.page', parent4).pagination({
            mode: 'fixed',
            totalData: data.dev.devphases.count,
            showData: 10,
            isHide: true,
            callback: function (p) {
              $.ajax({
                type: 'get',
                url: '<?php echo U('Index/getPageContent');?>',
                data: {
                  gzpage: p.getCurrent(),
                },
                success: function (data) {
                  var list5 = template('techList', {'content': data});
                  $('.list', parent4).html(list5);
                },
              });
            },
          });

          $('.page1', parent4).pagination({
            mode: 'fixed',
            totalData: data.dev.delivery.count,
            showData: 10,
            isHide: true,
            callback: function (p) {
              $.ajax({
                type: 'get',
                url: '<?php echo U('Index/getPageContent');?>',
                data: {
                  type: 7,
                  gzpage: p.getCurrent(),
                },
                success: function (data) {
                  var list6 = template('techList1', {'content': data});
                  $('.list1', parent4).html(list6);
                },
              });
            },
          });

          myChart3 = echarts.init($('.delivery', parent4)[0]);

          option3.series[0].data = [{
            name: '提前',
            value: data.dev.circle.aheadNum,
            status: 1
          }, {
            name: '按时',
            value: data.dev.circle.Num,
            status: 2
          }, {
            name: '超时',
            value: data.dev.circle.exNum,
            status: 3
          }];

          myChart3.setOption(option3);

          myChart3.on('click', function(param) {
              var _status = param.data.status, _startTime = param.data.startTime, _endTime = param.data.endTime;
              var _time1 = _startTime ? ('/startTime/' + _startTime) : '';
              var _time2 = _endTime ? ('/endTime/' + _endTime) : '';
              window.location.href = '/oa/www/admin/dev/dev_delivery/status/'+_status + _time1 + _time2;
          });
        }
      },
    });

    $('.sys_icon').click(function () {
      if ($(this).siblings('.tabs_items').is(':hidden')) {
        $(this).siblings('.tabs_items').show();
        $(this).removeClass('top');
      } else {
        $(this).siblings('.tabs_items').hide();
        $(this).addClass('top');
      }
    });

    $('.search_btn').click(function () {
      var searchText = $('.search_input').val();
      $.ajax({
        type: 'post',
        url: '<?php echo U('Index/ajaxAfter');?>',
        data: {
          project_name: searchText,
        },
        success: function (data) {
          var parent2 = $('.after');
          var afterHtml2 = template('afterList', data.after);
          $('.list', parent2).html(afterHtml2);

          $('.paging', parent2).pagination({
            mode: 'fixed',
            totalData: data.after.count,
            showData: 5,
            isHide: true,
            callback: function (p) {
              console.log(p);
            },
          });
        },
      });
    });
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

</script>
</body>
</html>
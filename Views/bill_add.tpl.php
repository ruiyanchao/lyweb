<!DOCTYPE html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>消费项目
                </label>
                <div class="layui-input-inline">

                    <div class="layui-input-inline">
                        <select name="consume_type">
                            <option value="早饭">早饭</option>
                            <option value="午饭">午饭</option>
                            <option value="晚饭">晚饭</option>
                            <option value="其他饮食">其他饮食</option>
                            <option value="日用">日用</option>
                            <option value="其他">其他</option></select>
                    </div>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>消费项目
                </div>
            </div>
            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red">*</span>金额
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="consume_sum" name="consume_sum" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>对应消费项目
                </div>
            </div>
            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red">*</span>日期
                </label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required"  autocomplete="off" placeholder="日期" name="consume_time" id="consume_time">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>消费当天日期
                </div>
            </div>
            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red"></span>备注
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="consume_remark" name="consume_remark" required=""
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red"></span>消费备注
                </div>
            </div>

            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    增加
                </button>
            </div>
        </form>
    </div>
</div>
<script>

    layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var form = layui.form;

        //执行一个laydate实例
        laydate.render({
            elem: '#consume_time' //指定元素
        });
    });

    $(function  () {
        layui.use('form', function(){
            var form = layui.form;

            //监听提交
            form.on('submit(add)', function(data){
                $.ajax({
                    url:"/bill/add",
                    data:data.field,
                    type:"Post",
                    dataType:"json",
                    success:function(data){
                        layer.msg(data.msg);
                        if(data.status == 1){
                            setTimeout(function(){
                                xadmin.father_reload();
                            }, 1000);

                        }
                    },
                    error:function(data){
                        $.messager.alert('错误',data.msg);
                    }
                });
                return false;
            });
        });
    })
</script>
</body>

</html>

layui.define(['element', 'jquery', 'layer'], function (exports) {
    let $ = layui.jquery, element = layui.element, layer = layui.layer;

    //处理头
    {


        let headerTpl = '';
        if (uid) {
            headerTpl = `<ul class="layui-nav layui-bg-black">
            <li class="layui-nav-item"><a href="./index.html">作业系统</a></li>
            <!--<li class="layui-nav-item"><a href="./manage.html">管理</a></li>-->
            <li class="layui-nav-item"><a href="#" id="s-add">发布</a></li>

          


            <li class="layui-nav-item layui-layout-right">
                <a class="login " onclick="layer.confirm('你确定要退出吗',function () {
            localStorage.clear();
            location.href='./index.html';
        })">退出</a>
            </li>
           

        </ul>`;
        } else {
            headerTpl = `<ul class="layui-nav layui-bg-black">
            <li class="layui-nav-item"><a href="./index.html">作业提交系统</a></li>

            <div class="layui-layout-right">
                <li class="layui-nav-item nonlogin">
                    <a href="./login.html">登陆</a>
                </li>
                <!--<li class="layui-nav-item nonlogin">-->
                    <!--<a href="./reg.html">注册</a>-->
                <!--</li>-->
            </div>
 
           

        </ul>`;
        }

        $('#sh-header').html(headerTpl);
    }
    //处理尾巴
    {
        let footerTpl = `
<hr>
                <div class="layui-footer sh-footer" >
                    <!-- 底部固定区域 -->
                   
                    <p>如果想一起优化这个小系统,请联系QQ:1228746736</p> 
                    <p>©2018 作业系统 by 孙武, 图表支持: 刘炳楠, 技术支持:王凡</p>
                    <p>备案/许可证编号为 黔ICP备17001679号 </p>
                 </div>`;
        $('#sh-footer').html(footerTpl);
    }
    //输出test接口

    obj = {
        delwork: function (id) {
            alert(2222);
        }
    };
    $('#s-add').click(function () {
        layer.prompt({
            formType: 1,
            title: '请输入密码',
        }, function (value, index, elem) {
            if (value === 'sunwu') {
                location.href='./av2.html';
            }
        });
    });
    exports('smod', obj);
});

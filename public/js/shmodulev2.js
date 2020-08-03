layui.define(['element', 'jquery', 'layer'], function (exports) {
    const $ = layui.jquery, element = layui.element, layer = layui.layer;


    //处理头
    {
        let headerTpl = '';
        if (uid) {
            headerTpl = `<ul class="layui-nav layui-bg-black">
    <li class="layui-nav-item"><a href=${pages.home}>作业系统</a></li>
    <li class="layui-nav-item"><a id="addwork">发布</a></li>
    <li class="layui-nav-item"><a id="chpwd">修改密码</a></li>
    <li class="layui-nav-item layui-layout-right">
        <a href="javascript:;" id="logout">退出</a>
    </li>
    
</ul>`;

            $.get(api.statistics + userapiqueryparams, (res) => {
                $('#sh-footer').html(`
                <hr>
                <div class="layui-footer sh-footer" >
                    <p>使用人数:${res.data.uc},使用班级:${res.data.cc}</p> 
                    <p>技术支持：1228746736@qq.com</p> 
                 </div>`);
            });


        } else {
            headerTpl = `<ul class="layui-nav layui-bg-black">
    <li class="layui-nav-item"><a href=${pages.home}>作业系统</a></li>

    <div class="layui-layout-right">
        <li class="layui-nav-item nonlogin">
            <a href=${pages.login}>登陆</a>
        </li>
    </div>
</ul>`;
            $('#sh-footer').html(`
                    <hr>
                <div class="layui-footer sh-footer" >
                    <p>如果想一起优化这个小系统,请联系QQ:1228746736</p> 
                    <p>©2018 作业系统 by 孙武, 图表支持: 刘炳楠, 技术支持:王凡</p>
                    <p>备案/许可证编号为 黔ICP备17001679号 </p>
                 </div>`);
        }

        $('#sh-header').html(headerTpl);
    }

    //输出test接口

    var obj = {
        delwork: function (id) {
            alert(2222);
        },
        //检查权限并跳转
        authredirect: (url) => {
            let au = layer.load(2);
            $.get(api.isAdmin + adminqueryparams, (res) => {
                layer.close(au);
                if (res.errno === 0) {
                    location.href = url;
                } else {
                    layer.msg(res.errmsg);
                }
            })
        }
    };

    $('#logout').click(() => {
        methods.logout();
    });
    $('#chpwd').click(() => {
        location.href=pages.changpwd;
    });
    $('#addwork').click(function () {
        obj.authredirect(pages.addwork);

    });


    var methods = {
        clearLoc: () => {
            layer.confirm('你确定要退出吗', function () {
                localStorage.clear();
                location.href = pages.home;
            })
        }, logout: () => {
            methods.clearLoc();
        }, settings: () => {
            layer.open({
                type: 2,
                title: false,
                closeBtn: 0, //不显示关闭按钮
                shade: [0.3],
                offset: 'rt', //右下角弹出
                // time: 2000, //2秒后自动关闭
                anim: 2,
                content: [pages.setting, 'no'], //iframe的url，no代表不显示滚动条
            });
        }
    };
    exports('shmodulev2', obj);
});

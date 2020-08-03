//是否为开发环境
const isDev = true;


let host = 'http://111.231.191.236';
// let host = 'http://www.sh.cn';


let server = {
    host: host,
    apihost: host + '/index.php'
};

let api = {
    'doing': server.apihost + "/index/doing",
    'done': server.apihost + "/index/done",
    'class': server.apihost + "/index/class",
    'detail': server.apihost + "/work/workDetail",            //添加数据[填表页]
    'reg': server.apihost + "/user/reg",
    'login': server.apihost + "/user/login",
    'uploadAutoRename': server.apihost + "/work/uploadAutoRename",
    'uploadInfo': server.apihost + "/work/uploadInfo",
    'downloadZip': server.apihost + "/work/downloadZip",
    'addSchoolWork': server.apihost + "/user/addSchoolWork",
    'modifySchoolWork': server.apihost + "/user/modifySchoolWork",
    'delWork': server.apihost + "/work/delWork",
    'getDeletedWorkOfClass': server.apihost + "/work/getDeletedWorkOfClass",
    'getAllWorkOfClass': server.apihost + "/work/getAllWorkOfClass",
};


//自动处理api
if (get_fk_class()) {
    api.doing = api.doing + '?class=' + get_fk_class();
    api.done = api.done + '?class=' + get_fk_class();
}


//用户信息
let uid = get_pk_user();
let uclass = get_fk_class();

function getOneQuery() {
    let query = location.search;
    let index = query.indexOf('=');
    return query.substr(index + 1);
}

function getSearch() {
    return location.search;
}
let tpl_sh = {
    doingItem: function (id, title, endtime) {
        return ` <div class="layui-col-md3">
                 <a href="./work.html?id=${id}" onclick="if(!isLogin()) location.href='./login.html'">
                      <div class="sh-box layui-bg-blue">
                    <span>${title}</span>
                    <p>截止到${endtime}</p>

                    </div>
                </a>
               
            </div>`;
    },
    doneItem: function (id, title, create_time) {
        return ` <div class="layui-col-md3"> <a href="./work.html?id=${id}">
                <div class="sh-box layui-bg-cyan">
                    <span>${title}</span>
                    <p>${create_time}</p>

                </div></a>
            </div>`;
    },
    workDetial: function (title, desc) {
        return `<li class="layui-timeline-item ">
                        <i class="layui-icon layui-timeline-axis">&#xe602;</i>
                        <div class="layui-timeline-content layui-text">
                            <h3 class="layui-timeline-title">${title} </h3>
                            <p class="timestamptostr">${desc}</p>
                        </div>
                    </li>`
    },
    option: function (val, title) {
        return `<option value="${val}">${title}</option>`;
    },
    th: function (title) {
        return `<th>${title}</th>`;
    },
    td: function (title) {
        return `<td>${title}</td>`;
    },
    table: function (head, body) {
        return ` <table   class="layui-table" id="table-container">
            <thead>
            <tr>
               ${head}
            </tr>
            </thead>
            <tbody>
                ${body}
            </tbody>
        </table>`;
    },
    nonWork: function () {
        return ` <div class="layui-col-md2">
                <div class="sh-box layui-bg-cyan">
                    <span>暂无作业</span>
                    <p>暂无作业</p>

                </div>
            </div>`;
    },
    exportToExcel: function () {
        return `
        <fieldset class="layui-elem-field">
            <legend>导出</legend>
            <div class="layui-form layui-field-box">
                <button id="dbToExcel" class="layui-btn layui-btn-fluid">导出为EXCEL</button>
            </div>
        </fieldset>`
    },


};

// (function transTime() {
//     let container=document.getElementsByClassName('.timestamptostr');
//     let ts=container.html();
//     let tdate=timestamptostr(ts);
//     container.html(tdate);
// })();

function isLogin() {
    if (localStorage.getItem('pk_user')) return localStorage.getItem('pk_user');
    return false;
}

function get_pk_user() {
    return localStorage.getItem('pk_user') ? localStorage.getItem('pk_user') : false;
}

function get_fk_class() {
    return localStorage.getItem('fk_class') ? localStorage.getItem('fk_class') : false;
}


function strtotimestamp(time) {
    return Date.parse(time) / 1000;
}

function timestamptostr(timestamp) {
    let date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
    Y = date.getFullYear() + '年';
    M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '月';
    D = date.getDate() + '日 ';
    h = date.getHours() + ':';
    m = date.getMinutes() + ':';
    s = date.getSeconds();
    return Y + M + D + h + m + s;
}

function  delWork() {
    alert('delWork');
}


## API
    index/doing/:class 进行中
    index/done/:class  已完成
    work/workDetail/:wid  作业详情
    work/uploadAutoRename/:wid 上传作业
    
    自动命名规则
    学号A|姓名B|作业名成C|时间D
    20150107030131-孙武-计算机网络实验报告-2017-08-01.doc
    



## 导入班级学生
>接口 :/index.php/user/inportUserFromExcel.html?class_id=2&password=(s.l)
>模板为班级id.xlsx,内容包含学号和姓名,并且只有一个sheet,该表格存在于upload/class/1.xlsx

http://sh.gzcjdx.cn/index.php/user/inportUserFromExcel.html?class_id=1&password=sunwu.liubingnan



1.创建班级同时添加好班级人员 post [domain]/index.php/user/addClassAndList
    
    <input type="file" name="stulists" /> <br> 
    <input type="test" value="" name="classname" /> 
    
    表格内容包含：学号和姓名,并且只有一个sheet
    
    
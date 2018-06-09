<?php

/**
* @method 账号管理
* @author xu 2018/05/16
*/
class sysAction
{

    /**
     * @method    修改管理员账户密码
     * @author    xu
     * @copyright 2018-05-16
    */
    public function ajaxSetPwd()
    {

        $data = $_POST['data'];
        // 新密码正则校验
        if(!LibFc::RegularValida($data['password'],'password'))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'密码格式不正确！']);

        // 校验密码
        $MainBase = new MainBase();
        $res = $MainBase->get('sh_user',['u_id','u_password'],sprintf(" and u_id=%d and u_password='%s'",$_SESSION['_userid'],md5($data['oldPassword'])),true);

        if(!empty($res['data']))
        {
            if($res['data']['u_password'] == md5($data['password']))
            {
                LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'密码与原密码相同！']);
            }
            $MainBase->set('sh_user',['u_password'=>md5($data['password'])],sprintf("and u_id=%d",$_SESSION['_userid']));
            LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'修改成功！']);
        }
        else
        {
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'密码错误！']);
        }
    }



    /**
     * @method    密码修改
     * @author    xu
     * @copyright 2018-05-16
    */
    public function password()
    {
        LibTpl::Set('title', '密码修改');
        LibTpl::Set('menu','sys');
        LibTpl::Put();
    }

    /**
     * @method    管理员列表
     * @author    xu
     * @copyright 2018-05-16
    */
    public function userList()
    {
        $Params = mvc::$URL_PARAMS;
        $MainBase = new MainBase();
        $page = isset($Params['page']) ? (int)$Params['page']:1;
        $perPage = 16;

        // 搜索条件
        $where = '';
        if(isset($Params['type']))
        {
            if(!in_array($Params['type'],['0','1','2']))
            {
                $Params['type'] = 1;
            }
            $where .= sprintf(" and u_style = '%d' ",$Params['type']);
        }

        // 限制其他管理员查看超级管理员
        if($_SESSION['_userid']!='1')
            $where.=sprintf(" and u_id!=1 ");
        
        if(!empty($Params['id']) && LibFc::Int($Params['id']))
            $where .= sprintf(" and u_id like '%%%s%%' ",$Params['id']);
        if(!empty($Params['realname']))
            $where .= sprintf(" and u_realname like '%%%s%%' ",$Params['realname']);
        if(!empty($Params['status']))
            $where .= sprintf(" and u_status ='%s' ",$Params['status']);
        
        // 排序
        $where .= "order by u_status desc,u_reg_time asc";
        $res = $MainBase->get('sh_user',['*'],$where.sprintf(" limit %d,%d",($page-1)*$perPage,$perPage));
        $user = $res['data'];
        LibTpl::Set('data',$user);

        // 分页
        $res = $MainBase->get('sh_user',['count(*) sum'],"",true);
        LibTpl::Set('count',$res['data']['sum']);
        LibTpl::Set('perPage',$perPage);
        LibTpl::Set('page',$page);

        // 状态枚举
        LibTpl::Set('userStat',['-1'=>'禁用','1'=>'正常']);
        LibTpl::Set('typeDes',['0'=>'客户','1'=>'管理员','2'=>'工厂']);
        
        // meta 
        LibTpl::Set('menu','sys');
        LibTpl::Set('Params',$Params);
        LibTpl::Set('title', '账户列表-'.mvc::$cfg['shopName']);
        LibTpl::Put();
    }



    /**
     * @method    检测唯一
     * @author    xu
     * @copyright 2018-05-17
     */
    function checkName()
    {
        $name = $_POST['name'];
        $MainBase = new MainBase();
        $res = $MainBase->get('sh_user',['u_id'],sprintf("and u_name='%s'",$name),true);
        if(!empty($res['data'])) {
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'用户名已经被使用！']);
        } else {
            LibFc::ajaxJsonEncode(['status'=>true, 'data'=>true]);
        }
    }


    /**
        * @method    删除管理员
        * @author    xu
        * @copyright 2018-05-18
    */
    function ajaxDelAdmin()
    {
        if(empty($_POST['id']) || !LibFc::Int($_POST['id']))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'删除账户参数错误！']);

        if($_SESSION['_userid'] == $_POST['id'])
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'您不能删除自己的账户！']);
        $MainUser = new MainUser();
        $MainUser->delUser($_POST['id'],$_SESSION['_userid']);

        LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'操作成功！']);
    }

    /**
        * @method    新增管理员
        * @author    xu
        * @copyright 2018-05-18
    */
    function addUser()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $data = $_POST['data'];
            $MainUser = new MainUser();
            $res = $MainUser->addUser($data);
            header('Location:'.mvc::$cfg['HOST']['adminUri'].'sys/setUser?id='.$res['data']);
            exit;
        }

        // meta 
        LibTpl::Set('Params',$Params);
        LibTpl::Set('menu','sys');
        LibTpl::Set('title', '管理员列表-'.mvc::$cfg['shopName']);
        LibTpl::Put();
    }

    /**
        * @method    编辑管理员
        * @author    xu
        * @copyright 2018-05-18
    */
    function setUser()
    {   
        $Params = mvc::$URL_PARAMS;
        if(empty($Params['id']) || !LibFc::Int($Params['id'])){
            header("Location:".mvc::$cfg['HOST']['adminUri']);
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // 保存更新
            $data = $_POST['data'];
            $MainUser = new MainUser();
            $MainUser->setUser($data,$Params['id']);
            header('Location:'.mvc::$cfg['HOST']['adminUri'].'sys/setUser?id='.$Params['id']);
            exit;
        }
        
        // 获取
        $MainBase = new MainBase();
        $res = $MainBase->get('sh_user',['*'],sprintf("and u_id=%d",$Params['id']),true);
        LibTpl::Set('data',$res['data']);
        if(empty($res['data'])){
            header("Location:".mvc::$cfg['HOST']['adminUri']);
            exit;
        }

        // meta 
        LibTpl::Set('Params',$Params);
        LibTpl::Set('menu','sys');
        LibTpl::Set('title', '管理员列表');
        LibTpl::Put('sys/addUser.php');
    }


    /**
        * @method    个人信息
        * @author    xu
        * @copyright 2018-05-18
    */
    function account()
    {   
        $Params = mvc::$URL_PARAMS;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // 保存更新
            $data = $_POST['data'];
            $MainUser = new MainUser();
            $MainUser->setUser($data,$_SESSION['_userid']);
        }
        
        // 获取
        $MainBase = new MainBase();
        $res = $MainBase->get('sh_user',['*'],sprintf("and u_id=%d",$_SESSION['_userid']),true);
        LibTpl::Set('data',$res['data']);

        // meta
        LibTpl::Set('typeDes',['1'=>'管理员','2'=>'工厂','0'=>'客户']); 
        LibTpl::Set('Params',$Params);
        LibTpl::Set('menu','sys');
        LibTpl::Set('title', '个人信息');
        LibTpl::Put();
    }
}
<?php

/**
* @method 管理员账户相关
* @author xu 2018/05/16
*/
class userAction
{


    /**
     * @method    用户登录
     * @author    xu
     * @copyright 2018-05-16
    */
    function login()
    {
        if(!empty($_SESSION['_userid']) && !empty($_SESSION['_username'])) {
            header("Location:".mvc::$cfg['HOST']['adminUri']);
            exit;
        }
        
        LibTpl::Set('title', '后台管理-'.mvc::$cfg['shopName']);
        LibTpl::Put();
    }



    /**
     * @method    用户登出
     * @author    xu
     * @copyright 2018-05-16
    */
    function logout()
    {

        // 去除会话凭证
        unset($_SESSION['_userid']);
        unset($_SESSION['_username']);
        unset($_SESSION['_userrealname']);
        unset($_SESSION['_style']);
        unset($_SESSION['_super']);

        LibTpl::Set('title', '后台管理-'.mvc::$cfg['shopName']);
        header("Location:".mvc::$cfg['HOST']['adminUri']);
        exit;
    }


    /**
     * @method    用户登录验证
     * @author    xu
     * @copyright 2018-05-16
    */
    function ajaxLogin()
    {
        // 检测参数
        $data = $_POST['data'];
        if(empty($data['username']))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'管理员账户不能为空！']);
        if(empty($data['password']))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'登录密码不能为空！']);

        // 验证
        $MainUser = new MainUser();

        // 校验账户是否存在
        $res = $MainUser->get('sh_user',['*'],sprintf(" and u_name='%s' ",LibFc::Escape($data['username'])),true);
        if(empty($res['data']))
            return LibFc::ReturnData(false,'账户不存在！');
        if($res['data']['u_password'] != md5($data['password']))
            return LibFc::ReturnData(false,'账户密码错误！');
        if($res['data']['u_status'] == '-1')
            return LibFc::ReturnData(false,'账户已被禁用！');

        // 更新登录时间
        $MainUser->set('sh_user',['u_login_time'=>time()],sprintf("and u_id=%d",$res['data']['adm_id']));

        // 清除本站所有会话凭证
        unset($_SESSION['_userid']);
        unset($_SESSION['_username']);
        unset($_SESSION['_userrealname']);
        unset($_SESSION['_style']);
        unset($_SESSION['_super']);

        // 记录凭证
        $_SESSION['_userid'] = $res['data']['u_id'];
        $_SESSION['_username'] = $res['data']['u_name'];
        $_SESSION['_userrealname'] = empty($res['data']['u_realname'])? $res['data']['u_name'] :$res['data']['u_realname'];
        $_SESSION['_style'] = $res['data']['u_style'];
        $_SESSION['_super'] = ($res['data']['u_id']==1)?true:false;

        LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'登录成功！']);
    }

}
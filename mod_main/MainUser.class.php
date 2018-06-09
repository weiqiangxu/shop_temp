<?php
/**
    * @method 后台管理信息
    * @author 许
    * @copyright 2018/05/16
    */
class MainUser extends MainBase
{

    /**
        * @method 新增账户
        * @param  $data arr ['u_name','u_password','u_realname'] 其余字段有传入且格式合法即可入库
        * @param  $status [boolean]  新增的账户是否启用状态-默认启用
        * @author xu
        * @copyright 2017/08/03
        * @return  成功：{'status':true,'data':arr} 失败：{'status':false,'data':'error'错误原因'}
    */
    public function addUser($data,$status = true)
    {   
        if(empty($data['u_name']) || empty($data['u_password']))
            return LibFc::ReturnData(false,'参数不正确');

        if(!LibFc::RegularValida($data['u_password'],'password'))
            return LibFc::ReturnData(false,'密码格式不正确！');

        if(!isset($data['u_style']) || !in_array($data['u_style'], ['0','1','2']))
            return LibFc::ReturnData(false,'账户类型未指明！');

        // 去重
        $res = $this->get('sh_user',['*'],sprintf("and u_name='%s' ",LibFc::Escape($data['u_name'])));
        if(!empty($res['data']))
            return LibFc::ReturnData(false,'账户已经存在！');

        // 入库
        $item = [
            'u_name' => $data['u_name'],
            'u_password' => md5($data['u_password']),
            'u_realname' => empty($data['u_realname'])?'':$data['u_realname'],
            'u_mobile' => $data['u_mobile'],
            'u_tel' => $data['u_tel'],
            'u_weixin' => $data['u_weixin'],
            'u_qq' => $data['u_qq'],
            'u_company' => $data['u_company'],
            'u_reg_time' => time(),
            'u_login_time' => time(),
            'u_style' => $data['u_style'],
            'u_status' => boolval($status)?1:-1
        ];
        $res = $this->add('sh_user',$item);

        return LibFc::ReturnData(true,$res['data']); 
    }


    /**
        * @method 获取账户列表
        * @author xu
        * @copyright 2018/05/18
        * @return  成功：{'status':true,'data':arr} 失败：{'status':false,'data':'error'错误原因'}
    */
    public function getUserList()
    {   
        $res = $this->get('sh_user',['*'],"");
        return LibFc::ReturnData(true,$res['data']); 
    }


    /**
        * @method 删除账户
        * @param  $targetAdmin  int 被删除账户ID
        * @param  $superAdmin   int 超级账户
        * @author xu
        * @copyright 2018/05/18
        * @return  成功：{'status':true,'data':arr} 失败：{'status':false,'data':'error'错误原因'}
    */
    public function delUser($targetUser,$superUser)
    {   
        // 参数
        if(!LibFc::Int($targetUser) || !LibFc::Int($superUser))
            return LibFc::ReturnData(false,"delUser 参数错误！"); 

        $res = $this->get('sh_user',['*'],sprintf("and u_id=%d",$targetUser),true);
        if(empty($res['data']))
            return LibFc::ReturnData(false,'账户不存在！');
        // 执行
        $this->del('sh_user',sprintf("and u_id=%d",$targetUser));

        return LibFc::ReturnData(true,'删除账户成功！'); 
    }


    /**
        * @method 编辑账户基本信息
        * @param  $data
        * @param  $status [boolean]  新增的账户是否启用状态-默认启用
        * @author xu
        * @copyright 2017/08/03
        * @return  成功：{'status':true,'data':arr} 失败：{'status':false,'data':'error'错误原因'}
    */
    public function setUser($data,$uid)
    {   

        if(!is_array($data) || !LibFc::Int($uid))
            return LibFc::ReturnData(false,'setUser 参数不正确！');

        if(!empty($data['u_password'])){   
            if(!LibFc::RegularValida($data['u_password'],'password')){
                return LibFc::ReturnData(false,'密码格式错误，修改失败！');
            }else{
                $data['u_password'] = md5($data['u_password']);
            }
        }
        if(isset($data['u_status']) && !in_array($data['u_status'],['-1','1']))
            unset($data['u_status']);
        $this->set('sh_user',$data,sprintf("and u_id=%d ",$uid));
        return LibFc::ReturnData(true,true); 
    }


    /**
        * @method 是否超级管理员
        * @param  $id
        * @author xu
        * @copyright 2017/08/03
        * @return  成功：{'status':true,'data':arr} 失败：{'status':false,'data':'error'错误原因'}
    */
    public function isSuper($id)
    {   

        if($id='1')
        {
            return LibFc::ReturnData(true,true); 
        }
        else
        {
            return LibFc::ReturnData(false,'非超级管理员'); 
        }
    }
}
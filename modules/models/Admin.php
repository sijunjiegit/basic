<?php

namespace app\modules\models;

use yii\db\ActiveRecord;
use Yii;

class Admin extends ActiveRecord
{
    public $rememberMe = true;
    public $confirmadminpass;
    public static function tableName()
    {
	return "{{%admin}}";
    } 

    public function attributeLabels()
    {
	return [
	    'adminuser' => '管理员账号',
	    'adminemail' => '管理员邮箱',
	    'adminpass' => '管理员密码',
	    'confirmadminpass' => '确认密码',
	];
    }

    public function rules()
    {
	return [
	   ['adminuser', 'required', 'message' => '用户名不能为空', 'on' => ['seek', 'login', 'changepassword', 'reg', 'changeemail']],
	   ['adminpass', "required", 'message' => "密码不能为空", 'on' => ['login', 'changepassword', 'reg', 'changepass']],
	   ['adminpass', 'validpass', 'message' => "用户名或者密码错误", 'on' => ['login']],
	   ['confirmadminpass','required', 'message'=>'确认密码不能为空', 'on'=>['changepassword', 'reg', 'changepass']],
	   ['confirmadminpass','validconfirmpass', 'message'=>'确认密码不能为空', 'on'=>['changepassword', 'reg', 'changepass']],
	   ['adminemail', 'required', 'message' => '邮箱不能为空', 'on' => ['reg', 'changeemail']],
	   ['adminemail', 'email', 'on'=>['seek', 'reg', 'changeemail'], 'message'=>"邮箱格式不正确",],
	   ['adminemail', 'validemail', 'on'=>'seek'],
	   ['adminuser', 'unique', 'message' => '用户名或者邮箱已被占用', 'on' => ['reg']],
	   ['adminemail', 'unique', 'message' => '用户名或者邮箱已被占用', 'on' => ['reg']],
	   ['adminemail', 'validateEmail', 'message' => '用户名或者邮箱已被占用', 'on' => ['changeemail']],
	];
    }

    public function validateEmail()
    {
	if (!$this->hasErrors())
	{
	    $user = self::find()
		->where("adminemail=:adminemail", [":adminemail" => $this->adminemail])
		->one(); 
	    if ($user != false)
	    {
		$this->addError('adminemail', '邮箱已被占用');		
	    }
    	}
    }

    public function unique()
    {
	if (!$this->hasErrors())
	{
	    $user = self::find()
		->where("adminuser=:adminuser or adminemail=:adminemail", [":adminuser"=>$this->adminuser, ":adminemail" => $this->adminemail])
		->one(); 
	    if ($user != false)
	    {
		$this->addError('adminuser', '用户名或者邮箱已经被占用');		
	    }
	}	
	return true;
    }

    public function validconfirmpass()
    {
	    if (!$this->hasErrors())
	    {
		    if ($this->adminpass == $this->confirmadminpass)
		    {
			return true;
		    }
		    else
		    {
			$this->addError("confirmadminpass","两次输入不一致");	
		    }
	    }	
    }
    public function validpass()
    {
	if (!$this->hasErrors())
	{
	    $res = self::find()
		->where("adminuser=:adminuser and adminpass=:adminpass",[":adminuser"=>$this->adminuser, ":adminpass"=>md5($this->adminpass)])
		->one();     
	    if ($res)
	    {
		return true;
	    }
	    else
	    {
		$this->addError("adminpass","用户名或者密码错误");	
	    }
	}	
    }

    public function validemail()
    {
	if (!$this->hasErrors())
	{
	    $res = self::find()
		->where("adminuser=:adminuser and adminemail=:adminemail", [":adminuser"=>$this->adminuser, ":adminemail"=>$this->adminemail])
		->one();
	    if (false == $res)
	    {
		$this->addError('adminemail', "用户名或者邮箱不匹配");	
	    }
	    return true;
	}
	return false;
    }

    public function login($post)
    {
	$this->scenario = 'login';
	if ($this->load($post) && $this->validate())
	{
	    $lefttime = $this->rememberMe ? 3600*24 : 0;	    
	    session_set_cookie_params($lefttime);
	    $session = Yii::$app->session;
	    $session['admin'] = ['adminuser'=>$this->adminuser,'isLogin'=>1];
	    $this->updateAll(['logintime'=>time(), 'loginip'=>ip2long(Yii::$app->request->userIp)], ["adminuser"=>$this->adminuser]);
	    return $session['admin']['isLogin'];
	}	
	return false;
    }

    public function seekPassword($post)
    {
	$this->scenario = 'seek';
	$model = new Admin;
	if ($this->load($post) && $this->validate())
	{
	    return true;
	}
	else
	{
	    $this->addError('adminemail','用户名或者邮箱错误'); 
	}
    }

    public function changepassword($post)
    {
	$this->scenario = 'changepassword';
	if ($this->load($post) && $this->validate())
	{
	    return $this->updateAll(['adminpass'=>md5($this->adminpass)], "adminuser = :adminuser", [":adminuser"=>$this->adminuser]);
	}	
	else
	{
	    $this->addError('adminemail','用户名或者邮箱错误'); 
	}
	return false;
    } 

    public function reg($post)
    {
	$this->scenario = 'reg';
	if ($this->load($post) && $this->validate())
        {
	    if ($this->save())
	    {
		return true;
	    }
	}
	return false;
    }

    public function changeEmail($post)
    {
	$this->scenario = 'changeemail';	
	if ($this->load($post) && $this->validate())
        {
	    return $this->updateAll(
		["adminemail" => $this->adminemail], "adminuser = :adminuser", 
		[":adminuser" => Yii::$app->session['admin']['adminuser']]
	    ); 
        }
	return false;
    }
    
    public function changePass($post)
    {
	$this->scenario = 'changepass';	
	if ($this->load($post) && $this->validate())
        {
	    return $this->updateAll(
		["adminpass" => $this->adminpass], "adminuser = :adminuser", 
		[":adminuser" => Yii::$app->session['admin']['adminuser']]
	    ); 
        }
	return false;
    }
}

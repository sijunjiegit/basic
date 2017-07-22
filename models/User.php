<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;


class User extends ActiveRecord
{
    public $confirmuserpass;

    public function attributeLabels()
    {
	return [
	    'username' => '管理员账号',
	    'useremail' => '管理员邮箱',
	    'userpass' => '管理员密码',
	    'confirmuserpass' => '确认密码',
	];
    }

    public function getProfile()
    {
	return $this->hasOne(Profile::className(), ['userid' => 'userid']);
    }      

    public function rules()
    {
	return [
            ['username', 'required', 'message'=>'用户名不能为空'],	
            ['username', 'checkUserName', 'message'=>'用户已被占用'],	
            ['useremail', 'required', 'message'=>'用户邮箱不能为空'],	
            ['useremail', 'email', 'message'=>'邮箱格式不正确'],	
            ['userpass', 'required', 'message'=>'密码不能为空'],	
            ['confirmuserpass', 'unique', 'message'=>'两次输入不一致'],	
            ['useremail', 'checkUserEmail', 'message'=>'用户邮箱不能为空'],	
	];	
    }

    public function checkUserName()
    {
	$result = self::find()->where("username = :username", [":username"=>$this->username])->one();	
	if (false == empty($result))
	{
	    $this->addError('username', "用户名被占用");
	}
	return true;
    }

    public function checkUserEmail()
    {
	$result = self::find()->where("useremail = :useremail", [":useremail"=>$this->useremail])->one();	
	if (false == empty($result))
	{
	    $this->addError('useremail', "邮箱被占用");
	}
	return true;
    }

    public function unique()
    {
	if (false == $this->hasErrors())		
	{
	    if ($this->userpass != $this->confirmuserpass)
	    {
		$this->addError('confirmuserpass', '两次输入密码不一致');	
	    }		
	}
    }

    public function reg($post)
    {
	if ($this->load($post) && $this->validate())
	{
            $connection = Yii::$app->db->beginTransaction();
	    try{
		$this->createtime = time();
		$this->insert();
		$profile = new Profile;		
		$profile->userid = $this->userid;
		$profile->insert();

		$connection->commit();
		return true;
	    }catch(Exception $e)
	    {
		$connection->rollback();
	    }  
	}
	return false;
    }
}

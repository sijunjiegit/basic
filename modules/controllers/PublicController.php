<?php

namespace app\modules\controllers;

use yii\web\Controller;
use app\modules\models\Admin;
use yii\web\Request;
use Yii;

class PublicController extends Controller
{
    public $layout = false;
    private $token = 'sijunjie@token';
    public function actionLogin()
    {
	$this->layout = false;
	$model = new Admin;
	$request = Yii::$app->request;
	if ($request->isPost)
	{
	    $post = $request->post();
	    if ($model->login($post))
	    {
		$this->redirect(['default/index']);	
		Yii::$app->end();
            }
	}
	return $this->render('login', ['model'=>$model]);
    }

    public function actionLogout()
    {
	Yii::$app->session->removeAll();	
	if (!Yii::$app->session['admin']['isLogin'])
	{
	    $this->redirect(['public/login']); 
	    Yii::$app->end();
	}
	$this->goback();
    }

    public function actionSeekpassword()
    {
	$model = new Admin;
	$request = Yii::$app->request;	
	if ($request->isPost)
	{
	    $post = $request->post(); 
	    if($model->seekPassword($post))
	    {
		$timestamp = time();
		$adminuser = $post['Admin']['adminuser'];
		$token = $this->createToken($adminuser, $timestamp);
		$url = 'http://47.93.5.10/index.php?r=admin/public/changepassword&adminuser=' . $adminuser . '&timestamp=' . $timestamp . '&token='. $token;
		Yii::$app->mailer->compose('seekpass', ['url'=>$url, 'adminuser'=>$adminuser])
		    ->setFrom('sijunjiede163@163.com')
	 	    ->setTo($post['Admin']['adminemail'])
		    ->setSubject("修改密码")
		    ->send();

		Yii::$app->session->setFlash("info", "邮件已发送");	
	    }
	}
	return $this->render('seekpassword', ['model'=>$model]);	
    }

    public function actionChangepassword()
    {
	$request = Yii::$app->request;
	$get = $request->get();
	$model = new Admin;
	$adminuser = $get['adminuser'];
	$timestamp = $get['timestamp'];
	$token = $get['token'];
	$mytoken = $this->createToken($adminuser, $timestamp);
	if ($token != $mytoken /*&& time()-$timestamp>300*/) 
	{
	    $this->redirect(['public/login']);
	}

	if ($request->isPost)
	{
	    $post = $request->post();      
	    if ($model->changepassword($post))
	    {
		Yii::$app->session->setFlash("info", "密码修改成功");	
	    }
	}
	return $this->render('changepassword', ['model'=>$model, 'adminuser'=>$get['adminuser']]);	
    }
	

    private function createToken($user, $time)
    {
	return md5($user . $time . $this->token);	
    } 
}

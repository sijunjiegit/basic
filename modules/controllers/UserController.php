<?php

namespace app\modules\controllers;

use yii\web\Controller;
use app\models\User;
use yii\data\Pagination;
use yii\web\Request;
use Yii;

class UserController extends Controller
{
    public $layout = false;
    public function actionUsers()
    {
	$query = User::find()->joinWith('profile');
	$count = $query->count();
	$pagination = new Pagination(['totalCount' => $count]);
 	$users = $query->offset($pagination->offset)->limit($pagination->limit)->all();	
        return $this->render('users', ['users' => $users, 'pagination' => $pagination]);       		
    }

    public function actionReg()
    {
	$model = new User;
	$request = new Request(); 
	if ($request->isPost)
	{
	    $post = $request->post();
	    $post['User']['userpass'] = md5($post['User']['userpass']);
	    $post['User']['confirmuserpass'] = md5($post['User']['confirmuserpass']);
	    if ($model->reg($post))
	    {
		Yii::$app->session->setFlash('info', '添加成功');
	    }
	    else
	    {
		Yii::$app->session->setFlash('info', '添加失败');
	    }
	}
	$model->userpass = $model->confirmuserpass = '';
        return $this->render('reg', ['model'=>$model]);		
    } 
}

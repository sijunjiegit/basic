<?php

namespace app\modules\controllers;

use yii\web\Controller;
use app\modules\models\Admin;
use yii\data\Pagination;
use Yii;

class ManageController extends Controller
{
    public $layout = false;
    public function actionManagers()
    {
	$model = new Admin;
        $query = $model::find(); 
	$count = $query->count();
	$pageSize = Yii::$app->params['pageSize']['manage'];
	$pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
	$userList = $query->offset($pagination->offset)->limit($pagination->limit)->all();
	return $this->render('managers', ['userList' => $userList, 'pagination'=>$pagination]);	
    }

    public function actionReg()
    {
	$model = new Admin;
	$request = Yii::$app->request;
	if ($request->isPost)
	{
	    $post = $request->post();
	    $post['Admin']['adminpass'] = md5($post['Admin']['adminpass']);
	    $post['Admin']['confirmadminpass'] = md5($post['Admin']['confirmadminpass']);
	    if ($model->reg($post))
	    {
		Yii::$app->session->setFlash('info', '创建成功');		 
	    }
	    else
	    {
		Yii::$app->session->setFlash('info', '创建失败');	
	    }
	}
	$model->adminpass = '';
	$model->confirmadminpass = '';
	return $this->render('reg', ['model'=>$model]);	
    }

    public function actionDel()
    {
	$model = new Admin;		
	$adminid = Yii::$app->request->get('adminid');
	if ($model->deleteAll("adminid = :adminid", [':adminid' => $adminid]))
	{
	    Yii::$app->session->setFlash('info', '删除成功'); 
	}
	else
	{
	    Yii::$app->session->setFlash('info', '删除失败'); 
	}
	$this->redirect(['manage/managers']);
    }
    
    public function actionChangeemail()
    {
	$model = Admin::find()->where("adminuser = :adminuser",[":adminuser"=>Yii::$app->session['admin']['adminuser']])->one();
	$request = Yii::$app->request;
	if ($request->isPost)
	{
	    $post = $request->post(); 
	    if ($model->changeEmail($post))
	    {
		Yii::$app->session->setFlash('info', '修改成功');			
	    }
	    else
	    {
		Yii::$app->session->setFlash('info', '修改失败');			
	    }
	}
	return $this->render('changeemail', ['model'=>$model]);
    }

    public function actionChangepass()
    {
	$model = Admin::find()->where("adminuser = :adminuser",[":adminuser"=>Yii::$app->session['admin']['adminuser']])->one();
	$request = Yii::$app->request;
	if ($request->isPost)
	{
	    $post = $request->post();
	    $post['Admin']['adminpass'] = md5($post['Admin']['adminpass']);
	    $post['Admin']['confirmadminpass'] = md5($post['Admin']['confirmadminpass']);
	    if ($model->changePass($post))
	    {
		Yii::$app->session->setFlash('info', '修改成功');
	    }
	    else
	    {
		Yii::$app->session->setFlash('info', '修改失败');
   	    }
	}
	$model->adminpass = '';
	$model->confirmadminpass = '';
	return $this->render('changepass', ['model' => $model]);
    }
}

<?php

namespace app\modules\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
	$this->layout = 'layout1';
	$this->checkLogin();
        return $this->render('index');
    }

    private function checkLogin()
    {
	$session = Yii::$app->session;		
	if ($session['admin']['isLogin'] == 1)
	{
	    return true;
	}
	else
	{
	   $this->redirect(['public/login']); 
	}
    }
}

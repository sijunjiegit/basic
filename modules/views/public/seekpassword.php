<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\html;
?>
<!DOCTYPE html>
<html class="login-bg">
    
    <head>
        <title>慕课商城 - 后台管理</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- bootstrap -->
        <link href="assets/admin/css/bootstrap/bootstrap.css" rel="stylesheet" />
        <link href="assets/admin/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
        <link href="assets/admin/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />
        <!-- global styles -->
        <link rel="stylesheet" type="text/css" href="assets/admin/css/layout.css" />
        <link rel="stylesheet" type="text/css" href="assets/admin/css/elements.css" />
        <link rel="stylesheet" type="text/css" href="assets/admin/css/icons.css" />
        <!-- libraries -->
        <link rel="stylesheet" type="text/css" href="assets/admin/css/lib/font-awesome.css" />
        <!-- this page specific styles -->
        <link rel="stylesheet" href="assets/admin/css/compiled/signin.css" type="text/css" media="screen" />
        <!-- open sans font -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
    
    <body>
        <div class="row-fluid login-wrapper">
            <a class="brand" href="index.html"></a>
	    <?php $form = ActiveForm::begin([
		'id' => 'w0000',
		'fieldConfig' =>
		[ 
		    'template' => '{error}{input}'
		],
		'options' => [
		]
	    ]);?>
                <div class="span4 box">
                    <div class="content-wrap">
                        <h6>徽众商城 - 找回密码</h6>
			<?php if (Yii::$app->session->hasFlash('info')) echo Yii::$app->session->getFlash('info');?>
                        <div class="form-group field-admin-adminuser">
                            <p class="help-block help-block-error"></p>
			    <?= $form->field($model, 'adminuser')->textInput(["class" => "span12", "placeholder" => "管理员账号"]); ?>
                        <div class="form-group field-admin-adminemail">
                            <p class="help-block help-block-error"></p>
			    <?= $form->field($model, 'adminemail')->textInput(["class" => "span12", "placeholder" => "管理员电子邮箱"]); ?>
                        <a href="/index.php?r=admin%2Fpublic%2Flogin" class="forgot">返回登录</a>
			<?= Html::submitButton("找回密码", ['class'=>'btn-glow primary login']); ?>	
                </div>
	    <?php ActiveForm::end();?>
        </div>
        <!-- scripts -->
        <script src="assets/admin/js/jquery-latest.js"></script>
        <script src="assets/admin/js/bootstrap.min.js"></script>
        <script src="assets/admin/js/theme.js"></script>
        <!-- pre load bg imgs -->
        <script type="text/javascript">$(function() {
                // bg switcher
                var $btns = $(".bg-switch .bg");
                $btns.click(function(e) {
                    e.preventDefault();
                    $btns.removeClass("active");
                    $(this).addClass("active");
                    var bg = $(this).data("img");

                    $("html").css("background-image", "url('img/bgs/" + bg + "')");
                });

            });</script>
    </body>

</html>

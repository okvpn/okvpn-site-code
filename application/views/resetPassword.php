<!DOCTYPE html>
<html>
<head>
    <title>OkVPN - Сброс пароля</title>

    <link href="<?=URL::base()?>public/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=URL::base()?>public/css/cover.css" rel="stylesheet">

    <link rel="shortcut icon" href="<?=URL::base()?>public/img/favicon.ico" type="image/png">
    <script src="<?=URL::base()?>public/js/jquery.min.js"></script>
    <script src="<?=URL::base()?>public/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="zero-20"></div>
    <nav class="navbar navbar-default">
        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                </button>
                <a class="navbar-brand" href="<?=URL::base()?>">OkVPN</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="<?=URL::base()?>faq">FAQ</a></li>
                    <li><a href="<?=URL::base()?>guide">Подключение</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?=URL::base()?>profile">Статистика</a></li>
                            <li><a href="#">Оплатить доступ</a></li>
                            <li><a href="<?=URL::base()?>profile/settings">Настройки</a></li>
                            <li><a href="<?=URL::base()?>profile/viewvpn">Создать VPN</a></li>
                            <li><a href="<?=URL::base()?>user/logout">Выйти</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="message"></div>
    <div class="zero-10"></div>
    <h1>Сброс пароля</h1>
    <div class="zero-50"></div>

    <div class="row">

        <div class="col-md-4 col-md-offset-4">
            <form method="post" id="form" action="<?php echo URL::base(); ?>user/setnewpassword">
                <input type="hidden" name="token" value="<?php echo $token?>">
                <div class="form-group">
                    <label>Новый пароль</label>
                    <input type="password" class="form-control" name="password"  placeholder="Новый пароль">
                </div>
                <div class="form-group">
                    <label>Подтверждение</label>
                    <input type="password" class="form-control" name="confirm" placeholder="Подтверждение">
                </div>
                <button type="submit" class="btn btn-green">Сбросить</button>
            </form>
        </div>
    </div>

</div>
<div id="modal" class="modal fade" >

</div>
</body>
<script type="text/javascript">
    var form = $('#form');
    form.submit(function (event) {
        event.preventDefault();
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function (json) {
                if (! json.error) {
                    $('.message').load('<?=URL::base()?>public/ajax/success.html',function(){
                        $('.alert').append('Пароль сброшен<br>');
                    });
                } else {
                    $('.message').load('<?=URL::base()?>public/ajax/warming.html',function(){
                        $('.alert').append(json.message + '<br>');
                    });
                }
            }
        });
    });
</script>
</html>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OkVPN - Подключение</title>

    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/cover.css" rel="stylesheet">
    <link rel="shortcut icon" href="public/img/favicon.ico" type="image/png">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <script src="public/js/jquery.min.js"></script>
    <script async src="public/js/bootstrap.min.js"></script>

  <script type="text/javascript">
    var system;
    $.ajax({
        url:"<?=URL::base()?>ajax/api",
        cache: false,
        success:function(json) {
          system = json;
          console.log(json);
        }
      });
  </script>
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
            <li class="active"><a href="<?=URL::base()?>guide">Подключение</a></li>
            <li><a href="<?=URL::base()?>faq">FAQ</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          <?php if ($auth): ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?=URL::base()?>profile">Статистика</a></li>
                <li><a href="#">Оплатить доступ</a></li>
                <li><a href="<?=URL::base()?>profile/settings">Настройки</a></li>
                <li><a href="<?=URL::base()?>profile/create">Создать VPN</a></li>
                <li><a href="<?=URL::base()?>user/logout">Выйти</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li style="cursor:pointer"><a id="signin">Войти</a></li>
          <?php endif ?>
          </ul>
        </div>
      </div>
    </nav>

  <div class ="color-white faq-main">

    <div style="margin:20px;">
      <img src="public/img/logo2.png"> 
    </div>
    <div class="faq-q font-open">

      <h2>Установка клиента</h2>
      <div style ="padding-left:50px; padding-right: 30px">
          <b>Windows</b> скачайте и установите <a href="https://openvpn.net/index.php/open-source/downloads.html">OpenVPN Windows Installer </a> <br>    
          <b>Android</b> <a href="https://play.google.com/store/apps/details?id=net.openvpn.openvpn">OpenVPN Connect </a> <br>  
          <b>Linux</b> используйте версию из репрезетория <code>sudo apt-get install openvpn</code>(Ubuntu)<br>  
          <div class="zero-20"></div>
      </div>
      <h2>Подключение</h2>
      <p style ="padding-left:50px; padding-right: 30px">
        После регистрации и подтверждения почты  вы можете активировать VPN доступ, для этого на 
        <a href="/profile/create">странице выбора VPN</a> выберите страну размещения сервера.
        После этого Вам на почту придет конфигурационный файл с ключем для подключения к OpenVPN серверу. 
        Если письмо не пришло, попробуйте <a href="<?php echo URL::base() ?>profile/settings">удалить</a>
        имеющиеся подключение и заново активировать новое или написать в <a href=mailto:team@okvpn.org>тех. поддержку</a>
      </p>
      <li><b>Window</b></li>
      <p style ="padding-left:50px; padding-right: 30px">
          Импортируйте его в <code> C:\Program Files\OpenVPN\config\ </code> 
          После этого запустите от имени администратора OpenVPN GUI. 
          Чтобы программа всегда запускалась от имени администратора выберите в свойствах на вкладке 
          совместимость - запуск от имени администратора 
          <div style="margin: 0 auto; padding: 20px 100px">
            <img src="<?php echo URL::base() ?>public/img/st3.png" class="img-thumbnail">
          </div>
      </p>
      <li><b  >Linux</b></li> 
        <p style ="padding-left:50px; padding-right: 30px">Запустить Openvpn можно через консоль <code>sudo openvpn --config /путь/имя_файла.ovpn</code>
        Например <code>sudo openvpn --config /etc/openvpn/af017e63.ovpn</code>
        </p>
        <h4>Если у Вас возникнут вопросы, не стесняйтесь, обращайтесь в тех. поддержку 
        <a href="mailto:team@okvpn.org">team@okvpn.org</a></h4>
      <nav>
        <ul class="pager">
          <li>
            <a href="<?=URL::base()?>">
              <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> На главную
            </a>
          </li>
          <li>
            <a href="<?=URL::base()?>faq">
              FAQ <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
            </a>
          </li>
        </ul>

      </nav>
    </div>    
  </div>
<div class ="zero-120"></div>
</div>

<!-- footer -->
<?php include "footer.php"; ?>


<script type="text/javascript">
  
$("#signin").click(function(event){
  if (system.auth) {
    window.location.replace(system.profile);
  } else {
    $("#modal").load('<?=URL::base()?>public/ajax/sign-in.html');
    $("#modal").modal('show');    
  }

});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
</body>
</html>
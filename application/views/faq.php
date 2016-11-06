<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OkVPN - FAQ</title>

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

  <?php include 'include/navbar.php'?>
  <div class ="color-white faq-main">

    <div style="margin:20px;">
      <img src="public/img/logo2.png"> 
    </div>
    <div class="faq-q font-open">
      <h1 style="color:#7D7676;font-style:italic;text-align:center">FAQ</h1>

      <h2>
        <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
        Мне не удалось подключиться?
      </h2>  
      <p style ="padding-left:50px;">Не стесняйтесь, обращайтесь в тех. поддержку
      <a href="mailto:team@okvpn.org">team@okvpn.org</a> </p>

      <h2>
        <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
        Какие порты вы используете?
      </h2>
      <p style ="padding-left:50px;">На данный момент мы функционируем в тестовом режиме, 
      поэтому доступно только подключение по 1194/UPD.</p>

      <h2>
        <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
        Я не удовлетворен качеством сервиса, можно ли верернуть деньги?
      </h2>
      <p style ="padding-left:50px;">Да можно, напишите об этом тех. поддержку.</p>

      <h2>
        <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
        В каких странах у вас есть серверы?
      </h2>
      <p style ="padding-left:50px;">Пока доступны для подключения серверы в Германии, Нидерландах, 
      Румынии и Великобритании.
      </p>

      <h2>
        <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
        Разрешены ли множественные подключения?
      </h2>
      <p style ="padding-left:50px;">
        Нет. Одновременно разрешено только одно подключение.
      </p>
      <h2>
        <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
        Вы ведете лог журналы?
      </h2>
      <p style ="padding-left:50px;">
        Нет. Мы не записываем Ваши действия, не храним Ваши IP при регистрации. Вся информация 
        о Вас это - адрес электроной почты.
      </p>

      <h2>
        <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
        Можно ли оплатить любой срок VPN?
      </h2>
      <p style ="padding-left:50px;">
        Да можно, но минимальная сумма пополнения ограничена минимальной суммой транзакции 
        <span class="faq-tooltip"data-toggle="tooltip" data-placement="top" title="Примерно 0.22 USD">0.0005 BTC</span>
      </p>
      <h2>
        <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
        Что нельзя делать?
      </h2>
      <p style ="padding-left:50px;">
        Категорически запрещены все действия, котрые приводят к появлению абузы:
        <li style ="padding-left:50px;">Загрузка и скачивание детской порнографии</li>
        <li style ="padding-left:50px;">Массовая рассылка писем (спама)</li>
        <li style ="padding-left:50px;">Любые действия, запрещенные в стране размещения сервера</li>
      </p>

      <div class="zero-20"></div>

      <nav>
        <ul class="pager">
          <li>
            <a href="<?=URL::base()?>">
              <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> На главную
            </a>
          </li>
          <li>
            <a href="<?=URL::base()?>guide">
              Подключение<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
            </a>
          </li>
        </ul>
      </nav>

      <div class="zero-20"></div>
      <div style="border-width: 2px;border-bottom-style: groove;"> </div>
      <div class="zero-20"></div>
<!--       <p style="font-size: 0.8em">
        <i> Ребята! Нам нужно продвигаться, а бюджета нет! Скинте ссылку друзьям или сделайте <a href="https://www.coinbase.com/jurasikt">пожертвование</a></i>
      </p>
 -->    </div>    
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

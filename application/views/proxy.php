<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OkVPN - Список прокси серверов</title>

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
            <li class="active"><a href="<?=URL::base()?>guide">Прокси лист</a></li>
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

      <h1 style="text-align: center;">Список бесплатных прокси </h1>
      <div class="zero-20"></div>
      <div style ="padding-left:50px; padding-right: 30px">
        Данное api предоставляется с целью автоматизирования процесса парсинга,
        накрутки голосов и тд на различных сайтах, где есть ограничение на количество запросов с одного ip.
        Предоставляется абсолютно бесплатно и без ограничений.
      </div>
      <h2>Использование:</h2>
      <div style ="padding-left:50px; padding-right: 30px">
        Адрес для запросов:
        <code>https://okvpn.org/api/v2/proxy/&ltformat&gt </code> метод <code>GET</code> <br>
        Список параметров:
          <div style ="padding-left:20px; padding-right: 30px; margin: 10px;border-radius: 10px; background: #F5EDED">
            <li><code>format</code> - Формат ответа, допустимые значения <code>json, xml, raw</code> по умолчанию <code>json</code></li>
            <li><code>code_country</code> - двузначный код страны, если надо указать несколько, то через запятую
             <code> ,</code> например <code>UA,RU,ES</code></li>
            <li><code>limit</code> - кол-во отображаемых прокси (максимум 1000)</li>
            <li><code>uptime</code> - минимальный uptime в %</li>
            <li><code>waiting</code> - максимальное время ответа в мсек.</li>
          </div>
        Возвращаемое значение (JSON): 
          <div style ="padding-left:20px; padding-right: 30px; margin: 10px;border-radius: 10px">
            <b><pre>
[
  {
    "ip": "94.77.161.176:8080",
    "country": "Russian Federation",
    "waiting": 7.94,
    "uptime": 100,
    "lastcheck": "2016-04-10 03:04:31"
  }          
]
            </pre></b>
          </div>
        XML: 
          <div style ="padding-left:20px; padding-right: 30px; margin: 10px;border-radius: 10px">
            <b><pre>
&ltdata&gt
  &ltitem0&gt
    &ltip&gt201.249.88.202:3128&lt/ip&gt
    &ltcountry&gtVenezuela&lt/country&gt
    &ltwaiting&gt6.103&lt/waiting&gt
    &ltuptime&gt83.3&lt/uptime&gt
    &ltlastcheck&gt2016-04-10 11:24:41&lt/lastcheck&gt
  &lt/item0&gt
&lt/data&gt
            </pre></b>
          </div>

        RAW: 
          <div style ="padding-left:20px; padding-right: 30px; margin: 10px;border-radius: 10px">
            <b><pre>
42.118.216.221:3128
177.91.23.221:8080
178.155.14.10:8080
52.88.61.115:8083
176.31.117.175:80
52.90.240.236:8083
125.212.219.221:3128
177.101.181.169:80
            </pre></b>
          </div>
        Описание полей:
          <div style ="padding-left:20px; padding-right: 30px; margin: 10px;border-radius: 10px; background: #F5EDED">
            <li><code>ip</code>  Ipv4 и порт для подключения
            <li><code>country</code> - страна размещения</li>
            <li><code>waiting</code> - время ответа в сек.</li>
            <li><code>uptime</code> - uptime в %.</li>
            <li><code>lastcheck</code> - время последней проверки в формате YYYY-MM-DD HH:MM:SS +03 UTC</li>
          </div>
        Пример использования: <a href="https://okvpn.org/api/v2/proxy/raw/?code_country=RU,US&limit=10&uptime=25">https://okvpn.org/api/v2/proxy/raw?code_country=RU,US&limit=10&uptime=25</a>

      </div>
      <div class="zero-20"></div>
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
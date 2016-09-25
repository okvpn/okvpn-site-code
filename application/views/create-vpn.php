<!DOCTYPE html>
<html>
  <head>
    <title>OkVPN - CreateVPN</title>

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
                <li><a href="<?=URL::base()?>profile/create">Создать VPN</a></li>
                <li><a href="<?=URL::base()?>user/logout">Выйти</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="alert-box"> </div>
    <div class="zero-50"></div>
      <h1>Выбор сервера</h1>
      
      <?php for ($i=0; $i < count($vpn)/2; $i++):  ?>
      <div class="row">
      <?php if (isset($vpn[2*$i])): ?>
        <div class="col-md-3 col-md-offset-1 vpn-block">

          <h1><?php echo $vpn[2*$i]['title'] ?></h1>

          <img src="<?php echo URL::base().$vpn[2*$i]['img'] ?>" class="img-circle" height ="80px" width="160px">
          <p>Страна размещения: <em><?php echo $vpn[2*$i]['country'] ?></em></p>
          <p>Количество свободных мест: <em><?php echo $vpn[2*$i]['free'] ?></em></p>

          <button type="button" class="btn btn-defaul btn-c <?php echo ($vpn[2*$i]['free']==0)?'disabled':''?>">
            Выбрать
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            <input type= "hidden" value="<?php echo $vpn[2*$i]['id'] ?>">
          </button>
          <?php if ($vpn[2*$i]['speedtest'] != null ): ?>
            <a href="<?php echo $vpn[2*$i]['speedtest'] ?>" class="btn btn-defaul" target="_blank">
              Speed Test
            </a>
          <?php endif ?>
        </div>
      <?php endif ?>
      <?php if (isset($vpn[2*$i+1])): ?>

        <div class="col-md-3 col-md-offset-3 vpn-block">

          <h1><?php echo $vpn[2*$i+1]['title'] ?></h1>

          <img src="<?php echo URL::base().$vpn[2*$i+1]['img'] ?>" class="img-circle" height ="100px" width="220px">
          <p>Страна размещения: <em><?php echo $vpn[2*$i+1]['country'] ?></em></p>
          <p>Количество свободных мест: <em><?php echo $vpn[2*$i+1]['free'] ?></em></p>

          <button type="button" class="btn btn-defaul btn-c <?php echo ($vpn[2*$i+1]['free']==0)?'disabled':''?>">
            Выбрать
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            <input type= "hidden" value="<?php echo $vpn[2*$i+1]['id'] ?>">
          </button>
          <?php if ($vpn[2*$i+1]['speedtest'] != null ): ?>
            <a href="<?php echo $vpn[2*$i+1]['speedtest'] ?>" class="btn btn-defaul" target="_blank">
              Speed Test
            </a>
          <?php endif ?>
        </div>
        <?php endif ?>
      </div>
      <?php endfor ?>  
      <div class="zero-50"></div>
    </div>
    <div id="modal" class="modal fade" >
      
    </div>


    <script type="text/javascript">
    csrf = '<?php echo $csrf?>';
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
    $('.btn-c:not(.disabled)').click(function(){
      id = $(this).children('input').val();
      
    $("#modal").load('<?=URL::base()?>profile/getinfovpn/'+id+'?_=' + (new Date()).getTime());
    $("#modal").modal('show');

    });
    </script>
  </body>
</html>
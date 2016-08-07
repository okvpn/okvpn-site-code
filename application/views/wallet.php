<!DOCTYPE html>
<html>
<head>
  <title>Wallet</title>


  <link href="<?=URL::base()?>public/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=URL::base()?>public/css/cover.css" rel="stylesheet">
  <link rel="shortcut icon" href="<?=URL::base()?>public/img/favicon.ico" type="image/png">
  <script src="<?=URL::base()?>public/js/jquery.min.js"></script>
  <script src="<?=URL::base()?>public/js/bootstrap.min.js"></script>
  <!--  -->

</head>
<body>
<div class="container">
  <div class="zero-20"></div>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          </button>
          <a class="navbar-brand" href="<?=URL::base()?>">OkVpn</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="#">FAQ</a></li>
            <li><a href="<?=URL::base()?>about">About</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown active">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?=URL::base()?>profile">Statistics</a></li>
                <li><a href="<?=URL::base()?>profile/wallet">Wallet</a></li>
                <li><a href="<?=URL::base()?>profile/settings">Settings</a></li>
                <li><a href="#">VPN choose</a></li>
                <li><a href="#">Login Out</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  <div class="zero-50"></div>
  <div class ="row">
    <div class="col-md-4 col-md-offset-1">
      <div class="zero-20"></div>
      <img src="https://blockchain.info/qr?size=200&data=<?=$wallet?>">
    </div>
    <div class="col-md-5">
      <div class="wallet-box">
        <div style="text-align:center;color:#222">
          <b>Адрес биткоин кошелька для пополнения Вашего баланса</b>
        </div>
        <div class="zero-20"></div>
        <div class="input-group">
          <input type="text" id="wallet" class="form-control" value="<?=$wallet?>" disabled>
          <span class="input-group-btn" data-toggle="tooltip" data-placement="top" title="Копировать в буфер обмена">
            <button class="btn btn-copy" type="button"><span class="glyphicon glyphicon-copy" aria-hidden="true"></span>Copy</button>
          </span>
        </div>
        <div class="zero-20"></div>
        <p>Комиссия за пополнение: 0% (без учета комиссии в сети биткоин)</p>
        <p>Минимальная сумма пополнения: 0.0005 BTC</p>
        <p>Курс BTC/USD: <?=$btc?></p>
        <div class="zero-20"></div>
        <p style="font-size:12px">Зачисление происходит автоматически после распространения транзакции в сети</p>
      </div>
      
    </div>

  </div>
</div>

<script type="text/javascript">
$('.btn-copy').click(function(event){
  var $tmp = $('<input>');
  $("body").append($tmp);
  $tmp.val($('#wallet').val()).select();
  document.execCommand("copy");
  $tmp.remove();
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
</body>
</html>
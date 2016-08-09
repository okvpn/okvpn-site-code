<!DOCTYPE html>
<html>
<head>
  <title>OkVPN - settings</title>


  <link href="<?=URL::base()?>public/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=URL::base()?>public/css/cover.css" rel="stylesheet">
  <link href="<?=URL::base()?>public/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="<?=URL::base()?>public/img/favicon.ico" type="image/png">
  <script src="<?=URL::base()?>public/js/jquery.min.js"></script>
  <script src="<?=URL::base()?>public/js/bootstrap.min.js"></script>
  <script src="<?=URL::base()?>public/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript">
      $('.datepicker').datepicker({
          format: 'yyyy-mm-dd'
      })
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
            <li><a href="<?=URL::base()?>faq">FAQ</a></li>
            <li><a href="<?=URL::base()?>guides">Подключение</a></li>
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
<div class="settings-title">
  <h2>Настройки</h2>
</div>  
<div class ="zero-20"></div>
<div class="row">
  <div class="settings-warming"></div>
  <div class ="col-md-6 col-md-offset-3">
    <div class="input-group">
      <input type="text" class="form-control" value ="<?=$email?>">
      <span class="input-group-addon" id="sizing-addon2">&nbsp&nbsp&nbspНовый Email </span>
    </div>
    <div class ="zero-20"></div>
    <div class ="input-group">
      <input type="password" class="form-control">
      <span class="input-group-addon" id="sizing-addon2">Новый пароль</span>
    </div>
    <div class ="zero-20"></div>
    <div class ="input-group">
      <input type="password" class="form-control"> 
      <span class="input-group-addon" id="sizing-addon2">&nbsp&nbsp&nbsp&nbsp&nbsp Re пароль</span>
    </div>
    <div class="zero-20"></div>
    <div class="row" style="display: none">
      <div class="col-sm-6">
        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
          <input type="text" class="form-control">
          <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>      
      </div>
      <div class="col-sm-6">
        <span data-toggle="tooltip" data-placement="top" title="Напрмер, если вам надо удалить аккаунта,
        по каким-то причинам, в оперделенное время, вы можете поставить таймер удаления. Обращаем внимание, что после удаления
        оставшиеся средства будут потерены, так как информация о Ваших транзакциях и адреса биткоин кошельков будет полностью
         уничтожена">
          Дата отложенного удаления аккаунта*
        </span>
      </div>
    </div>

    <h2>Активные VPN подключения</h2>
    <table class="table table-bordered">
      <tr>
        <td><b>ID</b></td>
        <td><b>Выбрать</b></td>
        <td><b>Имя</b><br> <i style="font-size: 0.7em">(Отбражается при <br>подключении к VPN)</i></td>
        <td><b>Хост</b></td>
        <td><b>Размещение</b></td>
      </tr>

      <?php foreach ($active_vpn as $item): ?>
      <tr>
        <td><?php echo $item['id']?></td>
        <td><input type="checkbox" value = "<?php echo $item['id'] ?>">Выбрать</td>
        <td><?php echo $item['name']?></td>
        <td><?php echo $item['host']?></td>
        <td><img src="<?php echo URL::base() ?><?php echo $item['icon']?>" class="img-rounded" height= "28" width= "42">&nbsp&nbsp&nbsp&nbsp<?php echo $item['location']?></td>
      </tr>
    <?php endforeach; ?>
    </table>

    <div class ="input-group">
      <button id ="del-vpn" type="button" class="btn btn-danger"style="margin:10px">Удалить VPN</button>
        <span data-toggle="tooltip" data-placement="top" title="Данная процедура требует перезапуска openvpn сервера, поэтому удаленый Вами сервер будет достумен еще максимум 24 часа">
          Удаление выбранное*
        </span>
    </div>

    <div class="zero-50"></div>
    <div class="row">
      <div class ="col-sm-8 settings-delete">
        <p style="font-size:11px;text-align:justify;color:#777">
          Если вы удалите аккаунт и на нем будут оставаться средства,
          то они будут безвозвратно потеряны, так как вся информация о вашем аккаунте
          полностью удаляется. Для того что бы вывести оставшиеся средства вам необходимо
          написать в тех поддержку указав, адрес биткоин кошелька куда выводить и ваш логин(email).
          Запрос на возврат обрабатывается в ручном режиме, и после проверки вам будет
          возвращена оставшиеся сумма. Как правило это занимает не более суток.
        </p>
      </div>
      <div class="col-sm-4">
      <div class="checkbox" style="font-size:12px">
        <label>
          <input type="checkbox" id="delete">
          Я понимаю, что эти действия не возможно отменить 
        </label>
      </div>
        <div class="zero-20"></div>
        <button id ="del-acc" type="button" class="btn btn-danger">Удалить учетную запись </button>
      </div>
    </div>
    <div class="zero-50"></div>
    <div class="row">
      <div class="col-sm-6">
        <button type="button" class="btn btn-success"style="margin:10px">Приметить</button>
        <button type="button" class="btn btn-primary"style="margin:10px">Отмена </button>
      </div>
    </div>
  </div>
</div>

</div>
<script type="text/javascript">
var csrftoken = "<?php echo $csrf ?>";
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

$("#del-acc").click(function(event){
  scroll(0,0);
  if ($('#delete').prop('checked')) {
    if (confirm('Вы уверены, что хотите удалить аккаунт?')) {
      $.post("<?php echo URL::base() ?>user/delete",{action:"delete",csrf:csrftoken},function(json){
        if (json.error) {
          $('.alert').append('Неизвестная ошибка<br>');
        } else {
          window.location.replace("<?php echo URL::base()?>");
        }
      });
    }
    
  } else {
    $('.settings-warming').load('<?=URL::base()?>public/ajax/warming.html',function(){
      $('.alert').append('Выберете, checkbox с тем, что вы согласны с условиями<br>');
    });
  }
});

$('#del-vpn').click(function(e){
  scroll(0,0);
  var items = $('table input[type=checkbox]');
  var arr = [];
  for (var i = 0; i < items.length; i++) {
    if (items[i].checked) {
      arr.push(items[i].value);
    };
  };
  if (arr.length > 0 && confirm('Вы уверены, что хотите удалить выбранные vpn?')) {
    arr = btoa(JSON.stringify(arr));

    $.post('<?php echo URL::base() ?>user/vpndelete',{csrf:csrftoken,host:arr}, function(res){
      
      if (res.error) {
        $('.settings-warming').load('<?=URL::base()?>public/ajax/warming.html', function(){
          $('.alert').append('Не чего не выбрано <br>');
        });

      } else {

        for (var i = 0; i < items.length; i++) {
          if (items[i].checked) {
            it = items[i];
            it.parentElement.parentElement.remove();
          }
        };
        $('.settings-warming').load('<?=URL::base()?>public/ajax/success.html',function(){
          $('.alert').append('Выполнено<br>');
        });
      }
    });
  } else {
    if (arr.length == 0) {
      $('.settings-warming').load('<?=URL::base()?>public/ajax/warming.html',function(){
        $('.alert').append('Не чего не выбрано <br>');
      });      
    }

  }
  
});
</script>

</body>
</html>
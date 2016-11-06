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
        <?php if (!isset($auth) || ($auth)): ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="<?=URL::base()?>profile">Статистика</a></li>
              <li><a href="#">Оплатить доступ</a></li>
              <li><a href="<?=URL::base()?>profile/settings">Настройки</a></li>
              <li><a href="<?=URL::base()?>profile/viewvpn">Создать VPN</a></li>
              <li><a href="<?=URL::base()?>user/logout">Выйти</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li style="cursor:pointer"><a id="signin" href="/">Войти</a></li>
          <script> </script>
        <?php endif ?>
      </ul>
    </div>
  </div>
</nav>

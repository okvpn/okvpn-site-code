<header class='masthead'>
  <div class='brand-container'>
    <a href='<?php echo URL::base() ?>'>
      <span class='brand-initials'>Ok</span>
      <span class='brand-name'>OkVPN только VPN доступ</span>
    </a>
  </div>
  <nav>
    <div class='nav-container'>
      <div>
        <input id='slider1' name='slider1' type='checkbox'>
        <label class='slide has-child' for='slider1'>
          <span class='element'>F</span>
          <span class='name'>FAQ</span>
        </label>
        <div class='child-menu'>
          <a href='<?php echo URL::base() ?>faq'>Часто задаваемые вопросы</a>
          <a href='<?php echo URL::base() ?>guide'>Подключение</a>
          <!-- <a href='<?php echo URL::base() ?>content'>Личный VPN сервер</a> -->
        </div>
      </div>
      <div>
        <input id='slider2' name='slider2' type='checkbox'>
        <label class='slide has-child' for='slider2'>
          <span class='element'>P</span>
          <span class='name'>Profile</span>
        </label>
        <div class='child-menu'>
          <a href='<?php echo  URL::base()?>profile/settings'>Настройки</a>
          <a href='#'>Оплатить</a>
          <a href='<?php echo  URL::base()?>profile/create'>Создать VPN</a>
          <a href='<?php echo  URL::base()?>profile'>Статистика</a>
          <a href='<?php echo  URL::base()?>user/logout'>Выйти</a>
        </div>
      </div>
      <div>
        <a class='slide' href='#contact'>
          <span class='element'>C</span>
          <span class='name'>Contact</span>
        </a>
      </div>
    </div>
  </nav>
  <div class='social-container'>
    <span>
      <a class='social-roll github' href='#'></a>
    </span>
    <span>
      <a class='social-roll twitter' href='#'></a>
    </span>
    <span>
      <a class='social-roll linkedin' href='#'></a>
    </span>
    <span>
      <a class='social-roll rss' href='#'></a>
    </span>
  </div>
  <div>
  </div>
</header>
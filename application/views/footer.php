  <footer class="footer-std">
    <a name="contact"></a>
    <div class="container">
    <div class="zero-20"></div>
      <div class="row">
        <div class="col-md-3">
          <div class ="col-md-8"><h3>OkVPN</h3></div>
          <div class ="col-md-8">
            <a href="<?php echo URL::base() ?>"><p>Главная</p></a>
            <a href="<?php echo URL::base() ?>faq"><p>FAQ</p></a>
            <a href="<?php echo URL::base() ?>guide"><p>Подключение</p></a>
            <a href="<?php echo URL::base() ?>proxy"><p>Прокси лист</p></a>            
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-8"><h3>Кабинет</h3></div>
          <div class="col-md-8">
            <a href="<?php echo URL::base() ?>profile"><p>Статистика</p></a>
            <a href="#"><p>Оплатить доступ</p></a>
            <a href="<?php echo URL::base() ?>profile/create"><p>Создать VPN</p></a>
            <a href="<?php echo URL::base() ?>profile/settings"><p>Настройки</p></a>            
          </div>

        </div>

        <div class="col-md-3">
          <div class="col-md-8"><h3>Разработчикам</h3></div>
          <div class="col-md-8">
            <a href="#"><p>Документация</p></a>
            <a href="#"><p>Public Api</p></a>
            <a href="#"><p>Private Api</p></a>            
          </div>

        </div>
        <div class="col-md-3">
          <div class="col-sm-8"><h3>Контакты</h3></div>
          <div class="col-sm-8"><h4><a href="mailto:team@okvpn.org">team@okvpn.org</a></h4></div>
          
        </div>
      </div>
      <div class ="zero-20"></div>
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <p>Copyright © OkVPN, 2016<a href=""> Privacy Policy</a></p>
        </div>
        <div class="col-md-1 col-md-offset-2">
          <img src="<?php echo URL::base()?>public/img/bitcoin.png" class="img-rounded" width ="90px" hight ="60px">
        </div>
      </div>
      <div class="zero-20"></div>
    </div>
  </footer>

<?php if (MODE == 'server'): ?>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-73445433-1', 'auto');
    ga('send', 'pageview');

  </script>

<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter36031910 = new Ya.Metrika({ id:36031910, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/36031910" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->  

<?php endif ?>
<div class="modal-dialog" role="document">
  <div class="modal-content">  
    <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h3 class="headers"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Характеристики:</h3>
      <div class="loader"></div>
      <div style="text-align: left; margin: 20px auto; padding-left: 35px" class="vpn-content">
        <?php echo $network; ?> <br>
      </div>
      <p style="font-size: 0.8em; text-align: left;">*Действительная скортость VPN подключения будет ниже из-за потерь</p>
      <div style="text-align: left;margin: 20px">
        <a class="btn btn-primary" href="<?php echo $link; ?>" target="_blank">Детально <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a>
        <a class="btn btn-primary act" href="#" style="margin-left: 25px">Выбрать <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></a>        

      </div>

    </div>
  </div>
</div>
<style type="text/css">


.loader {

  margin: 0px auto;
  width: 80px;
}

.circular {
  animation: rotate 2s linear infinite;
  height: 100%;
  transform-origin: center center;
  width: 100%;

  top: 0; bottom: 0; left: 0; right: 0;
  margin: auto;
}


.path {
  stroke-dasharray: 1,200;
  stroke-dashoffset: 0;
  animation: 
   dash 1.5s ease-in-out infinite,
   color 6s ease-in-out infinite
  ;
  stroke-linecap: round;
}

@keyframes rotate{
 100%{
  transform: rotate(360deg);
 }
}
@keyframes dash{
 0%{
  stroke-dasharray: 1,200;
  stroke-dashoffset: 0;
 }
 50%{
  stroke-dasharray: 89,200;
  stroke-dashoffset: -35px;
 }
 100%{
  stroke-dasharray: 89,200;
  stroke-dashoffset: -124px;
 }
}
@keyframes color{
  100%, 0%{
    stroke: #008744;
  }
  40%{
    stroke: #0057e7;
  }
  66%{
    stroke: #d62d20;
  }
  80%, 90%{
    stroke: #008744;
  }
}
</style>

<script type="text/javascript">
var loader = '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>';
$('.act').click(function(){
  if (confirm("Вы уверены, что хотите активировать VPN доступ?")) {
    $(".loader").html(loader);
    $('.vpn-content').html('');
    setTimeout(function(){
        $.post('<?php echo URL::base()."user/createvpn"?>',{"id":"<?php echo $id ?>","csrf":"<?php echo $csrf ?>"},
          function(json){
            $("#modal").modal('hide');
            scroll(0,0);
            if (!json.error) {
              $('.alert-box').load('<?=URL::base()?>public/ajax/success.html',function(){
                $('.alert').append('VPN доступ успешно активирован. На ваш адрес электронной почты отправлено письмо с .ovpn файлом   <br>');
              });
            } else {
              $('.alert-box').load('<?=URL::base()?>public/ajax/warming.html',function(){
                $('.alert').append('Opps..'+json.message+'<br>');
              });
            }
          });
      }, 1500);
  }
});
</script>
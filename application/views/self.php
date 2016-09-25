<!DOCTYPE html>
<html>
<head>
  <title>OkVPN - личный VPN</title>
  <?php include __DIR__.'/include/head.php' ?>
</head>
<body>
  <?php include __DIR__.'/include/navbar.php' ?>
  <section class="container">
  <div class="zero-120"></div>
  <div class ="color-white faq-main">

      
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
  </section>
  
<?php include __DIR__."/include/footer.php"; ?>
<?php include __DIR__."/include/ga.php"; ?>
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



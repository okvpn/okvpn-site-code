<!DOCTYPE html>
<html>
  <head>
    <title>OkVPN - Оплатить доступ</title>

    <link href="<?=URL::base()?>public/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=URL::base()?>public/css/cover.css" rel="stylesheet">

    <link rel="shortcut icon" href="<?=URL::base()?>public/img/favicon.ico" type="image/png">
    <script src="<?=URL::base()?>public/js/jquery.min.js"></script>
    <script src="<?=URL::base()?>public/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
    <div class="zero-20"></div>
      <?php include 'include/navbar.php'?>
    
    <div class="zero-10"></div>
      <h1>Пополнить счет через bitpay</h1>
    <div class="zero-50"></div>


    <div class="row">
      <div class="col-md-6" style="text-align: left">
        <address>
          <strong>Okvpn, International Ltd</strong><br>
          Lordou Vironos st. 15<br>
          6023 Larnaca, Cyprus<br>
          <abbr title="Phone">P:</abbr> +370 645 03378
        </address>

        <address>
          <strong>Техническая поддержка</strong><br>
          <a href="mailto:#">team@okvpn.org</a>
        </address>
      </div>

      <div class="col-md-6">
        <form class="form-inline" method="post" action="<?php echo URL::base(); ?>user/create_invoce">
          <div class="form-group">
            <label class="sr-only">Сумма (USD)</label>
            <div class="input-group">
              <div class="input-group-addon">$</div>
              <select class="form-control" name="usd">
                <?php foreach($allow as $item):?>
                  <option value="<?php echo $item ?>"><?php echo $item ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-lg btn-block" style="width: 75%;float: right;">Оплатить</button>
        </form>        

      </div>
    </div>

    </div>
    <div id="modal" class="modal fade" >
      
    </div>
  </body>
</html>

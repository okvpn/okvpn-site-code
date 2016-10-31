<!DOCTYPE html>
<html>
  <head>
    <title>OkVPN - Profile</title>
    <!-- amCharts javascript sources -->
    <script type="text/javascript" src="<?=URL::base()?>public/amcharts/amcharts.js"></script>
    <script type="text/javascript" src="<?=URL::base()?>public/amcharts/serial.js"></script>
    <script type="text/javascript" src="<?=URL::base()?>public/amcharts/themes/dark.js"></script>

    <link href="<?=URL::base()?>public/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=URL::base()?>public/css/cover.css" rel="stylesheet">
    <link href="<?=URL::base()?>public/css/cover-table.css" rel="stylesheet">
    <link rel="shortcut icon" href="<?=URL::base()?>public/img/favicon.ico" type="image/png">
    <script src="<?=URL::base()?>public/js/jquery.min.js"></script>
    <script src="<?=URL::base()?>public/js/bootstrap.min.js"></script>

    <!-- amCharts javascript code -->
    <script type="text/javascript">  
    $.post(
        "<?php echo URL::base()?>profile/billing",
        {"csrf":''},
        function(jsn){
          //$('#st-table').append('<table id="table"></table>');
          var content = '';
          for (var i = 0; i < jsn.length; i++) {
            content+='<tr>';
            content+='<td>'+jsn[i].id+'</td>';
            content+='<td>'+jsn[i].date+'</td>';
            content+='<td>'+jsn[i].x+'</td>';
            content+='<td>'+jsn[i].spent+'$</td>';
            content+='<td>'+jsn[i].balance+'$</td>';
            content+='</tr>';
          }
          $('#tbl').append(content);  
          var sum = 0;
          for (var i = 0; i < jsn.length; i++) {
            jsn[i].date = jsn[i].date.substr(5,9);
            sum+=jsn[i].x;
          }
          build(jsn);
        }
    );



    function build(json) {
      return  AmCharts.makeChart("chartdiv",
        {
          "type": "serial",
          "categoryField": "date",
          "autoMarginOffset": 40,
          "marginRight": 60,
          "marginTop": 60,
          "startDuration": 1,
          "fontSize": 13,
          "handDrawThickness": 4,
          "pathToImages": "<?=URL::base()?>public/amcharts/images/",
          "theme": "dark",
          "categoryAxis": {},
          "gridPosition": "start",
          "trendLines": [],
          "graphs": [
            {
              "balloonText": "<b>[[date]]</b> <br> [[value]] Mbyte",
              "bullet": "round",
              "bulletSize": 10,
              "id": "AmGraph-1",
              "lineAlpha": 1,
              "lineThickness": 3,
              "lineColor": "#ddd",
              "lineThickness": 4,
              "title": "graph 1",
              "type": "smoothedLine",
              "valueField": "x"
            }
          ],
          "guides": [],
          "valueAxes": [
            {
              "id": "ValueAxis-1",
              "title": ""
            }
          ],
          "allLabels": [],
          "balloon": {},
          "titles": [],
          "dataProvider": json
        }
      ); 
    }
    </script>
  </head>
<body class="grad">
  <div class="container">
  <div class="zero-20"></div>
    <?php include 'include/navbar.php'?>
    <div class="zero-50"></div>
    <div class="tbl">
      <div class="zero-20"></div>
  
      <section>
      <h1>Детализация расходов</h1> 
      <div  class="tbl-header">
      <table cellpadding="0" cellspacing="0" border="0">
        <thead>
          <tr>
            <th>id</th>
            <th>дата</th>
            <th>трафик MB</th>
            <th>израсходовано</th>
            <th>остаток</th>
          </tr>
        </thead>
      </table>
      </div>
      <div  class="tbl-content">
      <table cellpadding="0" cellspacing="0" border="0">
        <tbody id ="tbl">
        </tbody>
      </table>
      </div>
      </section>

      <h2>Статистика за последний месяц</h2>
      <div class="zero-20"></div>
      <div id="chartdiv" style="width: 100%; height: 400px; background-color:rgba(0,0,0,0);" ></div>
    </div>
    <div class="zero-50"></div>
  </div>


  <!-- footer -->
</body>
</html>

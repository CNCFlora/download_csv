<?php include 'config.php' ?><!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Download de buscas em CSV</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <!--<script src="<?php echo CONNECT_URL ?>/js/connect.js"></script>-->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    var elasticsearch = '<?php echo ELASTICSEARCH?>';
  </script>
  <script src="app.js" type="text/javascript"></script>
  <style type="text/css">
    .container {
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <div class="container">
        <h2>Download de buscas em CSV</h2>
    <?php if( isset($_GET["msg"]) ): ?>
      <p class="msg label label-success"><?php echo $_GET["msg"] ;?></p>
    <?php endif;?>
    <form id="login">
      <div class='form-group'>
        <button id="login-bt" class='btn btn-primary'>Login</button>
        <button id="logout-bt" class='btn btn-primary'>Logout</button>
      </div>
    </form>
    <form action="download.php" method="POST" id="app">
      <fieldset class=''>
        <div class="form-group">
          <label for="src">Buscas disponíveis</label>
          <select id="src" name="src" class='form-control'></select>
        </div>
        <p><button class='btn btn-primary'>Baixar CSV</button></p>
      </fieldset>
    </form>
  </div>
</body>
</html>

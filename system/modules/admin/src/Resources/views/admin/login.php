<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/img/ico/favicon.ico">

    <title>Signin</title>

    <!-- PLugins CSS -->
    <link href="/assets/admin/plugins/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/admin/plugins/fontawesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/admin/css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
    	<div class="panel panel-primary form-signin-panel">
  			<div class="panel-heading">Please Sign In</div>
  			<div class="panel-body">
  			
		  			<?php if( ! empty($messages)): ?>
		  				<div class="alert alert-danger">
		  				<?php foreach($messages as $message): ?>
		  					<p><i class="fa fa-warning"></i> <?= $message ?></p>
		  				<?php endforeach; ?>
		  				</div>
		  			<?php endif; ?>
  			
			      <form role="form" class="form-signin" method="POST">
			        <input name="email" type="email" class="form-control" placeholder="Email address" required autofocus>
			        <input name="password" type="password" class="form-control" placeholder="Password">
			        <div class="help clearfix">
				        <a class="pull-right" href="#">Help <i class="fa fa-question-circle"></i></a>
			    	</div>
			        <div class="clearfix">
				        <label class="remember pull-left">
				          <input type="checkbox" name="remember-me" value="1"> Remember me
				        </label>
			        	<button class="btn btn-primary pull-right" type="submit">Sign in</button>
			        </div>
			      </form>
  			</div>
		</div>
    </div> <!-- /container -->
  </body>
</html>
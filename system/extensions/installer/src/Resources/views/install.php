<!DOCTYPE html >
<html>
<head>
	<title>Drafterbit Installation</title>
	<style>
		<?php echo $css; ?>
		input, button, .btn, textarea {
			border-radius: 2px !important;
		}

		/* Font */
		@font-face {
		  font-family: 'Lobster Two';
		  /* temporary */
		  src: url('/system/vendor/web/Lobster_Two/LobsterTwo-Regular.ttf') format('truetype');
		}

		.title {
		  margin-top:10px;
		  font-family: "Lobster Two",cursive;
		  font-size: 38px;
		  color:#444;
		  font-weight: 200px;
		}

	</style>
</head>
<body>
	<div class="container" style="margin-top:100px">		
		<div class="row install-section">
			<div style="text-align:center">
				<h2 class="title">Drafterbit</h2>
				<span class="help-block">This pack is not installed yet.</span>
				<br/>
				<br/>
				<a href="#" data-next="#database-connect" class="btn btn-default begin-button"/> Begin </a>
			</div>
		</div>
		
		<div class="row install-section" style="display:none;" id="database-connect">
			<div class="header" style="text-align:center">
				<h2>Database Connection</h2>
				<span class="help-block">Please Enter Your Database Conection Detail</span>
			</div>
			<form data-next="#admin-account" method="post" class="static-form" id="database-form" action="<?php echo base_url('installer/check')?>">
				<div class="col-md-3 col-md-offset-3">
					<div class="form-group">
						<label class="control-label">Driver</label>
						<select name="database[driver]" class="form-control">
							<option value="pdo_mysql">MySQL</option>
							<option value="mysqli">MySQLi</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Hostname</label>
						<input type="text" name="database[host]" class="form-control" value="localhost">
					</div>
					<div class="form-group">
						<label class="control-label">Database</label>
						<input type="text" name="database[dbname]" class="form-control"/>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">User</label>
						<input type="text" name="database[user]" class="form-control"/>
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
							<input type="password" name="database[password]" class="form-control"/>
					</div>
					<div class="form-group">
						<label class="control-label">Table Prefix</label>
							<input type="text" name="database[prefix]" class="form-control" value="dt_"/>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary pull-right"/> NEXT </a>
					</div>
				</div>
			</form>
		</div>

		<div class="row install-section" style="display:none;" id="admin-account">
			<div class="header" style="text-align:center">
				<h2>Administrator Account</h2>
				<span class="help-block">Create Administrator Account</span>
			</div>
			<div class="col-md-4 col-md-offset-4">
				<form data-next="#site-name" class="static-form">
					<div class="form-group">
						<label class="control-label">Email</label>
						<input required type="email" name="email" class="form-control"/>
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<input type="password" name="user-password" class="form-control"/>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary pull-right"/> NEXT </button>
					</div>
				</form>
			</div>
		</div>

		<div class="row install-section" style="display:none;" id="site-name">
			<div class="header" style="text-align:center">
				<h2>Name Your Site</h2>
				<span class="help-block">You always can change this later.</span>
			</div>
			<div class="col-md-4 col-md-offset-4">
			<form class="install-form" method="post" action="<?php echo base_url('installer/install') ?>">
				<div class="form-group">
					<label class="control-label">Name</label>
						<input required type="text" name="site-name" class="form-control"/>
				</div>
				<div class="form-group">
					<label class="control-label">Description</label>
						<textarea type="text" name="site-desc" class="form-control"/></textarea>
				</div>
				<div class="form-group">
						<button type="submit" name="submit" class="btn btn-primary pull-right"/> Install</button>
				</div>
			</form>
			</div>
		</div>

	</div>
	<script>
	<?php echo $js; ?>
	</script>
</body>
</html>
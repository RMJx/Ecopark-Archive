<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Admin Login</title>
	<link rel="stylesheet" href="../style/style.css">
	<link rel="apple-touch-icon" sizes="180x180" href="/ecopark/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ecopark/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ecopark/favicon-16x16.png">
    <link rel="manifest" href="/ecopark/site.webmanifest">
</head>
<body>
	<p style="text-align:center;"><img src="../ecopark.png" style="width:600px;height:220px">
	<div class='box'>
		<div class='box-form'>
			<div class='box-login-tab'></div>
			<div class='box-login-title'>
				<div class='i i-login'></div>
				<h2>ADMIN LOGIN</h2>
			</div>
			<div class='box-login'>
				<div class='fieldset-body' id='login_form'>
					<form action="adminLogin.php" method="post">
					<p class='field'>
						<label for='adminusername'>Admin Username</label>
						<input type='text' id='adminusername' name='adminusername' title='adminusername' required/>
						<span id='valida' class='i i-warning'></span>
					</p>
					<p class='field'>
						<label for='pass'>Password</label>
						<input type='password' id='pass' name='password' title='Password' required />
						<span id='valida' class='i i-close'></span>
					</p>
					  <input type='submit' id='do_login' value='Login' title='Login' />
					</form>
				</div>
			</div>
		</div>
	</div>

</body>

</html>
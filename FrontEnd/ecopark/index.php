<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<style> body{
  background-image: url('https://cdn.dribbble.com/users/1252270/screenshots/5371571/bub.gif');
  background-size: cover;
  height: 100vh;
  padding:0;
  margin:0;
} </style>
	<title>Login</title>
	<link rel="stylesheet" href="style/style.css">
	<link rel="apple-touch-icon" sizes="180x180" href="/ecopark/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ecopark/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ecopark/favicon-16x16.png">
    <link rel="manifest" href="/ecopark/site.webmanifest">
</head>
<body>
	<p style="text-align:center;"><img src="ecopark.png" style="width:600px;height:220px">
	<div class='box'>
		<div class='box-form'>
			<div class='box-login-tab'></div>
			<div class='box-login-title'>
				<div class='i i-login'></div>
				<h2>LOGIN</h2>
			</div>
			<div class='box-login'>
				<div class='fieldset-body' id='login_form'>
					<form action="Login.php" method="post">
					<p class='field'>
						<label for='studentno'>Account Number</label>
						<input type='text' id='studentno' name='studentno' title='studentno' required/>
						<span id='valida' class='i i-warning'></span>
					</p>
					<p class='field'>
						<label for='pass'>PASSWORD</label>
						<input type='password' id='pass' name='password' title='Password' required />
						<span id='valida' class='i i-close'></span>
					</p>
					  <h2><a href="register.php">Register here!</a></h2>
					  <input type='submit' id='do_login' value='Login' title='Login' />
					</form>
				</div>
			</div>
		</div>
	</div>

</body>

</html>
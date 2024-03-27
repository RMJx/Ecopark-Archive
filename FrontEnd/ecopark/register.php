<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="style/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/ecopark/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ecopark/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ecopark/favicon-16x16.png">
    <link rel="manifest" href="/ecopark/site.webmanifest">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
      function enableBtn(){
   document.getElementById("register").disabled = false;
 }
</script>
    <style>
        * {box-sizing: border-box}

/* Add padding to containers */
.container {
  padding: 16px;
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Full-width input fields */
input[type=text], input[type=email] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=email]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit/register button */
.registerbtn {
  background-color: #6f4cc2;
  border-radius: 5px;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: 5px;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity:1;
}

button:disabled,
    button[disabled]{
      background-color: #83738D;
      color: white;
    }

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #f1f1f1;
  text-align: center;
}
    </style>
</head>

<body>
  <p style="text-align:center;">
  <div class='box'>
    <div class='box-form'>
      <div class='box-login-tab'></div>
      <div class='box-login-title'>
        <div class='i i-login'></div>
        <h2>REGISTER</h2>
      </div>
      <div class='box-login'>
        <div class='fieldset-body' id='login_form'>
                      <form action="registeraction.php" method="post">
                        <div class="container">
                          <h1>Register</h1>
                          <p>Please fill in this form to create an account.</p>
                          <hr>
                          
                          <label for="email"><b>Email</b></label>
                          <input type="email" id="email" placeholder="Enter Email" name="email" id="email" required>

                          <label for="username"><b>Username</b></label>
                          <input type="text" maxlength="35" placeholder="Enter User Name" name="username" id="username" required>

                          <label for="fname"><b>First Name</b></label>
                          <input type="text" placeholder="Enter First Name" name="fname" id="fname" required>

                          <label for="lname"><b>Last Name</b></label>
                          <input type="text" placeholder="Enter Last Name" name="lname" id="lname" required>

                          <label for="pcode"><b>Post Code</b></label>
                          <input type="text" placeholder="Enter Postcode" name="pcode" id="pcode" required>
                      
                          <label for="psw"><b>Password</b></label>
                          <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
              
                          <hr>
                      
                          <p>By creating an account you agree to our <a href="terms.php">Terms & Privacy</a>.</p>
                          <div class="g-recaptcha" data-sitekey="6LeWFWceAAAAAMx0Qc417-oHrIS3WK6GzSADdzV9" data-callback="enableBtn"></div>
                          <br>
                          <button type="register" name="register" id="register" class="registerbtn" disabled=true> Register</button>
                        </div>
                      
                        <div class="container signin">
                          <p>Already have an account? <a href="index.php">Sign in</a>.</p>
                        </div>
                      </form>
        </div>
      </div>
    </div>
  </div>

</body>

</html>


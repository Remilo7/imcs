<?php 
$your_email ='web@imcs.com.pl';// <<=== update to your email address

session_start();
$errors = '';
$name = '';
$visitor_email = '';
$user_message = '';

if(isset($_POST['submit']))
{
	
	$name = $_POST['name'];
	$visitor_email = $_POST['email'];
	$user_message = $_POST['message'];
	///------------Do Validations-------------
	if(empty($name)||empty($visitor_email))
	{
		$errors .= "\n Name and Email are required fields. ";	
	}
	if(IsInjected($visitor_email))
	{
		$errors .= "\n Bad email value!";
	}
	if(empty($_SESSION['6_letters_code'] ) ||
	  strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
	{
	//Note: the captcha code is compared case insensitively.
	//if you want case sensitive match, update the check above to
	// strcmp()
		$errors .= "\n Niepoprawny kod captcha!";
	}
	
	if(empty($errors))
	{
		//send the email
		$to = $your_email;
		$subject="$name";
		$from = "web@imcs.com.pl";
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		
		$body = "$user_message\n";	
		
		$headers = "From: $from \r\n";
		$headers .= "Reply-To: $visitor_email \r\n";
		
		mail($to,$subject,$body,$headers);
		
		header('Location: thank-you.html');
	}
}

// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	<title>Formularz kontaktowy</title>

<!-- a helper script for vaidating the form-->
<script language="JavaScript" src="scripts/gen_validatorv31.js" type="text/javascript"></script>
<link rel="stylesheet" href="style3.css" type="text/css" />	
</head>

<body>
<?php
if(!empty($errors)){
echo "<p class='err'>".nl2br($errors)."</p>";
}
?>
<div id='contact_form_errorloc' class='err'></div>

<div id="content">

	<div id="form">

		<div id="formularz"></div>

		<form method="post" name="contact_form" 
	action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"> 

			<p>
				<input type="text" name="email" value='<?php echo htmlentities($visitor_email) ?>'>
			</p>
			<p>
				<input type="text" name="name" value='<?php echo htmlentities($name) ?>'>
			</p>
			<p>
				<textarea name="message"><?php echo htmlentities($user_message) ?></textarea>
			</p>

			<p>
				<div id="szyfr">
					<input id="6_letters_code" name="6_letters_code" type="kod" placeholder="Przepisz kod widoczny na obrazku...">
					<div id="captcha"><img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ></div>
				</div>
			</p>
			<input type="submit" name='submit' value="WYŚLIJ">
		</form>

	</div>

</div>

<script language="JavaScript">
// Code for validating the form
// Visit http://www.javascript-coder.com/html-form/javascript-form-validation.phtml
// for details
var frmvalidator  = new Validator("contact_form");
//remove the following two lines if you like error message box popups
frmvalidator.EnableOnPageErrorDisplaySingleBox();
frmvalidator.EnableMsgsTogether();

frmvalidator.addValidation("name","req","Podaj temat wiadomości"); 
frmvalidator.addValidation("email","req","Podaj swój email"); 
frmvalidator.addValidation("email","email","Wpisz poprawny adres email"); 
</script>
<script language='JavaScript' type='text/javascript'>
function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
</body>
</html>
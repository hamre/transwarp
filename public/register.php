<?php 
include("../sys/core/init.inc.php");
$r = new user();
$r->logged_in_redirect();
include("inc/overall/header_overall.php");

require_once('recaptchalib.php');
$publickey = "6Le32eoSAAAAABk3r8H8j2ZVScI13ShDLhAv-t_C";

if(isset($_POST['submit']) === true) {
    $user = $_POST['username'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['mail'];
    $privatekey = "";
    $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);    
    
    if(empty($user) || empty($pass1) || empty($pass2) || empty($fname) || empty($lname) || empty($email)) {
        $errors[] = 'Alla fält måste fyllas i';
    }
    
    if($pass1 != $pass2) {
        $errors[] = 'Lösenorden stämde inte överens';
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Du angav en ogiltig epost adress';
    }
    
    if($r->user_exist($user) == 1) {
        $errors[] = 'Användarnamnet är upptaget';
    }
    
    if(strlen($pass1) < 6) {
        $errors[] = 'Lösenordet måste vara minst 6 tecken långt';
    }
    
    if($r->email_exists($email) == 1) {
        $errors[] = 'Epost adressen finns redan för en annan användare';
    }
    
    if(!$resp->is_valid) {
        $errors[] = 'Du angav inte rätt CAPTCHA.<br />CAPTCHA sa: '.$resp->error;
    }
    
    if(empty($errors) === false) {
        print($r->output_errors($errors));
    }
    else {
        $salt = uniqid(mt_rand(), true);
        $aktkod = 'transwarp'. $salt . 'yummie';
        $register_data = array("user" => $user,
                               "pass" => $pass1,
                               "fname" => $fname,
                               "lname" => $lname,
                               "email" => $email,
                               "aktkod" => $aktkod);
        $subject = 'Aktivera ditt konto på transwarp.se';
        $message = "                    Hej!\n\n
            
                    Du måste aktivera ditt konto på transwarp.se för att kunna logga in.\n
                    Gå till http://www.transwarp.se/public/activate.php?code={$aktkod} för att slutföra din registrering.\n\n
                    
                    Om du inte kan klicka på länken ovan så markera den och kopiera in den i din webbläsares adressfält.\n\n
                    
                    MvH\n
                    Transwarp.se staff";
        $headers = 'From: do_not_reply@transwarp.se' . "\r\n" .
            'Reply-To: do_not_reply@transwarp.se' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($email, $subject, $message, $headers);
        $r->register_user($register_data);
        header("Location: index.php?register=success");
    }
}
?>
<form action="" method="post">
    <fieldset><legend>Registrera dig här</legend>
        Användarnamn*<br /><input type="text" name="username" value='<?php print($_POST['username']); ?>'><br />
        Lösenord*<br /><input type="password" name="pass1"><br />
        Lösenord igen*<br /><input type="password" name="pass2"><br />
        Förnamn*<br /><input type="text" name="fname" value="<?php print($_POST['fname']); ?>"><br />
        Efternamn*<br /><input type="text" name="lname" value="<?php print($_POST['lname']); ?>"><br />
        Email*<br /><input type="email" name="mail" value="<?php print($_POST['mail']); ?>"><br />
        <?php print(recaptcha_get_html($publickey)); ?>
        <input type="submit" name="submit" value="Registrera">&nbsp;<input type="reset" value="Ångra">
    </fieldset>
</form>
Alla fält med en * är obligatoriska
<?php include("inc/overall/footer_overall.php"); ?>

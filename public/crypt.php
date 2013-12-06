<?php 
include("../sys/core/init.inc.php");
include("inc/overall/header_overall.php"); 
?>
<h1>Kryptera lösenord</h1><br />
<form action="" method="post">
    <input type="password" name="pass">
    <br />
    <input type="submit" name="submit" value="Kryptera">
</form>
<?php 
if(isset($_POST['submit'])) {
    $pass = $_POST['pass'];
    $p = new user();
    
    $hashed = $p->crypt_password($pass);
    print('Ditt lösenord är: <br /><br /><br /><br /><br /><br /><br /><br />' .$hashed);
}
include("inc/overall/footer_overall.php"); ?>
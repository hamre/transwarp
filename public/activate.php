<?php 
include("../sys/core/init.inc.php");
$r = new user();
include("inc/overall/header_overall.php");

if(isset($_GET['active']) == 'yes') {
    $code = $_GET['kod'];
    
    if($r->activate_user($code) === true) {
    header("Location: index.php?active=yes");
}
else {
    print('Denna kod är redan använd');
}
}

if(isset($_GET['code'])) {
    $code = $_GET['code'];
    
    if(empty($code)) {
        $errors[] = 'FEL!<br />Antingen kom du hit av misstag eller så fungerade inte länken i mailet.<br /><br />Om länken inte fungerade var god kontakta en admin.';
    }
    
    if(empty($errors) === false) {
        print($r->output_errors($errors));
    }
    else {
        print('<a href="?kod='.$code.'&active=yes">Tryck här för att aktivera ditt konto</a>');
    }
}
?>

<?php include("inc/overall/footer_overall.php"); ?>
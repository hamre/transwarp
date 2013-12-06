<?php 
include("../sys/core/init.inc.php");
$b = new user();
include("inc/overall/header_overall.php");

if(isset($_GET['register']) == 'success') {
    print('Du har nu registrerat dig. Titta i din mailbox för att aktivera ditt konto.<br />');
}

if(isset($_GET['active']) == 'yes') {
    print('Du har nu aktiverat ditt konto. Var god logga in här till höger');
}

$blogg = $b->get_blogg(0);

foreach($blogg AS $bloggs){
    print('<section><h2>'. $bloggs['subject'] .'</h2><span>Skriven av: <strong>'. $bloggs['author'] . '</strong></span><span style="float:right"><strong>'. $bloggs['date'] .'</strong></span><br /><br />');
    print(nl2br($bloggs['body']).'<br /><br />');
    print('Kommentarer: '. $b->count_comments($bloggs['id']) .'<span style="float:right"><a href="comments.php?blogg_id='. $bloggs['id'] .'">Kommentera/Visa kommentarer</a></span></section>');
}
include("inc/overall/footer_overall.php"); 
?>
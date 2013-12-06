<?php 
include("../sys/core/init.inc.php");
$b = new user();
include("inc/overall/header_overall.php");

if($_GET['blogg_id'] == "") {
    $errors[] = 'Inget ID angavs';
}

if(!is_numeric($_GET['blogg_id'])) {
    $errors[] = 'ID var inte ett nummer';
}

if(empty($errors) === false) {
    print($b->output_errors($errors));   
}
else {
    $id = $_GET['blogg_id'];
    $blogg = $b->get_blogg($id);
    $comments = $b->get_comments($id);
    
    print('<section><h2>'.$blogg['subject'].'</h2><span>Skriven av: '.$blogg['author'].'</span><span style="float:right"><strong>'.$blogg['date'].'</strong></span><br /><br />');
    print(nl2br($blogg['body']).'<br /><br /></section>');
    print('<section><h2>Kommentarer</h2>');

if($comments == "") {
    print('<section>Inga kommentarer funna. Var först med att lägga in en kommentar</section>');
}
else {
    foreach($comments AS $comm) {
        print('<span>Skriven av: '.$comm['author'].'</span><span style="float:right"><strong>'.$comm['date'].'</strong></span><br /><br />');
        print(nl2br($comm['body']).'<br /><br /></section>');
    } 
}
?>
<form action="add_comment.php" metdhod="post">
    <fieldset><legend>Lägg in en kommentar</legend>
        Namn:<br/>
        <input type="text" name="namn" required><br/>
        E-post:<br/>
        <input type="mail" name="mail" required><br/>
        Kommentar:<br/>
        <textarea cols="10" rows="10" name="komm" required></textarea>
        <input type="hidden" value="<?=$id; ?>" name="id">
        <input type="submit" name="submit" value="Lägg till kommentar">&nbsp;<input type="reset" value="Ångra">
    </fieldset>
</form>
<?php
}
include("inc/overall/footer_overall.php"); 
?>
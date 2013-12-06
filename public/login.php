<?php
include("../sys/core/init.inc.php");
$log = new user;
$log->logged_in_redirect();
include("inc/overall/header_overall.php");

if(empty($_POST) === false) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  if(empty($username) || empty($password)) {
    $errors[] = "Alla fält måste fyllas i";
  } else {          
          $login = $log->login($username, $password);
          
          if($login === false) {
            $errors[] = "Användarnamnet eller lösenordet är fel<br />";            
          } else {              
              $_SESSION['user_id'] = $login['id'];              
              header("Location: index.php");
              exit();
            }
        }
}
if(!empty($errors)) {
print('<h2>Det var ett eller flera fel som gjorde att du inte kunde logga in: </h2><br />'.$log->output_errors($errors));
}
include("inc/overall/footer_overall.php");
?>
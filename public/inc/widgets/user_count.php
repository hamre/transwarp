<?php
$users = new user();
$user = $users->count_users();
?>
<div class="widget">
		<h2>Användardata</h2>
			<div class="inner">
                            Vi har för närvarande <strong><?php print($user[0]); ?></strong> användare.<br />
                            <?php
                                if($users->logged_in() === true) {
                                    
                                }
                                else {
                            ?>                                        
                            Vill du vara med i gemenskapen?<br /><br /> <a href="register.php">Registrera dig här</a>
                            <?php
                                }
                            ?>
			</div>
</div>
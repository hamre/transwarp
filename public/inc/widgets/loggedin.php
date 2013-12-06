<div class="widget">
		<h2>Hallå, <?php print($user_data['f_name']); ?></h2>
			<div class="inner">            
        <ul>
<?php
$check = new user;
  if($check->is_admin($user_data['id']) == 1) {		
?>
Hej
<?php
	}
?>
          <li><a href="logout.php">Logga ut</a></li>
        </ul>
			</div>
</div>
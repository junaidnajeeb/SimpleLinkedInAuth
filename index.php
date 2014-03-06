<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php require_once(dirname(__FILE__) . '/linkedInAuth/LinkedIn.php'); ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

		<br/>
		<br/>
		<a  href="linkedInAuth/linkedin_login_get_code.php"><img  class="linkedin-image" src="linkedInAuth/linkedin-button.png" /></a>
		<br/>
		<br/>

		<?php
		
		if (isset($_SESSION['linkedIn_access_token']) && !empty($_SESSION['linkedIn_access_token'])) {

			$data = array(
				'token' => $_SESSION['linkedIn_access_token'],
				'expires_in' => $_SESSION['linkedIn_expires_in'],
				'expires_at' => $_SESSION['linkedIn_expires_at']
			);
			$linkedIn_obj = new LinkedIn($data);

			$profile_data = $linkedIn_obj->getProfilePretty();
			echo "<pre>";
			var_export($profile_data);
		} else {
			echo 'You are not logged in, please login.';
		}
		?>
    </body>
</html>

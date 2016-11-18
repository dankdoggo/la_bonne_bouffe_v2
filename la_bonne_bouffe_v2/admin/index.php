<?php

session_start();

require_once '../inc/connect.php';
require_once '../inc/functions.php';

$post = [];
$errors = [];
$hasError = false;
$formValid = false;

if(!empty($_POST)){

	//Nettoyage du donnée
	foreach ($_POST as $key => $value) {
		$post[$key] = trim(strip_tags($value));
	}

	//Vérification de l'existence du surnom en bdd
	if(!usernameExist($post['username'], $bdd)){
		$errors[] = 'Le couple identifiant/mot de passe est incorrect';
	}

	if(empty($post['password'])){
		$errors[] = 'Le mot de passe doit être complété';
	}

	if(count($errors) === 0){
		$select = $bdd->prepare('SELECT * FROM lbb_users WHERE username = :username');
		$select->bindValue(':username', $post['username']);

		if($select->execute()){
			$user = $select->fetch(PDO::FETCH_ASSOC); // On récupère l'utilisateur pour pouvoir comparer son mot de passe et le connecter si besoin

			//Si les mots de passe correspondent, on peuple la session
			if(password_verify($post['password'], $user['password'])){	
					$formValid = true;

					if($formValid){
						$_SESSION = [
							'username'		=>	$user['username'],
							'firstname'		=>	$user['firstname'],
							'lastname'		=>  $user['lastname'],
							'permission'	=>	$user['permission'],
							'email'			=> 	$user['email'],
							'id'			=> 	$user['id'],
						];

						if(!empty($_SESSION)){ //(éditeur = 1, admin = 2) Si l'utilisateur est un éditeur, alors on redirige sur la liste
							header('Location: my_profile.php');
							die();
						}
							
					}else{
						header('Location: my_profile.php');
					}
			}
			else {
				$errors[] = 'Le couple identifiant/mot de passe est incorrect';
				$hasError = true;
			}
		}
	}else{
		$hasError = true;
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Connexion</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>

	<?php include 'header.php'; ?>


	<?php
		if($hasError){
			echo '<p class="alert alert-danger text-center">'.implode('<br>', $errors).'</p>';
		}
	?>

	<!-- Formulaire de connexion -->
	<div class="col-sm-6 col-sm-push-3">
		<h1 class="text-center text-info">Se connecter</h1>
		<form method="post">
			<label for="username">Nom d'utilisateur</label><br>
			<input type="text" name="username" id="username" class="form-control">

			<br>
			<label for="password">Mot de passe</label><br>
			<input type="password" name="password" id="password" class="form-control">

			<br>
			<input type="submit" value="Se connecter" class="btn btn-primary">
		</form>
	</div>
</body>
</html>

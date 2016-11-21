<?php

require_once '../inc/connect.php';
require_once '../inc/functions.php'; 


$userValid = false;
$tokenValid = false;



	
// on vérifie si le token contenu dans l'URl correspond à celui stocké dans lbb_token , si oui : $tokenValid = true

	
			
	$check = $bdd->prepare('SELECT * FROM lbb_token WHERE token = :token AND email =:email');
		

	$check->bindValue(':token', $_GET['token']);
	$check->bindValue(':email', $_GET['email']);
		
		if($check_get->execute()){
			if($check_get->fetchColumn() > 0){
					$tokenValid = true;
			}
		}			
			
	
	// on vérifie si l email contenu dans l'URl correspond à celui stocké dans lbb_users, si oui : $userValid = true

	if($tokenValid = true){
		$query = $bdd->prepare('SELECT * FROM lbb_users WHERE email = :email');
		$query->bindValue(':email', $get['email']);
		if($query->execute()){
			$user = $query->fetcha(PDO::FETCH_ASSOC);	
		
		}	
	
	}
		
		if(!empty($_user){
			$_post = array_map('trim', array_map('strip_tags', $_POST)); 
		
			if(!(bool)preg_match('#[A-Za-z0-9]{8,20}#', $post['password']))){
				$errors[] = 'Le Mot de passe doit contenir entre 8 et 20 caractères (Pas de caractères spéciaux)';
				
		
			if(count($errors) === 0){

			$update = $bdd->prepare('UPDATE lbb_users SET password = :password WHERE id = :id');
			$update->bindValue(':password', $post['password']);
			$update->bindValue(':id', $user['id']);
			if($update->execute()){
				$passwordValid = true;

			}
		}
	}
	





?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mot de passe oublié</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>
	<?php include 'header.php'; ?>

	<main class="container">
		
	

		<!-- AFFICHAGE DES ERREURS -->

		<?php 
		if(count($errors) > 0){
			echo '<p class="errors">'.implode('<br>', $errors).'</p>';
		}
		elseif(isset($passwordValid) && $passwordValid == true){
			echo '<p class="success">, Votre mot de passe à bien été modifié</p>';
		}
		?>

		<!-- AFFICHAGE DES ERREURS -->

		<form>
			
			<div class="col-sm-6 col-sm-push-3">
				
				<h1 class="text-center text-info">Récupération de votre Mot de passe</h1>
				
				
				<br><br>
				<form method="POST">
					
					<p class="text-center">Veuillez entrer votre nouveau mot de passe</p><br>
					
					<label for="password"></label>
					<input type="text" name="password" id="" placeholder="Nouveau mot de passe " class="form-control">

					<br>
					<input type="submit" value="Enregistrer" class="btn btn-primary">
				
				</form>
			</div>
	</main>
</body>
</html>
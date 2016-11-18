<?php

require_once '../inc/functions.php';
require_once '../inc/connect.php';
session_start();
$post = [];
$errors = [];
$success = false;
$mimeTypeAllow = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
$dirUpload = '../uploads/';

if(!empty($_POST)){
	$post = array_map('trim', array_map('strip_tags', $_POST));

	if(empty($post['username']) || !minAndMaxLength($post['username'], 2, 15)){
		$errors[] = 'Le pseudo doit contenir entre 2 et 15 caractères';
	}
	if(usernameExist($post['username'], $bdd)){
		$errors[] = 'Votre Pseudo est déja utilisé';
	}
	if(empty($post['firstname']) || !minAndMaxLength($post['firstname'], 2, 20)){
		$errors[] = 'Le pseudo doit contenir entre 2 et 20 caractères';
	}
	if(empty($post['lastname']) || !minAndMaxLength($post['lastname'], 2, 20)){
		$errors[] = 'Le pseudo doit contenir entre 2 et 20 caractères';
	}
	if(empty($post['password']) || !minAndMaxLength($post['password'], 8, 20)){
		$errors[] = 'Le Mot de passe doit contenir entre 8 et 20 caractères';
	}
	if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
		$errors[] = 'Veuillez entrer une adresse mail valide';
	}
	if(emailExist($post['email'], $bdd)){
		$errors[] = 'Votre Email est déja utilisé';
	}

	if(!is_uploaded_file($_FILES['avatar']['tmp_name']) || !file_exists($_FILES['avatar']['tmp_name'])){
		$errors[] = 'Vous devez ajouter un avatar';
	}
	else{
		$finfo = new finfo();  // création de la variable finfo qui contiendra les info, de fichier
		$mimeType = $finfo->file($_FILES['avatar']['tmp_name'], FILEINFO_MIME_TYPE);
		
		if(in_array($mimeType, $mimeTypeAllow)){ 
			$avatarName = uniqid('avatar_');
			$avatarName.= '.'.pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

			if(!is_dir($dirUpload)){ // si la variable n'existe, nous creons la variable
				mkdir($dirUpload, 0755);
			}


			if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $dirUpload.$avatarName)){
				$errors[] = 'Erreur lors de l\'envoi de votre avatar';
			}
		}
		else{
			$errors[] = 'Le type de fichier est invalide. Uniquement jpg/gif/png.'; 
		}
	}

	if(count($errors) === 0){
		$insert = $bdd->prepare('INSERT INTO lbb_users(username, firstname, lastname, email, password, avatar) VALUES(:username, :firstname, :lastname, :email, :password, :avatar)');

		$insert->bindValue(':username', $post['username']);
		$insert->bindValue(':firstname', $post['firstname']);
		$insert->bindValue(':lastname', $post['lastname']);
		$insert->bindValue(':email', $post['email']);
		$insert->bindValue(':password', password_hash($post['password'], PASSWORD_DEFAULT));
		$insert->bindValue(':avatar', $dirUpload.$avatarName);
		
		if($insert->execute()){
			$success = true;
		}
		else {
			var_dump($insert->errorInfo());
		}
	}

}




?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Ajouter un utlisateur</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
			
	</head>
	<body>
	<?php if (empty($_SESSION)){
		header('Location: index.php');
	} ?>
	<?php include 'header.php'; ?>
		
		<header></header>

		<main class="cantainer">

		 	<div class="col-sm-6 col-sm-push-3">

				<h1 class="text-center text-info">Ajout d'un utlisateur</h1>

				<?php if(count($errors) > 0): ?>
					<div class="alert alert-danger">
						<?=implode('<br>', $errors);?>
					</div>
		
				<?php elseif(isset($success) && $success == true): ?>

					<div class="alert alert-success">
						Votre inscription a bien été prise en compte
					</div>
				<?php endif; ?>

				<form method="post" class="form-horizontal" enctype="multipart/form-data">
					
					<label for="username">Pseudo</label>
					<input type="text" name="username" id="username" placeholder="" class="form-control">

					<br><br>
					<label for="lastname">Nom</label>
					<input type="text" name="lastname" id="lastname" placeholder="" class="form-control">

					<br><br>
					<label for="firstname">Prénom</label>
					<input type="text" name="firstname" id="firstname" placeholder="" class="form-control">
				
					<br><br>
					<label for="password">Mot de passe</label>
					<input type="password" name="password" id="password" placeholder="" class="form-control">

					<br><br>
					<label for="email">Email</label>
					<input type="text" name="email" id="email" placeholder="" class="form-control">

					<br><br>
					<label for="avatar">Avatar</label>
					<input id ="avatar" name="avatar" class="input-file" type="file" accept="image/*"><br>

					<br><br>
					<!-- <button id="" name="" class="btn btn-info btn-block">S'inscrire</button> -->
					<input type="submit" id="submit" value="Créer l'utilisateur" class="btn btn-info btn-block">

				</form>

			</div>

		</main>
		
		<footer></footer>

	</body>
</html>
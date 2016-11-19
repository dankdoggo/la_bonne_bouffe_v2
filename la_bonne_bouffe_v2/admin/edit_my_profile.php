<?php

require_once '../inc/connect.php';
require_once '../inc/functions.php';


session_start();

// $inf = [ 

// 'firstname' => $_SESSION['firstname'],
// 'lastname'	=> $_SESSION['lastname'],
// 'email'		=> $_SESSION['email']
// ];




$updateAuthor = $_SESSION['id'];
$updateId = $_GET['id'];

$post = [];
$errors = [];
$updateAvatar = false;
$updatePassword = false;
$mimeTypeAllow = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
$dirUpload = '../uploads/uploads_user/';

if(!empty($_POST)){
	$post = array_map('trim', array_map('strip_tags', $_POST));

	if(!minAndMaxLength($post['lastname'], 2, 20)){
		$errors[] = 'Votre prénom doit comporter entre 3 et 20 caractères';
	}

	if(!minAndMaxLength($post['firstname'], 2, 20)){
		$errors[] = 'Votre nom doit comporter entre 3 et 20 caractères';
	}

	if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
		$errors[] = 'Votre email est invalide';
	}

	if(!empty($post['password'])){
		$updatePassword = true;
		if(!((bool)preg_match('#[A-Za-z0-9]{8,20}#', $post['password']))){
			$errors[] = 'Votre mot de passe doit comporter entre 8 et 20 caractères (Pas de caractères spéciaux)';
		}
	}

	if(isset($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name']) && file_exists($_FILES['avatar']['tmp_name'])){

		$finfo = new finfo();
		$mimeType = $finfo->file($_FILES['avatar']['tmp_name'], FILEINFO_MIME_TYPE);
		
		if(in_array($mimeType, $mimeTypeAllow)){
			$avatarName = uniqid('avatar_');
			$avatarName.= '.'.pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

			if(!is_dir($dirUpload)){
				mkdir($dirUpload, 0755);
			}


			if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $dirUpload.$avatarName)){
				$errors[] = 'Erreur lors de l\'envoi de votre avatar';
			}
		}
		else{
			$errors[] = 'Le type de fichier est invalide. Uniquement jpg/gif/png.'; 
		}

		$updateAvatar = true;
	}

	if(count($errors) === 0 && $updateAuthor === $updateId){


		$columnSQL = 'firstname = :firstname, lastname = :lastname, email = :email '; // on instencie la variable $column qui contiendra les informations utilisateurs stockées dans la bdd

		if($updatePassword){
			$columnSQL.= ', password = :password'; // variable $updatePassword qui contient les infos concaténés + le mdp
		}

		if($updateAvatar){
			$columnSQL.= ', avatar = :avatar'; // variable $updateAvatar qui contient les infos concaténés + l'avatar 
		}

		$update = $bdd->prepare('UPDATE lbb_users SET '.$columnSQL.' WHERE id = :id'); // requete SQl par ID
		$update->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
		$update->bindValue(':firstname', $post['firstname']);
		$update->bindValue(':lastname', $post['lastname']);
		$update->bindValue(':email', $post['email']);
		
		if($updatePassword){
			$update->bindValue(':password', password_hash($post['password'], PASSWORD_DEFAULT)); //  update du MDP + hachage de sécu
		}

		if($updateAvatar){
			$update->bindValue(':avatar', $dirUpload.$avatarName); // update de l'avatar
		}



		if($update->execute()){
			$formValid = true;
		}
		else {
			$echec = true;
			// var_dump($update->errorInfo());
			
		}
		
	}

}

if(isset($_GET['id']) && is_numeric($_GET['id'])){ // si l'ID est ok

	$select = $bdd->prepare('SELECT * FROM lbb_users WHERE id = :id');
	$select->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
	if($select->execute()){
		$user = $select->fetch(PDO::FETCH_ASSOC);  // on affiche les résultats dans $user
	}
}

?>
<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title>Modifier mon profil</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="../css/styles.css">
	</head>
	<body>

		<?php if (empty($_SESSION)){
		header('Location: index.php');
		} ?>

		<?php include 'header.php'; ?>
		
		
		<main class="container">
			
		 	<div class="col-sm-6 col-sm-push-3">

				<h1 class="text-center text-info">Modifier mon profil</h1>

				<?php if(count($errors) > 0): ?>
					<div class="alert alert-danger">
						Erreur lors de la mise a jour !
						<?=implode('<br>', $errors);?>
						
					</div>
				
		
				<?php elseif(isset($formValid) && $formValid == true): ?>
					<div class="alert alert-success">
						 compte utilisateur Mis à jour !
					</div>
				<?php endif; ?>


				<form method="post" class="form-horizontal" enctype="multipart/form-data">
					
					<?php if(!isset($formValid) && !empty($user)): ?>
					
					<label for="lastname">Nom</label>
					<input type="lastname" name="lastname" id="lastname" value="<?=$_SESSION['lastname'];?>"  class="form-control">

					<br>
					<label for="firstname">Prénom</label>
					<input type="text" name="firstname" id="firstname" value="<?=$_SESSION['firstname'];?>"  class="form-control">

					<br>
					<label for="password">Mot de passe</label>
					<input type="password" name="password" id="password" class="form-control">

					<br>
					<label for="email">Email</label>
					<input type="text" name="email" id="email" value="<?=$_SESSION['email'];?>"  class="form-control">

					<br>
					<label for="avatar">Avatar</label>
					<input type="file" name="avatar" id="avatar" class="input-file" accept="image/*">
		
					<br>

					<div id="Boutton" class="center-block">
							<button type="submit" class="btn btn-primary">ENREGISTRER</button>
					</div>
				
				</form>

			</div>
			

			<?php endif ?>
		</main>

		
		<footer></footer>

	</body>
</html>
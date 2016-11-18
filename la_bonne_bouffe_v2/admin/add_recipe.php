<?php

session_start();

if(empty($_SESSION)){
	header('Location: index.php');
}

require_once '../inc/connect.php'; 
require_once '../inc/functions.php'; 

$errors = [];
$post = [];
$formValid = false;
$hasError = false;
$dirUpload = 'upload/';


if(!empty($_POST)){

	foreach ($_POST as $key => $value) {
		$post[$key] = trim(strip_tags($value));
	}

	if(empty($post['title-take']) || !minAndMaxLength($post['title-take'], 5, 50)){
			$errors[] = 'Merci d\'indiquer un titre de recette entre cinq et cinquante caractères';
	}

	if(empty($post['content-take']) || !minAndMaxLength($post['content-take'], 25, 1000)){
			$errors[] = 'Merci d\'indiquer une présentation de recette d\'au moins 25 caractères';
	}

	if(!is_uploaded_file($_FILES['picture-take']['tmp_name']) || !file_exists($_FILES['picture-take']['tmp_name'])){ // ici on sécurise en doublant l'info: uploadé et existant
			$errors[] = 'Merci de télécharger une photo de présentation de la recette';
	}

	else{ // si fichier a été envoyé et est existant
		$finfo = new finfo(); //on crée une variable $finfo pour utiliser l'outils qui permet de récupérer le mimetype
		$mimetype = $finfo->file($_FILES['picture-take']['tmp_name'], FILEINFO_MIME_TYPE); //on créee une varaible mimetype pour vérifier le mimetype du fichier (les extensions du fichier)
		$mimeTypeAllow = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];// on crée une variable array pour lister l'ensemble des types d'extension autorisés

		if(in_array($mimetype, $mimeTypeAllow)){ // si dans le tableau mimeTypeAllow la variable $mimetype existe

			$photoName = uniqid('photo_recette_'); // on crée une variable pour renommer le fichier avec un ID unique : uniqid ('avec extension photo_produit_ avant')
			$photoName.= '.' .pathinfo($_FILES['picture-take']['name'], PATHINFO_EXTENSION); // on rajoute au nom du fichier son extension. (pathinfo: chemin système -PATHINFO_EXTENSION est une méthode qui permet de récupérer l'extension du fichier)

			if(!is_dir($dirUpload)){// si le dossier upload de la varibale$dirUpload (créee en haut) n'existe pas (c'est le dossier dans lequel on range les fichiers uploadés car sinon un fichier uploadé à une existance de vie de 30')
				mkdir($dirUpload, 0755); // on crée le dossier (mkdir= créer un dossier)
			}

			if(!move_uploaded_file($_FILES['picture-take']['tmp_name'], $dirUpload.$photoName)){//Si le fichier uploadé n'a pas été rangé dans le dossier upload(concaténé) avec son nouveau nom
				$errors[]= 'Votre photo de présentation de la recette n\'a pas pu être uploadée';  
			}
		}
		else{ // sinon(si dans le tableau $mimeTypeAllow la variable $mimetype) soit n'existe pas
			$errors[]= 'Votre photo de présentation de la recette n\'est pas valide';
		}

	}//fermeture else (si le fichier envoyé est existant)

		
	if (count($errors) === 0)  {
		$add=$bdd->prepare('INSERT INTO lbb_recipe (title, content, picture, date_publish, author) VALUES ( :title, :content, :picture, NOW(), :author)');

		$add->bindValue(':title',$post['title-take']);
		$add->bindValue(':content',$post['content-take']);
		$add->bindValue(':picture', $dirUpload.$photoName);
		$add->bindValue(':author', $_SESSION['id']);

		if($add->execute()){
			$formValid = true;

		} /*fermeture de condition execute()*/

		else {
			var_dump($add->errorInfo());
		}

	}/*fermeture de condition $errors=0*/
	else{ 
		$hasError = true;	
		
	}
}/*Fermeture de ma première condition !empty $_POST*/

?>


<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title>Ajouter une recette</title>

		 <!--Feuille de style Bootstrape-->

		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- My CSS -->
        <link href="../css/styles.css" rel="stylesheet">
	</head>

	<body>

	<?php include 'header.php'; ?>


			<main class="container">

				<h1 class="text-center">Enregistrer une nouvelle recette</h1>
				<hr>

				<!-- Affichage des messages d'erreurs (condition vérification formulaire)-->
				<?php
				if($hasError === true){
					echo '<div class="alert alert-danger">'.implode(' - ', $errors). '</div>';
				}
				if($formValid === true){
					echo '<div class= "alert alert-success"> Bravo votre recette est bien enregistrée</div>';
				}
				?>


				<form method="POST" class="form-horizontal" enctype="multipart/form-data">

					<label class="text-center text-info">Nom de la recette:</label>
					<br>
					<input type="text" name="title-take" class="form-control" placeholder="Ex: Risotto de Saint Jacques et Chorizo">

					<br><br>

					<label  class="text-center text-info">Description:</label>
					<br>
					<textarea name="content-take" class="form-control" placeholder="Ex: Pour préparer la recette du Risotto de Saint Jacques et Chorizo il vous faut : ..."></textarea>

					<br><br>
							
					<label class="text-center text-info">Photo:</label>
					<br>
					<input type="file" name="picture-take" class="btn btn-default btn-lg" accept="image/*">

					<br><br>

					<div id="Boutton" class="center-block">
						<button type="submit" class="btn btn-primary">Enregistrer</button>
					</div>

					</form>
			</main>
	</body>

</html>
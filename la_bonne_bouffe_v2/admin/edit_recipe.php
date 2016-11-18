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
$dirUpload = '../uploads/uploads_recipe/';
$updatePicture = false;



if(!empty($_POST)){

	foreach ($_POST as $key => $value) {
		$post[$key] = trim(strip_tags($value));
	}

	if(!((bool)preg_match('#[A-Za-z]{5,140}#', $post['title-take']))){
			$errors[] = 'Merci d\'indiquer un titre de recette entre cinq et cent quarante caractères';
	}

	if(!((bool)preg_match('#[A-Za-z0-9]{20,}#', $post['ingredient-take']))){
			$errors[] = 'Merci d\'indiquer une liste d\'ingredients d\'au moins 20 caractères';
	}

	if(!((bool)preg_match('#[A-Za-z0-9]{20,}#', $post['content-take']))){
			$errors[] = 'Merci d\'indiquer une présentation de recette d\'au moins 20 caractères';
	}

	if(is_uploaded_file($_FILES['picture-take']['tmp_name']) || file_exists($_FILES['picture-take']['tmp_name'])){ // ici on sécurise en doublant l'info: uploadé et existant
		$finfo = new finfo(); //on crée une variable $finfo pour utiliser l'outils qui permet de récupérer le mimetype
		$mimetype = $finfo->file($_FILES['picture-take']['tmp_name'], FILEINFO_MIME_TYPE); //on créee une varaible mimetype pour vérifier le mimetype du fichier (les extensions du fichier)
		$mimeTypeAllow = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];// on crée une variable array pour lister l'ensemble des types d'extension autorisés

		if (in_array($mimetype, $mimeTypeAllow)){ // si dans le tableau mimeTypeAllow la variable $mimetype existe

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
			$errors[]= 'Votre photo de présentation de recette n\'est pas valide';
		}

		$updatePicture = true;
	}

	if (count($errors) === 0) {

		$columSQL = 'title = :Newtitle, ingredient = :Newingredient, content = :Newcontent, date_publish = NOW(), username_author = :Newauthor';

		if ($updatePicture) {
			$columSQL.= ', picture = :Newpicture';
		}

		$upd = $bdd->prepare('UPDATE lbb_recipe SET '.$columSQL.' WHERE id= :id');

		$upd->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
		$upd->bindValue(':Newtitle', $post['title-take']);
		$upd->bindValue(':Newingredient', $post['ingredient-take']);
		$upd->bindValue (':Newcontent', $post['content-take']);
		$upd->bindValue (':Newauthor', $_SESSION['username']);


		if ($updatePicture) {
			$upd->bindValue(':NewPicture', $post['picture-take']);
		}

		if($upd->execute()){
			$formValid = true;
		}
		else{
			var_dump($upd->errorInfo());
		}

	} /*fermeture count error*/

	else{
		$hasError = true;
	}

} //fermeture première condition ! empty


?>


<!-- ICI ON PREPARE LA REQUETE POUR IDENTIFIER L'ELEMENT EN METHODE GET PAR l'ID 
 -->

<?php 
    //On vérifie que l'id recherché existe et qu'il est de type numérique
    if(isset($_GET['id']) && is_numeric($_GET['id'])){

        //On prépare la requête SELECT
        $select= $bdd->prepare('SELECT * FROM lbb_recipe WHERE id = :id');

        //On lui indique la valeur correspondant au paramètre de la requête
        $select->bindValue(':id', $_GET['id'], PDO::PARAM_INT); 
 
        //on lui dit ici si la requete s'execute 
        if($select->execute()){
            //on crée une varibale $utilisateur pour récupérer les données correpondante à l'ID
            $recipe = $select->fetch(PDO::FETCH_ASSOC);
        }
  
    }
    
?>

<!DOCTYPE html>

<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title> Modifier une recette</title>

		 <!--Feuille de style Bootstrape-->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- My CSS -->
        <link href="../css/styles.css" rel="stylesheet">

	</head>

	<body>

	<?php include 'header.php'; ?>

		
			<main class="container">

				<h1 class="text-center">Modifier la recette</h1>
				<hr>

           		<h3 class="text-center"> Vous allez modifier la recette <?=ucfirst($recipe['title']);?> <br>Ecrite par <?=ucfirst($_SESSION['username']);?> </h3>
           		<br><br>

				<!-- Affichage des messages d'erreurs (condition vérification formulaire)-->
				<?php
				if($hasError === true){
					echo '<div class="alert alert-danger">'.implode(' <br> ', $errors). '</div>';
				}
				if($formValid === true){
					echo '<div class= "alert alert-success"> Bravo votre recette est bien enregistrée</div>';
				}
				?>


				<form method="POST" class="form-horizontal" enctype="multipart/form-data">

					<label for="title" class="text-center text-info">Nom de la recette:</label>
					<br>
					<input id="title" type="text" name="title-take" class="form-control" value="<?=$recipe['title'];?>">

					<br><br>

					<label for="ingredient " class="text-center text-info">Ingredients:</label>
					<br>
					<textarea id="ingredient" name="ingredient-take" class="form-control"><?=$recipe['ingredient'];?>"</textarea>

					<br><br>


					<label for="recipe" class="text-center text-info">Description:</label>
					<br>
					<textarea id="recipe" name="content-take" class="form-control"><?=$recipe['content'];?></textarea>

					<br><br>
							
					<label for="photo" class="text-center text-info">Photo:</label>
					<img src="<?=$recipe['picture'];?>" style="width:100px;">
					<br><br>
					<input id="photo" type="file" name="picture-take" class="btn btn-default btn-lg" accept="image/*">

					<br><br>

					<div id="Boutton" class="center-block">
						<button type="submit" class="btn btn-primary">Enregistrer</button>
					</div>

					</form>
			</main>
	</body>

</html>
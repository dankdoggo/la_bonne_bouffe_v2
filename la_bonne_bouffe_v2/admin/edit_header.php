<?php

session_start();

// permet d'avoir accès à la page edit_header si l'admin est connecté
if (empty($_SESSION)) {
	header('Location: index.php');
} 

require_once '../inc/connect.php';
require_once '../inc/functions.php';

$errors = [];
$post = [];
$files = [];
$mimeTypeAllow = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
$dirUpload = '../uploads/uploads_slider/';
$nbSliders;
$updateValid;



if(!empty($_FILES)) { // tant que les champs ne sont pas vides on effectue pas les vérif donc si l'admin ne change pas ça sera pas pris en compte

	if ($_POST['action'] === 'formulaire_1') { // si on est dans le formulaire 1 alors on va faire les vérif qui lui correspondent
	
		foreach ($_FILES['slider']['error'] as $key => $error) {

			$finfo = new finfo(); // fonction qui permet de retourner les informations sur un fichier
        	$mimeType = $finfo->file($_FILES['slider']['tmp_name'][$key], FILEINFO_MIME_TYPE);
        
	        if(in_array($mimeType, $mimeTypeAllow)) { // si l'extension qu'on a lu dans le tmp_name est présente dans le tableau qui stocke le type d'extension autorisée alors on va lui attribuer un ID qui met la date de l'upload et autre chose aussi
	            $pictureName = uniqid('slide_');
	            $pictureName.= '.'.pathinfo($_FILES['slider']['name'][$key], PATHINFO_EXTENSION); // on stocke dans une var $pictureName unidid un point et les informations sur l'extension

	            if(!is_dir($dirUpload)) { // is_dir vérifie si le dossier existe dans l'arborescence des dossiers
	                mkdir($dirUpload, 0755); // s'il n'y a pas de dossier existant avec al fonction mk_dir on le crée, 0755 correspond aux droits d'utilisateur
	            }
			
				// ici $_FILES nous indique qu'il y a aucune erreur donc on peut faire l'insertion	
				if ($error == UPLOAD_ERR_OK) { // Si tout est ok
					$tmp_name = $_FILES['slider']['tmp_name'][$key];

					if(move_uploaded_file($tmp_name, $dirUpload.$pictureName)) {
						$update = $bdd->prepare('UPDATE lbb_edit_home SET value = :nomImage WHERE data = "slide'.$key.'"');
						$update->bindValue(':nomImage', 'uploads/uploads_slider/'.$pictureName);
						
						if($update->execute()){
							$updateValid = true;
						}
						else {
							var_dump($update->errorInfo());
						}
					}
					else {
						$errors[] = 'Erreur lors du téléchargement de votre image';
					}
				} // end of upload erro ook

			} // end of in an array
			else {
				$errors[] = 'Le type de fichier est invalide. Uniquement jpg/jpeg/gif/png'; 
			}

		}// end of foreach file

	} // end of si on est dans le form 1  
 
} // end of si $_FILES est vide ou pas, on fera pas les vérif

if(!empty($_POST)) { 

	if ($_POST['action'] === 'formulaire_2') { // si on est dans le formulaire 2 on lui fait les vérfi qui lui correspondent
		
		foreach ($_POST as $key => $value) { // on nettoie les données avant toute vérification 
	        $post[$key] = trim(strip_tags($value));
	    }

	    if(!minAndMaxLength($post['name-resto'], 4, 50)) {
    		$errors[] = 'Le nom du restaurant doit comporter entre 4 et 50 caractères';
    	}

		if(!minAndMaxLength($post['address'], 4, 50)) {
    		$errors[] = 'L\'adresse doit comporter entre 4 et 50 caractères';
    	}

	    if(!is_numeric($post['zipcode']) && strlen($post['zipcode']) != 5) {
	    	$errors[] = 'Le code postal doit comporter 5 chiffres';
	   	}

	    if(!minAndMaxLength($post['city'], 3, 50)) {
	    	$errors[] = 'Le nom de la ville doit comporter entre 3 et 50 caractères';
	    }

	    //if avec preg_match pour le mail du resto #[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]#
		if(!(bool)preg_match('#[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]#', $post['email'])) {
	    	$errors[] = 'Veuillez entrer une adresse mail valide';
	    }

	    //if avec preg_match pour le numéro de téléphone
		if(!(bool)preg_match('#[0-9]{10}#', $post['phone'])) {
	    	$errors[] = 'Le numéro de téléphone doit comporter 10 chiffres';
	    }
	
	    if(isset($errors) && count($errors) === 0) {

	    	/*$columSQL = 'name-resto = :name-resto, address = :address, zipcode = :zipcode, city = :city, phone = :phone, email = :email';*/

		    $update = $bdd->prepare('UPDATE lbb_edit_address SET nameresto = :nameresto, address = :address, zipcode = :zipcode, city = :city, phone = :phone, email = :email');
		    
			$update->bindValue(':nameresto', $post['name-resto']);
			$update->bindValue(':address', $post['address']);
			$update->bindValue(':zipcode', $post['zipcode']);
			$update->bindValue(':city', $post['city']);
			$update->bindValue(':phone', $post['phone']);
			$update->bindValue(':email', $post['email']);
						
			if($update->execute()){
				$updateValid = true;
			}
			else {
			var_dump($update->errorInfo());
			}

		} // end of isset et count $erros

	} // end of check form 2 

}  // end of !empty $_POST

	
$checkImg = $bdd->prepare('SELECT value FROM lbb_edit_home WHERE data LIKE "slide%"'); 

if($checkImg->execute()) {
    $sliders = $checkImg->fetchAll(PDO::FETCH_ASSOC);
    $nbSliders = count($sliders);
    $videSliders = empty($sliders);
}

?>
<!DOCTYPE html>
<html lang="fr">



	<head>
		<meta charset="utf-8">

		<title>Editer les coordonnées et le slider</title>

	     <!--Icone FontAwesome CDN Bootstrape-->
	    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous"> 

		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
		<link rel="stylesheet" type="text/css" href="../css/styles.css">

	</head>

	<body>

		<?php if (empty($_SESSION)){
		header('Location: index.php');
		} ?>


		<?php include 'header.php';?>

			
		<h1 class="text-center text-info"><i class="fa fa-picture-o"></i> Editer le slider <br><br> <i class="fa fa-map-marker"></i> Modifier vos coordonnées</h1>
		<hr>

		<?php if($_SESSION['permission'] == 2 && !empty($_SESSION['id'])): ?>


		<?php if(count($errors) > 0) : ?>

		    <div class="alert alert-danger">
		         <?php echo implode('<br>', $errors); ?>
		    </div>

		<?php elseif(isset($updateValid) && $updateValid == true) :?>    
		     
		     <div>
		     	<p class="alert alert-success">Vos mises à jour ont bien été prises en compte</p>
		     </div>       

		<?php endif; ?>

		<br>
			<div class="container">

				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

					<h2 style="font-size: 1.4em;"> <i class="fa fa-picture-o"></i> Editer les images du slider</h2>

					<br>
						
						<form method="post" enctype="multipart/form-data">

							<input type="hidden" name="action" value="formulaire_1">

							<?php 
							for($i=1;$i<=$nbSliders;$i++):?>
							<label for="slider[<?=$i;?>]">Image <?=$i;?></label>
							<input type="file" name="slider[<?=$i;?>]">
							<br>

							<?php endfor; ?>

							<br>
							
							<div id="Boutton" class="center-block">
								<button type="submit" class="btn btn-primary">ENREGISTRER</button>
							</div>

						</form>

					</div>

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

						<h2 style="font-size: 1.4em;">Images actuelles du slider</h2>	

						<br>
		     		
						<?php if($videSliders === true) : ?>

							<div>
								<p class=" text-center alert alert-danger">Vous n'avez pas encore téléchargé d'images</p>
							</div>

						
						<?php elseif ($nbSliders != 0): ?>

									<div>
										<?php foreach ($sliders as $slider) :?>

										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 contain-img-slider-back">
											<img class="img-slider-back" src="../<?=$slider['value'];?>">
										</div>
														
										<?php endforeach; ?>

									</div>


		        		<?php endif; ?>

					</div>

				</div>	

				<br><br>
				<hr>
				<br>

			<h2 style="font-size: 1.4em;"> <i class="fa fa-map-marker"></i> Editer vos coordonnées</h2>	

			<br>
				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

					<form method="post">
						<input type="hidden" name="action" value="formulaire_2">

		                <label for="name-resto">Nom du resto</label><br>
		                <input type="text" id="name-resto" name="name-resto" class="form-control" placeholder="La bonne bouffe">

		                <br>
						<label for="address">Adresse</label><br>
						<input type="text" id="address" name="address" class="form-control" placeholder="Rue de la Gastronomie">

						<br>
						<label for="zipcode">Code postal</label><br>
						<input type="text" id="zipcode" name="zipcode" class="form-control" placeholder="33000">

						<br>
						<label for="city">Ville</label><br>
						<input type="text" id="city" name="city" class="form-control" placeholder="Bordeaux">

						<br>
						<label for="email">Email</label><br>
						<input type="text" id="email" name="email" class="form-control" placeholder="restaurant@labonne-bouffe.fr">

						<br>
						<label for="phone">Téléphone</label><br>
						<input type="text" id="phone" name="phone" class="form-control" placeholder="05.23.14.12.45">

						
						<br>
						<div id="Boutton" class="center-block">
							<button type="submit" class="btn btn-primary">ENREGISTRER</button>
						</div>
						
					</form>
					</div>
				</div>
				<br>
			</div>
			<?php else: ?>

				<div class="alert alert-danger">	
							Vous n'êtes pas autorisé à voir cette page
				</div>

				<?php endif; ?>
	</body>
</html>

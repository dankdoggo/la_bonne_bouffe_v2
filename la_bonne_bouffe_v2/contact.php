<?php

require_once 'inc/connect.php';
require_once 'inc/functions.php'; 

$errors = [];
$post = [];
$formValid = false;
$hasError = false;


if (!empty($_POST)) {

	foreach ($_POST as $key => $value) {
		$post[$key] = trim(strip_tags($value));
	} // fermeture de mon nettoyage de $POST

	if (empty($post['FirstName-take']) || !minAndMaxLength($post['FirstName-take'], 3, 30 )){
		$errors [] = 'Merci d\'indiquer un prénom entre  trois  et trente caractères';
	}

	if (empty($post['LastName-take']) || !minAndMaxLength($post['LastName-take'], 3, 30 )){
		$errors [] = 'Merci d\'indiquer un nom entre trois et trente caractères';
	}

	if(!filter_var(($post['Email-take']),FILTER_VALIDATE_EMAIL)){
		$errors [] = 'Votre adresse email n\'est pas correcte';
	}

	if (empty($post['Message-take']) || !minAndMaxLength($post['Message-take'], 3, 1000 )){
		$errors [] = 'Merci d\'indiquer un message d\'au moins trois caractères';
	}

	if(count($errors) === 0){
		$add=$bdd->prepare ('INSERT INTO lbb_contact (firstname, lastname, email, message, is_read) VALUES (:firstname, :lastname, :email, :message, :is_read)');

		$add->bindValue(':firstname',$post['FirstName-take']);
		$add->bindValue(':lastname',$post['LastName-take']);
		$add->bindValue(':email',$post['Email-take']);
		$add->bindValue(':message',$post['Message-take']);
		$add->bindValue(':is_read', 0, PDO::PARAM_BOOL);

		if($add->execute()){
			$formValid = true;
		}

	} /*fermeture count $errors ==O*/

	else {
		$hasError = true;
	}


} /*fermeture première condition if!empty*/

?>


<!DOCTYPE html>
<html lang="fr">

	<head>

		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="TEXTE">
        <meta name="author" content="TEXTE">
        <meta name="robots" content="index, follow, archive">

        <!--META FB-->
        <meta property="og:title" content="TEXTE">
        <meta property="og:description" content="TEXTE">
        <meta property="og:local" content="fr-FR">
        <meta property="og:site_name" content="TEXTE">
        <meta property="og:image" content="chemin/acces/url.jpg">
        <meta property="og:type" content="website-article">



		<title> Contacter la bonne bouffe </title>

	 	<!-- Police Google Font -->
	    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet"> 
	    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	            
	     <!--Icone FontAwesome CDN Bootstrape-->
	    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous"> 
	         
	     <!--Feuille de style Bootstrape-->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


		 <!-- My CSS -->
        <link href="css/styles.css" rel="stylesheet">
	</head>

	<body>
		<div class="wrapper"> <!-- Wrapper comprenant header + main + footer -->
	       
	        <?php include_once 'inc/header.php'; ?>	

			<main>

				<div class="wrapper-form">

					<h1 class="h1-contact text-center"> Envoyez-nous votre message</h1>

					<p class="text-contact text-center">
	        		Vous souhaitez réserver le restaurant pour une soirée privée ? <br>
	        		Prendre un cours de cuisine avec notre Chef ? <br>
	        		<br>
	        		Nous traiterons avec grand plaisir votre demande.
	        		<br> Envoyez-nous votre message directement avec ce formulaire !
	        		</p>

					<!-- Affichage des messages d'erreurs (condition vérification formulaire)-->
					<?php
					if($hasError === true){
					echo '<div class="alert alert-danger">'.implode(' - ', $errors). '</div>';
					}
					if($formValid === true){
					echo '<div class= "alert alert-success"> Votre message a bien été envoyé. Nous y répondrons dans les plus brefs délais</div>';
					}
					?>




					<form method="POST" class="contact">

					<!-- Prénom -->
						<label for="FirstName" class="label-contact">Prénom</label>
						<input type="text" id="FirstName" name="FirstName-take" placeholder="Ex: Axel" class="input-contact">

					<!-- Nom -->
						<label for="LastName" class="label-contact">Nom</label>
						<input type="text" id="LastName" name="LastName-take" placeholder="Ex: Wargnier" class="input-contact">
					

					<!-- Mail -->
						<label for="Email" class="label-contact">Mail</label>
						<input type="email" id="Email" name="Email-take" placeholder="Ex: AxelWargnier@yahoo.fr" class="input-contact">
						

					<!-- Message -->
						<label for="Message" class="label-contact">Message</label>
						<textarea id="Message" name="Message-take" placeholder="Bonjour, je me permets de vous contacter afin d'obetnir un devis pour une soirée privée" class="input-contact textarea-contact"></textarea>
								
					<!-- Bouton Submit-->
						<div class="center-block">
							<button type="sumbmit" class="boutton-contact"> Envoyer</button>
						</div>

					</form>

					<h1 class="h1-contact text-center"> Vous aussi pouvez nous contacter directement</h1>

					<p class="text-contact text-center">
	        		La bonne bouffe
	        		<br>
	        		Rue de la gastronomie - Quartier Saint Michel -33000 Bordeaux
	        		<br>
	        		01.23.45.67.89
	        		</p>


				</div><!--  fermeture du wrapper-form -->


			</main>



			<?php include_once 'inc/footer.php'; ?>



		</div>

	</body>


</html>

<?php
	

require_once '../vendor/autoload.php'; // permet de charger les packages composer
require_once '../inc/connect.php';
require_once '../inc/functions.php'; 

$token = token_password();


$errors = [];
$post = [];


				
// 
if(!empty($_GET)){ 
		$post = array_map('trim', array_map('strip_tags', $_GET));

	if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
		$errors[] = 'L\'adresse email est invalide';
	}
	if(!emailExist($post['email'], $bdd)){
		$errors[] = 'l\'adresse email n\'existe pas';
	}

	if(count($errors) === 0){
	 
	$insert = $bdd->prepare('INSERT INTO lbb_token(token, email) VALUES(:token, :email)'); // préparation BDD

		
	$insert->bindValue(':token', $token);
	$insert->bindValue(':email', $post['email']);

		if($insert->execute()){ 
				$formValid = true;
				
	
			// le message qui sera envoyé
			$email = $post['email'];
				
				$contentmail = '<a href="https://localhost/la_bonne_bouffe/admin/new_password.php?token='.$token.'&email='.$email.'">Bonjour, Veuillez cliquer sur le lien suivant pour modifier votre mot de passe </a>';
				                             

				$mail = new PHPMailer;
				$mail->isSMTP();                                      
				$mail->Host = 'smtp.mailgun.org';  					  //  l'hote du SMTP
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'postmaster@dev.axw.ovh';           // SMTP username
				$mail->Password = 'WF3Phil0#3';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` 
				$mail->Port = 587;
				$mail->Charset = 'utf-8';                                    // TCP port to connect to

				$mail->setFrom('restaurant.la.bonne.bouffe@gmail.com');		 		 // EXPEDITEUR	
				$mail->addAddress($post['email']);     // DESTINATAIRE


				$mail->Subject = 'Récupération mot de passe ';
				$mail->Body    = $contentmail; // avec html
				$mail->AltBody = $contentmail; // sans html
			
			if(!$mail->send()) {
				  $errors[] = 'Le message n\'a pu être envoyé.';
				  $errors[] = 'Mailer Error: ' . $mail->ErrorInfo;
				
			}
		}

			else {
				var_dump($insert->errorInfo()); // erreur admin requete SQL
			}
	
	}

}


/*----------------------------------- MAIL PHP -----------------------------------*/


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
		elseif(isset($formValid) && $formValid == true){
			echo '<p class="success">, un mail de récupération vous à été envoyé</p>';
		}
		?>

		<!-- AFFICHAGE DES ERREURS -->

		<form>
			
			<div class="col-sm-6 col-sm-push-3">
				
				<h1 class="text-center text-info">Récupération de votre Mot de passe</h1>
				
				

				<form method="GET">
					
					<p>Veuillez entrer votre adresse email pour recevoir un nouveau mot de passe</p>
					<label for="email">Email</label><br>
					<input type="email" name="email" id="email" placeholder="Entrez votre mail " class="form-control">

					<br>
					<input type="submit" value="Envoyer" class="btn btn-primary">
				
				</form>
			</div>
	</main>
</body>
</html>



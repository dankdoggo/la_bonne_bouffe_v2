
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
		<form>
			
			<div class="col-sm-6 col-sm-push-3">
				
				<h1 class="text-center text-info">Récupération de votre Mot de passe</h1>
				
				

				<form method="post">
					
					<p>Veuillez entrer votre adresse email pour recevoir un nouveau mot de passe</p>
					<label for="username">Email</label><br>
					<input type="text" name="username" id="username" placeholder="Entrez votre mail " class="form-control">

					<br>
					<input type="submit" value="Envoyer" class="btn btn-primary">
				
				</form>
			</div>
	</main>
</body>
</html>
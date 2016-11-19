<?php
require_once '../inc/connect.php';
session_start();




//Si l'utilisateur connecté est un administrateur, alors on lui affiche la liste des messages
/*if($_SESSION['permission'] === 2){*/

	//Récupération des mails
	$select = $bdd->prepare('SELECT * FROM lbb_contact');
	if($select->execute()){
		$messages = $select->fetchAll(PDO::FETCH_ASSOC);
	}else{
		var_dump($select->errorInfo());
	}
	
/*}elseif($_SESSION['permission'] === 1){
	//Si l'utilisateur est un éditeur, alors on le redirige vers la liste des recettes
	header('Location: ../list_recipes.php');
	die();

}else{
	//Et s'il n'est rien, on le redirige vers la page de connexion 
	header('Location: index.php');
	die();
}*/


?>
<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title>Liste des messages</title>

	     <!--Icone FontAwesome CDN Bootstrape-->
	    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous"> 
	         

		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
		<link rel="stylesheet" type="text/css" href="../css/styles.css">


	</head>

	<body>

	<?php if (empty($_SESSION)){
			header('Location: index.php');
		} ?>
	<?php include 'header.php'; ?>

		<main class="container">

			<h1 class="text-center text-info"> <i class="fa fa-envelope"></i> Liste des messages</h1>
			<hr>
			
			<!-- Liste des messages -->
			<?php if($_SESSION['permission'] == 2 && !empty($_SESSION['id'])): ?>

				<table class="table">
					<thead>
						<tr>
							<th class="text-center">Prénom</th>
							<th class="text-center">Nom</th>
							<th class="text-center">Email</th>
							<th class="text-center">Message</th>
							<th class="text-center">Lire</th>
							<th class="text-center">Supprimer</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							if(!empty($messages)){
								foreach ($messages as $message) {

									if($message['is_read'] == 0){
										$bold = ' style="font-weight:bold;"';
									}elseif($message['is_read'] == 1){
										//Sinon, affichage normal
										$bold = '';
									}
									echo '<tr>';
										echo '<td class="text-center"'.$bold.'>'.$message['firstname'].'</td>';
										echo '<td class="text-center"'.$bold.'>'.$message['lastname'].'</td>';
										echo '<td class="text-center"'.$bold.'>'.$message['email'].'</td>';
										echo '<td class="text-center"'.$bold.'>'.substr($message['message'], 0, 10).'...</td>';
										echo '<td class="text-center"><a href="view_mail.php?id='.$message['id'].'"><i class="fa fa-envelope fa-2x"></a></td>';
										echo '<td class="text-center"><a href="delete_message.php?id='.$message['id'].'"<i class="fa fa-trash fa-2x"></i></a></td>';
									echo '</tr>';
											
								}
							}else{
								echo '<p class="alert alert-danger">Aucun message</p>';
							}
						?>	
					</tbody>
				</table>
			
			<?php else: ?>

				<div class="alert alert-danger">
					Vous n'êtes pas autorisé à voir cette page
				</div>

			<?php endif ?>

		</main>	
	</body>
</html>
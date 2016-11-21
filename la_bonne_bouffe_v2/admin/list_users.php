<?php
	//  Voir jc pour les affichage selonn permission
	require_once '../inc/connect.php';
	session_start();



$query = $bdd->prepare('SELECT * FROM lbb_users');
if($query->execute()){
	$users = $query->fetchAll(PDO::FETCH_ASSOC);
}
else {
	// A des fins de debug si la requète SQL est en erreur
	var_dump($query->errorInfo());
	die;
}

	?>


<!DOCTYPE html>
<html lang="fr">



	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title>Liste des utilisateurs</title>

	     <!--Icone FontAwesome CDN Bootstrape-->
	    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous"> 
	         


		 <!--Feuille de style Bootstrape-->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		



		<link rel="stylesheet" type="text/css" href="../css/styles.css">

	</head>
	<body>

	<?php if (empty($_SESSION)){
		header('Location: index.php');
	} ?>


	<?php include 'header.php'; ?>

		<main class="container">
			
			<h1 class="text-center text-info"> <i class="fa fa-users"></i> Liste des utilisateurs</h1>
			<hr>

				<?php if($_SESSION['permission'] == 2 && !empty($_SESSION['id'])): ?>
				<table class="table">
					<thead>
						<tr>
							<th class="text-center">Nom et Prénom</th>
							<th class="text-center">Permission</th>
							<th class="text-center">Email</th>
							<th class="text-center">Actions</th>
						<tr>
					</thead>

					<tbody>
						<?php foreach($users as $user): ?>

						<tr>
							<td class="text-center"><?=$user['firstname'].' '.$user['lastname']; ?></td>
							<td class="text-center"><!-- <?=$user['permission']; ?> -->

								<?php if($user['permission'] == 1): ?>
										&Eacutediteur
								<?php else: ?>
										Administrateur
								<?php endif;?>

							</td>
							<td class="text-center"><?=$user['email']; ?></td>
							<td class="text-center">
								<a href="view_user.php?id=<?=$user['id'];?>" title="Voir le profil de l'utilisateur">
									<i class="fa fa-user-circle"></i>Visualiser
								</a>
								&nbsp; - &nbsp;
								<?php if($_SESSION['permission'] == 2): ?>

								<a href="edit_user.php?id=<?=$user['id'];?>" title="Editer cet utilisateur">
									<i class="fa fa-pencil"></i> Modifier
								</a>
								&nbsp; - &nbsp;

								<a href="delete_user.php?id=<?=$user['id'];?>" style="color:red" title="Supprimer cet utilisateur">
									<i class="fa fa-trash"></i> Supprimer
								</a>
							<?php endif;?>
							</td>
						</tr>

					<?php endforeach; ?>

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

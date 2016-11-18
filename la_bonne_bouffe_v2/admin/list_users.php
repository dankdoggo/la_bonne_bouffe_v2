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
<html>
	<head>
		<meta charset="utf-8">
		<title>Liste des utilisateurs</title>
		 <!--Feuille de style Bootstrape-->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
	<body>
	<?php if (empty($_SESSION)){
		header('Location: index.php');
	} ?>
	<?php include 'header.php'; ?>
		<main class="container">

			<h1 class="text-center text-info">Liste utilisateurs</h1>

				<table class="table">
					<thead>
						<tr>
							<th class="text-center">Username</th>
							<th class="text-center">Permission</th>
							<th class="text-center">E-mail</th>
							<th class="text-center">action</th>
						<tr>
					</thead>

					<tbody>
						<?php foreach($users as $user): ?>
						<tr>
							<td class="text-center"><?=$user['username']; ?></td>
							<td class="text-center"><!-- <?=$user['permission']; ?> -->

								<?php if($user['permission'] == 1): ?>
										&Eacutediteur
								<?php else: ?>
										Administrateur
								<?php endif;?>

								</td>
							<td class="text-center"><?=$user['email']; ?></td>
							<td class="text-center">
								<a href="view_user.php?id=<?=$user['id'];?>" class="text-success" title="Voir le profil de l'utilisateur">
								<i class="fa fa-user-circle-o"></i> Visualiser
								</a>
								&nbsp; - &nbsp;
								<?php if($_SESSION['permission'] == 2): ?>

								<a href="edit_user.php?id=<?=$user['id'];?>" title="Editer cet utilisateur">
									<i class="fa fa-edit"></i> Editer
								</a>
								&nbsp; - &nbsp;

								<a href="delete_user.php?id=<?=$user['id'];?>" class="text-danger" title="Supprimer cet utilisateur">
									<i class="fa fa-times"></i> Supprimer
								</a>
							<?php endif;?>
							</td>
						</tr>

					<?php endforeach; ?>
					</tbody>
				</table>
		</main>
	</body>
</html>

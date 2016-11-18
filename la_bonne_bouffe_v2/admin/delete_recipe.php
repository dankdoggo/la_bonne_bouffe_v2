<?php
	
	require_once '../inc/connect.php';
	session_start();

	if(isset($_GET['id']) && is_numeric($_GET['id'])){ // si l'id est reconu

	if(!empty($_POST)){
		if(isset($_POST['delete'])){  // si on clique sur l'input delete 
			$delete = $bdd->prepare('DELETE FROM lbb_recipe WHERE id = :id'); // préparation requete SQL de supression d'un user
			$delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

			if($delete->execute()){
				header('Location: list_recipe.php'); // si la supression s'effectue, on affiche la liste des recettes
				die;
			}
		}
	}

	$select = $bdd->prepare('SELECT * FROM lbb_recipe WHERE id = :id');
	$select->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
	if($select->execute()){
		$recipe = $select->fetch(PDO::FETCH_ASSOC); // on stock nos résultats dans $user
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Supprimer une recette</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
<body>
<?php if (empty($_SESSION)){
		header('Location: index.php');
	} ?>
<?php include 'header.php'; ?>


	<main class="container">

		<h1 class="text-center text-info">
			<i class="fa fa-trash"></i> Supprimer une recette
		</h1>


			<form method="post" class="pager">
				 <input type="button" onclick="history.back();" value="Annuler" class="btn btn-default">

				 <input type="submit" name="delete" value="Confirmer la supression" class="btn btn-success">
			</form>

	</main>

</body>
</html>

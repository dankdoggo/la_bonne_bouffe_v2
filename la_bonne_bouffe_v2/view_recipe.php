<?php

require_once 'inc/connect.php';

if(isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])){

	$select = $bdd->prepare('SELECT * FROM lbb_recipe LEFT JOIN lbb_users ON lbb_recipe.username_author=lbb_users.username WHERE lbb_recipe.id = :idRecipe'); //requête d'Anthony (pour l'écriture): SELECT * FROM recipe LEFT JOIN users ON recipe.id_autor=users.id;

	$select->bindValue(':idRecipe', $_GET['id'], PDO::PARAM_INT);

	if($select->execute()){

		$recipe = $select->fetch(PDO::FETCH_ASSOC);

	}else{
		var_dump($select->errorInfo());
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>La recette du chef</title>


	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Source+Sans+Pro" rel="stylesheet"> 
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
	<div class="wrapper">

	<?php include_once 'inc/header.php'; ?>

	<main>
	<?php if(empty($recipe)):?>
		<?php header('Location: 404.php'); ?>
	<?php else:?>	
		
		<section id="section-view-recipe">
			<h1 class="title-section-list"><?=$recipe['title'];?></h1>
			<div class="contain-img-list-recipe">
				<img src="<?=$recipe['picture'];?>" alt="migale" class="img-list-recipe">
			</div>
			<!-- <div>
				<h2 class="title-ingredient-preparation">Ingrédients</h2>
				<ul>
					<li>Oeufs</li>
					<li>Migales</li>
					<li>Mayo</li>
				</ul>
			</div> -->
			<div>
				<h2 class="title-ingredient-preparation">Préparation de la recette</h2>
				<p><?=$recipe['content']?></p>
			</div>
			<p class="date-author-recipe">Publié le <?=$recipe['date_publish']?> par <?='Chef '.$recipe['firstname'].' '.$recipe['lastname'];?></p>

		</section>
	<?php endif; ?>				
	</main>

	<?php include_once 'inc/footer.php'; ?>

	</div> <!-- end of div wrapper -->
</body>
</html>
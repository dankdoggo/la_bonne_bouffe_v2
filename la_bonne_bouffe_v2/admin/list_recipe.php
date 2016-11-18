<?php

require_once '../inc/connect.php';
require_once '../inc/functions.php';
session_start();
$get =[];
$sql='';


/*PHP module de recherche*/

if (!empty($_GET)) {
	    foreach ($_GET as $key => $value) { 
        $get[$key] = trim(strip_tags($value));
    } 

    if (isset($get['search']) && !empty($get['search'])) {
    	$sql =' WHERE title LIKE :search OR content LIKE :search';
    }

} /*fermeture première condition !empty $_GET*/

$search = $bdd->prepare('SELECT * FROM lbb_recipe'.$sql);

if(!empty($sql)){
	$search->bindValue(':search', '%'.$get['search'].'%');
}

if ($search->execute()) {
	$resultSearch = $search->fetchALL(PDO::FETCH_ASSOC);
}
else {
	var_dump($search->errorInfo());
}
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



		<title> Liste des recettes du Chef </title>

	 	<!-- Police Google Font -->
	    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet"> 
	    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	            
	     <!--Icone FontAwesome CDN Bootstrape-->
	    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous"> 
	         
	     <!--Feuille de style Bootstrape-->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


		 <!-- My CSS -->
        <link href="css/styles.temp.css" rel="stylesheet">

	</head>


	<body>
		<?php if (empty($_SESSION)){
		header('Location: index.php');
		} ?>
		<?php include 'header.php';?>
		<main class="container">

			<h1 class="text-center"> Liste des recettes du Chef présentes sur le site</h1>
			<hr>


			<div>
				<form method="get" class="form-inline">
					<div class="input-group">
						<input type="text" id="search" name="search" class="form-control" placeholder="Rechercher une recette" value="">
							<span class="input-group-btn">
								<button class="btn btn-info" type="submit">
									<i class="fa fa-search"></i>
								</button>
							</span>
					</div>
				</form>
			</div>



			<table class="table">

				<thead>
					<tr>
						<th>Titre</th>
						<th>Date publication</th>
						<th>Auteur</th>
						<th colspan="2" class="text-center">Actions</th>

					</tr>
				</thead>

				<tbody>

				<?php foreach ($resultSearch as $value):?>
					<tr>
						<td><?= ucfirst($value['title']);?></td>
						<td><?=$value['date_publish'];?></td>
						<td><?= ucfirst($value['username_author']);?></td>
						<td>
							<a href="edit_recipe.php?id=<?=$value['id'];?>"> 
							<i class="fa fa-pencil"></i> Modifier</a>
						</td>
						<td><a style="color:red" href="delete_recipe.php?id=<?=$value['id'];?>"> 
							<i class="fa fa-trash"></i> Supprimer</a>
						</td>

					</tr>
				</tbody>

			<?php endforeach; ?>


			</table>

		</main>

	</body>

</html>	


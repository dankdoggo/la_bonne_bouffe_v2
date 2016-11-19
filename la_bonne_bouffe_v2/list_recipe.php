<?php

require_once 'inc/connect.php';
require_once 'inc/functions.php';

$get =[];
$sql='';




/*PHP module de recherche */

 /*REQUETE PAGINATION DE RESULTAT */
 // Permet d'identifier le numéro de la page courante. Si pas défini par défaut c'est la page 1

    if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page'])) {
    	$page = (int) $_GET['page'];
    }
    else{
    	$page = 1;
    }

// On réalise une requête SELECT COUNT pour savoir le nombre de recettes écrites dans la base.
// on retourne l'ensemble des résultats dans une variable  $result
//On crée la variable $Nbrecette pour stocker le nombre total de recette
$count = $bdd->prepare('SELECT COUNT(*) AS total FROM lbb_recipe');
$result = $count->fetch();
$NbRecette = $result['total'];
//var_dump($count);

// On calcul le nombre de page à afficher pour créer les liens numérotés
// La fonction ceil() arrondit au nombre supérieur notre division. 
//le nombre total de page = nombre total article/ nombre d'article par page
$nbTotalPages = ceil($NbRecette / $itemsPerPage); 

// Permet de calculer la page de démarrage. 
$startPage = ($page - 1) * $itemsPerPage;


/*PHP module de recherche Doublé de l'affichage des pages (toutes ou celles concernées par la recherche)*/

if (!empty($_GET)) {
	    foreach ($_GET as $key => $value) { 
        $get[$key] = trim(strip_tags($value));
    } 

    if (isset($get['search']) && !empty($get['search'])) {
    	$sql =' WHERE title LIKE :search OR content LIKE :search';
    }

} /*fermeture première condition !empty $_GET*/


// on prépare la requête de recherche avec l'info de pagination
$search = $bdd->prepare('SELECT * FROM lbb_recipe'.$sql.' ORDER BY id DESC LIMIT :maxi');
$search->bindValue(':maxi', 6, PDO::PARAM_INT);

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
<html>
<head>
	<meta charset="utf-8">
	<title>Les recettes des chefs</title>


	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Source+Sans+Pro" rel="stylesheet"> 
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
	<div class="wrapper">

	<?php include_once 'inc/header.php'; ?>	

	<main>
		<section id="section-list-recipe">
			<h1 class="text-center title-section-list">Liste des recettes du Chef</h1>
			
			<div class="contain-search-recipe">
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

			

			<div class="container">
				<div class="row">
				<!--Affichage des recettes-->
					<?php foreach ($resultSearch as $value):?> 
						<?php if(isset($get['search']) && !empty($get['search'])) :?> <!--Si une recherche est rentrée, on affiche les résultats de la recette-->
							<a href="view_recipe.php?id=<?=$value['id']?>" class="linkRecipe">
							<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 contain-img-text-list-recipe">
								<div class="title-list-recipe"><?=preg_replace('/'.$get['search'].'/', '<span style="background:yellow;">'.$get['search'].'</span>', $value['title']);?></div>
								<div class="contain-img-list-recipe">
									<img src="<?=$value['picture'];?>" alt="recipe" class="img-list-recipe">
								</div>	
							</div>	
							</a>
						<?php elseif(empty($get['search'])) : ?>
							<a href="view_recipe.php?id=<?=$value['id']?>" class="linkRecipe">
							<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 contain-img-text-list-recipe">
								<div class="title-list-recipe"><?=$value['title'];?></div>
								<div class="contain-img-list-recipe">
									<img src="<?=$value['picture'];?>" alt="recipe" class="img-list-recipe">
								</div>	
							</div>	
							</a>
						<?php elseif(empty($value)) : ?> <!--Si jamais il ne retourne rien (même si théoriquement, on aura toujours quelque chose)-->	
							<p class="jumbotron alert alert-danger">Nous n'avons pas trouvé ce que vous recherchez</p>		
						<?php endif;?>	
					<?php endforeach; ?>		
				</div>

			</div>



		</section>			
	</main>

	<?php include_once 'inc/footer.php'; ?>

	</div> <!-- end of div wrapper -->
</body>
</html>
<?php

require_once 'inc/connect.php';

$select = $bdd->prepare('SELECT * FROM lbb_recipe ORDER BY id DESC LIMIT :maxi');
$select->bindValue (':maxi', 3, PDO::PARAM_INT);

if($select->execute()){
	$recipes = $select->fetchAll(PDO::FETCH_ASSOC);

}else{
	var_dump($select->errorInfo());
}

$displayImg = $bdd->prepare('SELECT value FROM lbb_edit_home WHERE data LIKE "slide%"'); 

if($displayImg->execute()) {
    $sliders = $displayImg->fetchAll(PDO::FETCH_ASSOC);
    $nbSliders = count($sliders);
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



		<title> Bienvenue au restaurant La Bonne Bouffe </title>

	 	<!-- Police Google Font -->
	    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet"> 
	    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 

	    <!--appel librairy jquery-->
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	            
	     <!--Icone FontAwesome CDN Bootstrap-->
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
	<section id="section-slider">

	<div id="carrousel" class="bloc-slider center-block">
		<ul>
			<li>
				<?php foreach ($sliders as $slider) : ?>
					
					<img class="img-slider" src="<?=$slider['value'];?>">
				
				<?php endforeach; ?>
			</li>
		</ul>
	</div>
		
		      
		   
    </section>

	        	<section id="section-recipe">

	        		<h1 class="home h1-home text-center">Nos recettes de Chef</h1>
	        		<p class="text-recipe text-center">
	        		Vous avez adoré les plates dégustés chez nous ? Lancez-vous et devenez un vrai Chef !
	        		<br> Découvrez nos meilleurs recettes pour prolonger le plaisir de vos papilles.
	        		</p>

	        		<div class="wrapper-recipe">
	        			<?php foreach ($recipes as $recipe): ?>

	        			<div class="bloc-recipe">
		        			<div id="recipe1" class="bloc-recipe-first">
			        			<h2 class="home h2-home text-center"><?=$recipe['title'];?></h2>
								<div class="bloc-recipe-second">
			        				<img class="img-recipe" src="<?=$recipe['picture'];?>"></img>
			        			</div>
							</div>
							<a href="view_recipe.php?id=<?=$recipe['id'];?>">
							<h3 class="h3-home text-center">Lire la recette</h3>
							</a>
						</div>

						<?php endforeach;?>	
					</div>	        		
	        	</section>

				<section id="section-link-recipe">

					<a href="list_recipe.php" id="link-recipe-style">
					<div class="link-recipe center-block">Découvrir toutes nos recettes</div>
					</a>
					
				</section>



	        </main>

	       <?php include_once 'inc/footer.php'; ?>


	     </div> <!-- fermeture wrapper comprenant header + main + footer -->





        
    
    
    <!--appel script personalisé-->
    <script src="js/script.js"></script>


    </body>

</html>
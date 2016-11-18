<?php



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



		<title> Oups...On a pas trouvé ce que vous recherchez </title>

	 	<!-- Police Google Font -->
	    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet"> 
	    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet"> 
	            
	     <!--Icone FontAwesome CDN Bootstrape-->
	    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous"> 
	         
	     <!--Feuille de style Bootstrape-->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


		 <!-- My CSS -->
        <link href="css/styles.css" rel="stylesheet">

	</head>

	<body>
	<div class="wrapper"> <!-- Wrapper comprenant header + main + footer -->

			<?php include_once 'inc/header.php'; ?>		
	        
	        <main class="error_404">
	        	<p class="error_message">Oups...on a pas trouvé ce que vous recherchez</p>
	        </main>



	       <?php include_once 'inc/footer.php'; ?>


	</div> <!-- fermeture wrapper comprenant header + main + footer -->





        
        <!-- 
*******************************************************************************************
SCRIPTS DE FIN DE PAGE : NE PAS TOUCHER, NE RIEN ECRIRE APRES
*******************************************************************************************
-->
        <!-- jQuery -->
<!--        <script src="js/jquery.js"></script>-->

        <!-- Bootstrap Core JavaScript -->
<!--        <script src="js/bootstrap.min.js"></script>-->

        <!-- APPEL Jquery CDN-->
   <!--     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
        
<!--    <script src="js/monscript"> </script>-->


    </body>

</html>
<?php

$logout = false;
    if(!isset($_SESSION)) 
    { 
        session_start();
        /*$_SESSION = array(); if (ini_get("session.use_cookies")) { $params = session_get_cookie_params(); setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"] ); $logout = true; } session_destroy(); header('location: connectMember.php'); die();*/
    }
    if(isset($_POST['Oui']) && $_POST['Oui'] == 'Oui'){
        $_SESSION = [];     //vide le tableau de session
        session_destroy();
        header('location: index.php');
        die();
    }
    elseif(isset($_POST['Non']) && $_POST['Non'] == 'Non'){
        header('location: my_profile.php');
        die();
    } 
?>




<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Souhaitez-vous nous quitter ?</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="../css/styles.css">
	</head>
	<body>
		<header><?php include 'header.php'; ?></header>
        
            <br>
                <br>
                    <br>


		<main class="container">
			 <?php if(isset($_SESSION)): ?>
                    <!-- <h1 class="text-center text-info"><i class="fa fa-user-times"></i> Déconnexion</h1> -->
		            <h1 class="text-center text-info">Etes vous sûr de vouloir vous déconnecter ?</h1>
                    
                    <br>
                        <br>
                            <br>


                     <div class="">   
                    <form method="post">
                    <div id=pos_btn>
                        	<input id="btndeco" type="submit" name="Oui" value="Oui" placeholder="Se déconnecter"
                     	   	class="btn btn-success">
                        	<input id="btndeco" type="submit" name="Non" value="Non" placeholder="Revenir à la page précédente"
                        	class="btn btn-danger">
                    </div>
                    </form>
                    </div>
                    <?php endif;
                if(!isset($_SESSION['id'])){
                    header('Location: index.php');
                }
                ?>
            </main>



	</body>
</html>
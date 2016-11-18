<?php

// com
require_once '../inc/connect.php';
session_start();



if(isset($_SESSION['id']) && is_numeric($_SESSION['id'])){

	$select = $bdd->prepare('SELECT * FROM lbb_users WHERE id = :id');
	$select->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
	if($select->execute()){
		$user = $select->fetch(PDO::FETCH_ASSOC);
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Mon profil</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	   <link rel="stylesheet" type="text/css" href="../css/styles.css">
    </head>
	<body>
		<?php if (empty($_SESSION)){
        header('Location: index.php');
        } ?>
		<?php include 'header.php'; ?>

		<main class="container">


               
                <h1 class="text-center text-info">
			<i class="fa fa-user"></i> Profil d'un utilisateur
		</h1>

                <hr>

                <?php if(empty($user)): ?>
                    <div class="alert alert-danger">
                        Utilisateur inconnu !
                    </div>
                    <?php endif; ?>


                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li>
                                        <strong>Pseudo :</strong>
                                        <?=$user['username'];?>
                                    </li><br>
                                     <li>
                                        <strong>Nom :</strong>
                                        <?=$user['lastname'];?>
                                    </li><br>
                                     <li>
                                        <strong>Prénom :</strong>
                                        <?=$user['firstname'];?>
                                    </li><br>
                                    <li>
                                    	<strong>Email :</strong>
                                        <?=$user['email'];?>
                                    </li><br>
                                    <li>
                                        <strong>Status :</strong>
                                        <?php if($user['permission'] == 1): ?> 
                                        	&Eacutediteur
                                        <?php else: ?>
                                        	Administrateur
                                        <?php endif; ?>

                                    </li>
                                </ul>

                                <hr>
                                <br>

                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="edit_user.php?id=<?=$user['id'];?>" title="Editer cet utilisateur" class="btn btn-info btn-block">
                                            <i class="fa fa-edit"></i> Editer
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <img src="<?=$user['avatar'];?>" class="img-thumbnail img-responsive">
                            </div>
                        </div>

		</main>
		
		<footer></footer>

	</body>
</html>
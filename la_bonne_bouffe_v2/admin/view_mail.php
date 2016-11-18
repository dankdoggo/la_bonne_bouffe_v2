<?php

require_once '../inc/connect.php';

$centerTitle = '<h1 class="text-center text-info">';
$endCenterTitle = '</h1>';
$centerText = 'class="text-center"';

if(isset($_GET['id']) && !empty($_GET['id'])){
	$select = $bdd->prepare('SELECT * FROM lbb_contact WHERE id = :idMail');
	$select->bindValue(':idMail', $_GET['id'],PDO::PARAM_INT);

	if($select->execute()){
		$message = $select->fetch(PDO::FETCH_ASSOC);

		$markRead = $bdd->prepare('UPDATE lbb_contact SET is_read = :is_read WHERE id = :idMail');
		$markRead->bindValue(':idMail', $_GET['id'], PDO::PARAM_INT);
		$markRead->bindValue(':is_read', 1);
		$markRead->execute();

	}else{
		var_dump($select->errorInfo());
	}
}else{
	$error = 'Ce message n\'existe pas';
}



?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Message</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<?php if (empty($_SESSION)){
		header('Location: index.php');
	} ?>
<?php include 'header.php'; ?>
<main class="container">
	<div class="col-sm-6 col-sm-push-3">
		<?php 
			echo $centerTitle.'Message de '.$message['firstname'].' '.$message['lastname'].$endCenterTitle.'<br>';
			echo '<div class="jumbotron">';
			echo $message['email'].'<br>';
			echo $message['message'].'</div>';
		?>
	</div>
</main>
</body>
</html>
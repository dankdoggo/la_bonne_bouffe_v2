<?php
/**
 * Vérifie que la longueur d'une chaine se situe entre $min et $max caractères
 * @param string $str La chaine à vérifier
 * @param int $min La longueur minimale
 * @param int $max La longueur maximale
 * @return bool TRUE Si la longueur est ok, FALSE sinon
 */
function minAndMaxLength($str, $min = 2, $max = 255){

	// On vérifie que la chaine soit remplie et bien de type string, sinon on retourne un message d'erreur
	// Note : Une fonction s'arrête dès le premier "return"
	if(empty($str) || !is_string($str)){
		return trigger_error('Le paramètre $str est invalide', E_USER_WARNING);
	}

	// On effectue la vérification sur la longueur de la chaine
	if(strlen($str) >= $min && strlen($str) <= $max){
		// Ici la longueur est ok
		return true;
	}
	else {
		// Ici la longueur n'est pas bonne
		return false;
	}
}


/** 
 * Vérifie l'existence d'un pseudo
 * @param string $username le pseudo qu'on souhaite vérifier
 * @param obj $bdd La connexion à PDO
 * @return bool TRUE si l'email existe, false sinon
 */
function usernameExist($username, $bdd){

	// On vérifie que $username & $bdd ne soient pas vides
	if(!empty($username) && !empty($bdd)){

		// On effectue la requete
		$check = $bdd->prepare('SELECT * FROM lbb_users WHERE username = :username');
		$check->bindValue(':username', $username);
		if($check->execute()){
			if($check->fetchColumn() > 0){
				return true;
			}
		}
	}

	return false;
}

/**
* vérifie l'existence d'une adresse e-mail
*@param obj $bdd La connexion à PDO
*@param strind l'adresse email qu'on souhaite vérifier
*@return bool TRUE si l'email existe, false sinon
*/
function emailExist($email, $bdd){

	// On vérifie que $email et $bdd ne sois pas vide
	if(!empty($email) && !empty($bdd)){

		// On effectue la requete
		$check = $bdd->prepare('SELECT * FROM lbb_users WHERE email = :email');
		$check->bindValue(':email', $email);
		if($check->execute()){
			if($check->fetchColumn() > 0){
				return true;
			}
		}
	}
		return false;
}
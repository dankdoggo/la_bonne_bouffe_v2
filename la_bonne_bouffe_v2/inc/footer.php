<?php

require_once 'connect.php';	

$selectAddress= $bdd->prepare('SELECT * FROM lbb_edit_address');

if($selectAddress->execute()){
    //on crée une varibale $utilisateur pour récupérer les données correpondante à l'ID
    $address = $selectAddress->fetch(PDO::FETCH_ASSOC);
}

?>

<div class="wrapper"> <!-- Wrapper comprenant header + main + footer -->

	<footer id="footer">

		<div class="container">
			<div class="row">
	
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
		
					
					<div><?=$address['name-resto']?></div>
					<div><?=$address['address']?></div>
					<div><?=$address['zipcode']?>&nbsp;<?=$address['city']?></div>
					<div><?=$address['email']?></div>
					<div><?=$address['phone']?></div>
									
				</div>
	
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-center mentions-footer">
					<div>
						<a href="https://generateur-de-mentions-legales.com/">Mentions Légales</a>
					</div>
					<div>Made with &nbsp;
						<i class="fa fa-2x fa-hand-peace-o peace" aria-hidden="true"></i>
						&nbsp; by Group 3
					</div>
				</div>
	
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-right mentions-footer">
						<div>
							<a href="https://www.facebook.com" target="_blank">
							<i class="fa fa-3x fa-facebook-square" aria-hidden="true"></i>
							</a>
							&nbsp;

							<a href="https://twitter.com/?lang=fr" target="_blank">
							<i class="fa fa-3x fa-twitter" aria-hidden="true"></i>
							</a>
							&nbsp;

							<a href="https://fr.pinterest.com/" target="_blank">
							<i class="fa fa-3x fa-pinterest-square" aria-hidden="true"></i>
							</a>

						</div>
					</div>
				</div>
			</div>
		</div>

	</footer>

</div>
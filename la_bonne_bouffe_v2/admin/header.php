<?php

require_once '../inc/connect.php';


?>

<header>
    
        <nav class="wrapper-header-back">

            <div id="logo"><img src="../img/logo-back.png"></div>

            <div class="menu-header-back">
                <ul>

                   <?php if(isset($_SESSION['permission']) && $_SESSION['permission'] == 2): ?>  
                        <a href="add_user.php"><li>AJOUTER UN UTILISATEUR</li></a>
                        <a href="list_users.php"><li>LISTE DES UTILISATEURS</li></a>
                        <a href="edit_header.php"><li>EDITER SLIDER</li></a>
                    <?php endif; ?>
                    <?php if(!empty($_SESSION)): ?>
                        <a href="my_profile.php"><li>MON PROFIL</li></a>
                        <a href="add_recipe.php"><li>AJOUTER UNE RECETTE</li></a>
                        <a href="list_recipe.php"><li>LISTE RECETTE</li></a>
                        <a href=""><li><i class="fa fa-user-times"></i> SE DECONNECTER</li></a> 
                    <?php else: ?> 
                        <a href="index.php"><li><i class="fa fa-user"></i> SE CONNECTER</li></a>
                                      
                    <?php endif; ?>
                                     
                    
                </ul>
            </div>

        </nav>


</header>
    	

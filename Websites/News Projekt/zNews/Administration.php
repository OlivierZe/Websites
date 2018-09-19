<!DOCTYPE html>

<?php

    // Session Handling

    session_start();
    
    $sUsername = "";
    
    if(isset($_SESSION['login_user']))
    {
        $sUsername = $_SESSION['login_user'];
    } 
    else 
    {
        echo "<script language='javascript' type='text/javascript'> location.href='indexphp.php'</script>";
    }
?>


<html>
    <head>
        <title>zNews - Administrator</title>
        <link href="style/style.css" type="text/css" rel="stylesheet">
        
    </head>
    
    <body>
            
        
        <div class="content">
            <div class="Adminbereich">
                
                
                    <?php
                                
                        if ($sUsername != "")
                        {
                             echo "<h1>Profil " .$sUsername . "</h1>";
                        } else {
                            
                        }
                                
                    ?>  
                
                <button class="Button" onclick="location.href='PasswortAendern.php'">Passwort &auml;ndern</button>
                <button class="Button" onclick="location.href='NewsBearbeiten.php'">Newsbereich</button>
                
                <button class="Zurueck" onclick="location.href='index.php'">Zur&uuml;ck</button>
                
            </div>
            
        </div>
        

    </body>
</html>
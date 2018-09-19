<!DOCTYPE html>

<?php
	require_once('Konstanten.php'); 

    include 'myDBConnMgr.php';
    $myDB = new myDBConnMgr();  

?>

<html>
    <head>
        <title>zNews - Registrierung</title>
        <link href="style/style.css" type="text/css" rel="stylesheet">

        
        
        
    </head>
    
    <body>


        <div class="RegisterForm">
            <h1 style="text-align: center">Registrieren</h1>

            <form method="POST" class="MainForm">
                
                <div class="Form">
                
                <label><b>Username</b></label>
                <input type="text" placeholder="Username..." name="inUsername">

                <label><b>Passwort</b></label>
                <input type="password" placeholder="Passwort..." name="inPasswort">
                    
                <label><b>eMail</b></label>
                <input type="email" placeholder="eMail..." name="inEmail">
        
                <button class="Register_Button" type="submit" name="btnRegister">Registrieren</button>
                </div>

                <div class="Cancel">
                    <button type="button" class="cancelbtn" onclick="location.href='index.php'">Abbrechen</button>
                </div>
            
            
            </form>
        </div>
        
        
                
        
        <?php
        
         if(isset($_POST['btnRegister']))
         {
                //userid und passwort sind NICHT case-sensitive (einfach alles capslock)
                $sUsername = strtoupper(trim($_POST['inUsername']));
                $sPasswort = strtoupper(trim($_POST['inPasswort']));
                $sEmail = trim($_POST['inEmail']);
             
                $sUsername = str_replace(" ", "", $sUsername); // ich will keine blanks, auch nicht innerhalb des namens
                $sPasswort = str_replace(" ", "", $sPasswort); // passwörter ohne blank
                $sEmail = str_replace(" ", "", $sEmail); // email ohne blanks

                $bSuccess = false; // standardmaessig immer false
             
                if ((strlen($sUsername) > C_USERNAME_MINLENGTH) && (strlen($sPasswort) > C_PASSWORT_MINLENGTH)) // Werte aus Konstanten holen
                {				
					// nur wenn die validierung erfolgreich, dann registriere ich den user
                    $bSuccess = $myDB->RegisterUser($sUsername, $sPasswort, $sEmail);
                }
				else 
				{
					echo "<script language='javascript' type='text/javascript'> alert('" . C_MSG_MINIMUM_LENGTH . "')</script>";
				}
             
             
             
             
             
                if($bSuccess == true)
                {
                    
                    echo "<script language='javascript' type='text/javascript'> alert('" . C_MSG_REGISTRIERUNG_OK . "')</script>";

                    echo "<script language='javascript' type='text/javascript'> location.href='index.php'</script>";
                }
                else
                {
                    echo "<script language='javascript' type='text/javascript'> alert('" . C_MSG_FEHLERHAFT . "')</script>";
                }
            }            
        ?>
                
    </body>
</html>

<!DOCTYPE html>

<?php
	require_once('Konstanten.php'); 

    include 'myDBConnMgr.php';
	$myDB = new myDBConnMgr();       

    // Session Handling

    session_start();
    
    $sUsername = "";
    
    if(isset($_SESSION['login_user']))
    {
        $sUsername = $_SESSION['login_user'];
    } else 
    {
        
        echo "<script language='javascript' type='text/javascript'> location.href='index.php'</script>";
        
    }

?>


<html>
    <head>
        <title>zNews - Login</title>
        <link href="style/style.css" type="text/css" rel="stylesheet">
        
    </head>
    
    <body>


        <div class="LoginForm">
            <h1 style="text-align: center">Passwort &auml;ndern</h1>
            
            <form method="POST" class="MainForm">
                
                <div class="Form">
                
                <label><b>Altes Passwort</b></label>
                <input type="text" placeholder="Altes Passwort..." name="inPasswort_alt" required>

                <label><b>Neues Passwort</b></label>
                <input type="password" placeholder="Neues Passwort..." name="inPasswort_neu" required>
                    
                <label><b>Neues Passwort best&auml;tigen</b></label>
                <input type="password" placeholder="Neues Passwort..." name="inPasswort_neu_2" required>
                
                <div class="changePW">
                    <button type="submit" name="btnPWChange" class="changePWbtn">Passwort &auml;ndern</button>
                </div>
                    
                </div>

                <div class="Cancel">
                    <button type="button" class="cancelbtn" onclick="location.href='index.php'">Abbrechen</button>
                </div>
            
            
            </form>
        </div>
        
        <?php
        
        if(isset($_POST['btnPWChange']))
        {
            $sUsername = strtoupper(trim($_SESSION['login_user']));
            $sPasswortAlt = strtoupper(trim($_POST['inPasswort_alt']));
            $sPasswortNeu = strtoupper(trim($_POST['inPasswort_neu']));
            $sPasswortNeu2 = strtoupper(trim($_POST['inPasswort_neu_2']));

			if ($sPasswortNeu != $sPasswortNeu2)
			{
				echo "<script language='javascript' type='text/javascript'> alert('" .C_MSG_PASSWORTCONFIRMATION_NOK. "')</script>";
			}
            
            $bSuccess = $myDB->ChangePasswort($sUsername, $sPasswortAlt, $sPasswortNeu);

             if($bSuccess == true)
             {                                     
				echo "<script language='javascript' type='text/javascript'> alert('" .C_MSG_PASSWORTCHANGE_OK. "')</script>";
                echo "<script language='javascript' type='text/javascript'> location.href='index.php'</script>";
             }
                else
                {
                    echo "<script language='javascript' type='text/javascript'> alert('" .C_MSG_PASSWORTCHANGE_NOK. "')</script>";

                }
        }
		
        ?>                                
    </body>
</html>

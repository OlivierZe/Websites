<!DOCTYPE html>

<?php
	require_once('Konstanten.php'); 

    include 'myDBConnMgr.php';
    $myDB = new myDBConnMgr();  
?>

<html>
    <head>
        <title>zNews - Login</title>
        <link href="style/style.css" type="text/css" rel="stylesheet">

    </head>
    
    <body>


        <div class="LoginForm">
            <h1 style="text-align: center">Login</h1>
            
            <form method="POST"  class="MainForm">
                
                <div class="Form">
                
                <label><b>Username</b></label>
                <input type="text" placeholder="Username..." name="inUsername" required>

                <label><b>Passwort</b></label>
                <input type="password" placeholder="Passwort..." name="inPasswort" required>
        
                <button class="Login_Button" type="submit" name="btnLogin">Einloggen</button>
                </div>

                <div class="Cancel">
                    <button type="button" class="cancelbtn" onclick="location.href='index.php'">Abbrechen</button>
                </div>
            
            
            </form>
        </div>
        
        <?php
        
        if(isset($_POST['btnLogin']))
        {
            session_start();
            
            $sUsername= strtoupper(trim($_POST['inUsername']));
            $sPasswort= strtoupper(trim($_POST['inPasswort']));
            
            $sUsername = str_replace(" ", "", $sUsername);
            $sPasswort = str_replace(" ", "", $sPasswort);
                
            $bSuccess = $myDB->CheckLogIn($sUsername, $sPasswort);

             if($bSuccess == true)
             {
                    
                    $_SESSION['login_user']=$sUsername;
                 
                    echo "<script language='javascript' type='text/javascript'> location.href='index.php'</script>";
                }
                else
                {
                    echo "<script language='javascript' type='text/javascript'> alert('" .C_MSG_LOGIN_NOK. "')</script>";

                }
        }
        
        ?>
        
        
        
        

    </body>
</html>

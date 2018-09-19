<!DOCTYPE html>

<?php
	require_once('Konstanten.php'); 

    include 'myDBConnMgr.php';

    $myDB = new myDBConnMgr();  
?>

<html>
    <head>
        <title>zNews - News Hinzuf&uuml;gen</title>
        <link href="style/style.css" type="text/css" rel="stylesheet">
        <script src="//cdn.ckeditor.com/4.8.0/basic/ckeditor.js"></script>

    </head>
    
    <body>
        
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
            echo "<script language='javascript' type='text/javascript'> location.href='index.php'</script>";
        }
        
?>


        <div class="LoginForm">
            <h1 style="text-align: center">News Hinzuf&uuml;gen</h1>
            
            
            <form method="POST" class="MainForm" enctype="multipart/form-data">
                
                <div class="Form">
                
                    <label><b>Titel</b></label>
                    <input type="text" placeholder="Titel..." maxlength="<?php echo C_MAXLEN_TITLE; ?>" name="inTitel" required>

                    <label><b>Text</b></label>
                    <textarea class="ckeditor" name="inText" maxlength="<?php echo C_MAXLEN_CKEDITOR; ?>" required></textarea>
                    
                    <label><b>G&uuml;ltig von</b></label>
                    <div class="gueltig">
                        <input class="gueltigInput" type="datetime-local" name="inGueltigVon" value="2018-03-06T19:32" required>
                    </div>
                    
                    <label><b>G&uuml;ltig bis</b></label>
                    <div class="gueltig">
                        <input class="gueltigInput" type="datetime-local" name="inGueltigBis" value="2018-03-06T19:32" required>
                    </div>
                    

                    <label><b>Bild</b> <i>(Bild sollte im Format 16:9 sein)</i></label>
                    <input type="file" name="inBild">

                    <label><b>Kategorie</b></label>
                    <select name="inKategorie" class="Kategorie">
                        <option value="" disabled selected>Kategorie ausw&auml;hlen...</option>
                        <?php

                        // Kategorieliste aus der Datenbank holen (Array)
                        $Kategorien = $myDB->GetKategorieList();

                        $iLaenge = $Kategorien->num_rows;

                        for($i = 0; $i < $iLaenge; $i++)
                        {
                            // jede einzelne kategorien in die listbox abfuellen
                            $row = $Kategorien->fetch_assoc();
                            echo "<option value='" .$row["id"]. "'>" .$row["Kategorie"]. "</option>";
                        }
            
                        ?>                    

                    </select>
                    
                    <div class="newshinzu">
                        <button class="newshinzu_Button" type="submit" name="btnNewsHinzu">News hinzuf&uuml;gen</button>
                    </div>
                
                </div>

                <div class="Cancel">
                    <button type="button" class="cancelbtn" onclick="location.href='index.php'">Abbrechen</button>
                </div>
            </form> 
            
            
            
        </div>
        
        
        <?php

        if(isset($_POST["btnNewsHinzu"]))
        {
            $sTitel = trim($_POST['inTitel']);
            $sText = trim($_POST['inText']);
			$sBildName = $_FILES["inBild"]["name"];
			$sBildTemp = $_FILES["inBild"]["tmp_name"];
			$iSize = $_FILES["inBild"]["size"];
            $iKategorie = $_POST["inKategorie"];
            $dGueltigVon = $_POST["inGueltigVon"];
            $dGueltigBis = $_POST["inGueltigBis"];
            
            $bSuccessBild = $myDB->UploadPicture($sBildTemp, $sBildName, $iSize); 

            if ($bSuccessBild = true)
            {
				echo "<script>alert('" .C_MSG_BILD_HINZUFUEGEN_OK. "');</script>";
            }
            else
            {
				echo "<script>alert('" .C_MSG_BILD_HINZUFUEGEN_NOK. "');</script>";
            }

            if ($bSuccessBild == true)
            {
                $bSuccessAdd = $myDB->AddNews($sTitel, $sText, $sBildName, $sUsername, $iKategorie, $dGueltigVon, $dGueltigBis);

                if ($bSuccessAdd ==true)
                {
                    echo "<script>alert('" .C_MSG_NEWS_HINZUFUEGEN_OK . "');</script>";
                    echo "<script language='javascript' type='text/javascript'> location.href='index.php'</script>";
                }
                else
                {            
                    echo "<script>alert('" .C_MSG_NEWS_HINZUFUEGEN_NOK ."');</script>";
                }
            }
        }
        
        ?>

    </body>
</html>

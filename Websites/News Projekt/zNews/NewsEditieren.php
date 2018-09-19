<!DOCTYPE html>

<?php
	require_once('Konstanten.php'); 

    include 'myDBConnMgr.php';
    $myDB = new myDBConnMgr();  
?>

<html>
    <head>
        <title>zNews - News Editieren</title>
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
            
            $id = $_GET["id"];

            $myNewsObjekt = $myDB->GetNewsItem($id);	// myNewsObjekt ist ein zweidimensinoaler Array (Result von DB)
			$row = $myNewsObjekt->fetch_assoc();		// um den ersten Datensatz zu holen (hier ist eh nur einer vorhanden)
        } 
        else    
        {
            echo "<script language='javascript' type='text/javascript'> location.href='index.php'</script>";
        }
        
        ?>


        <div class="LoginForm">
            <h1 style="text-align: center">News Editieren</h1>
            
            
            
            
            <form method="POST" class="MainForm" enctype="multipart/form-data">
                
                <div class="Form">
                
                    <label><b>Titel</b></label>
                    <input type="text" placeholder="Titel..." maxlength="30" name="inTitel" value="<?php  echo $row['name']; ?>" required>

                    <label><b>Text</b></label>
                    <textarea class="ckeditor" required=required maxlength="6000" name="inText"><?php  echo $row['inhalt']; ?></textarea>
                    
                    <label><b>G&uuml;ltig von</b></label>
                    <div class="gueltig">
                        <input class="gueltigInput" type="datetime-local" name="inGueltigVon" value="<?php echo str_replace(" ", "T", $row['GueltigVon']); ?>" required>
                    </div>
                    
                    <label><b>G&uuml;ltig bis</b></label>
                    <div class="gueltig">
                        <input class="gueltigInput" type="datetime-local" name="inGueltigBis" value="<?php echo str_replace(" ", "T", $row['GueltigBis']); ?>" required>
                    </div>
                
                    <label><b>Bild</b><i>(Das Bild sollte im Format 16:9 sein)</i></label>
                    <input type="file" name="inBild">
                    
                    <label><b>Kategorie</b></label>
                    <select name="inKategorie" class="Kategorie">
                        <option value="<?php  echo $row['idKategorie']; ?>" selected><?php echo $row['kategorie']; ?></option>
                        <?php
                    
                        $Kategorien = $myDB->GetKategorieList();

                        $iLaenge = $Kategorien->num_rows;

                        for($i = 0; $i < $iLaenge; $i++)
                        {
                            $row = $Kategorien->fetch_assoc();
                            echo "<option value='" .$row['id']. "'>" .$row['Kategorie']. "</option>";
                        }
                                    
                        ?>                    

   
                    </select>
        
                <button class="newsedit_Button" type="submit" name="btnNewsEdit">News editieren</button>
                </div>

                <div class="Cancel">
                    <button type="button" class="cancelbtn" onclick="location.href='NewsBearbeiten.php'">Abbrechen</button>
                </div>
            </form>
        </div>
        
        
        <?php
        
        if(isset($_POST["btnNewsEdit"]))
        {
            $sTitel = trim($_POST['inTitel']);
            $sText = trim($_POST['inText']);
			$sBildName = $_FILES["inBild"]["name"];
			$sBildTemp = $_FILES["inBild"]["tmp_name"];
			$iSize = $_FILES["inBild"]["size"];
            $dGueltigvon = $_POST['inGueltigVon'];
            $dGueltigbis = $_POST['inGueltigBis'];
            $iKategorie = $_POST['inKategorie'];    /* ID der Kategorie */
            	
            if ($sBildName != "") // Bild nur updaten, wenn was drin steht
			{
				$bSuccessBild = $myDB->UploadPicture($sBildTemp, $sBildName, $iSize);
				
				if ($bSuccessBild == true)
				{
					echo "<script>alert('" .C_MSG_BILD_AENDERN_OK. "');</script>";
				}
				else 
				{					
					echo "<script>alert('" .C_MSG_BILD_AENDERN_NOK. "');</script>";
				}
			}
            else // Bild ist leer
            {            
                echo "<script>alert('" .C_MSG_BILD_AENDERN_KEINBILD. "');</script>";
				$bSuccessBild = true;
            }
			
			// nur wenn das Bild erfogreich hochgeladen (oder leer gelassen) wird, dann update ich auch die news db
			if ($bSuccessBild == true) 
			{
				// und jetzt die DB updaten
				$bSuccessAdd = $myDB->UpdateNews($id, $sTitel, $sText, $iKategorie, $dGueltigvon, $dGueltigbis, $sBildName, $sUsername);
			}

			// wenn die NEWS erfolgreich in der DB updated wurden, dann Meldung
			if ($bSuccessAdd == true)
            {
				echo "<script>alert('" .C_MSG_NEWS_AENDERN_OK. "');</script>";
                echo "<script language='javascript' type='text/javascript'> location.href='index.php'</script>";
            }
            else
            {            
				echo "<script>alert('" .C_MSG_NEWS_AENDERN_NOK."');</script>";
            }           
        }
            
        ?>
        

    </body>
</html>

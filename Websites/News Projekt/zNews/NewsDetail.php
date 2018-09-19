<!DOCTYPE html>

<?php
	require_once('Konstanten.php'); 

	include 'myDBConnMgr.php';
    $myDB = new myDBConnMgr();  

	$id = $_GET['id'];  // holen url paramter id= 

	$myNewsObjekt = $myDB->GetNewsItem($id);
	$row = $myNewsObjekt->fetch_assoc();
?>


<html>
    <head>
        <title>zNews - Home</title>
        <link href="style/style.css" type="text/css" rel="stylesheet">
        
    </head>
    
    <body>
        <div class="NewsDetail">
            <div class="NewsDetail_Bild">
                <img src='<?php echo $row["bild"]; ?>' />
            </div>
            
            <div class="NewsDetail_Titel">
                <h1><?php echo $row["name"]; ?></h1>
            </div>
        
            <div class="NewsDetail_Text">
                <p><?php echo $row["inhalt"]; ?></p>
            </div>
        
            <div class="NewsDetail_Kategorie">
                <p><?php echo $row["kategorie"]; ?></p>
            </div>
        
            <div class="NewsDetail_Autor">
                <p><?php echo $row["autor"]; ?></p>
            </div>
        
        </div>
    </body>
    
    
</html>
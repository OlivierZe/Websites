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
    } 

?>

<html>
    <head>
        <title>zNews - Home</title>
        
        <link href="style/style.css" type="text/css" rel="stylesheet">
        <meta name="author" content="Olivier Zelger">
        <meta name="title" content="zNews">
        <meta charset="utf-8">
        
    </head>
    
    <body>
            <header>
                 
                <nav>                    
                    <div class="Logo">
                        <a href="index.php"><img src="Bilder/LOGO_zNEWS.png"></a>
                    </div>
                    
                    <div class="menu">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="Archiv.php">Archiv</a></li>
                            
                            <?php
                                
                                if ($sUsername != "")
                                {
                                     echo "<li><a href='Administration.php'>Profil " .$sUsername . "</a></li>";

                                } else {

                                }
                                
                            ?>            
                            
                            
                        </ul>
                        
                        <ul class="LogIN_Register">
                            
                            <?php
                                
                                if ($sUsername != "")
                                {
                                     echo "<li><a href='Logout.php'>Logout</a></li>";

                                } else {
                                     echo "<li><a href='Login.php'>Login</a></li>";

                                }
                                
                            ?>                            
                            
                            <li><a href="Registrieren.php">Registrieren</a></li>
                            
                        </ul>
                        
                    </div>
                </nav>
                
                
                <div class="Start">
                    
                    <img class="Start_Logo" src="Bilder/zNEWS_Banner.png" />
                    
                    
                </div>
                
                
                
            </header>
            
        
        <div class="content">
            <div class="skewedDIV"></div>
                 
            <div class="News">
                <div class="NewsReihe">
                    
                    <form method="POST" class="FormFilter">
                        <div class="Filtern">
                            
                            <label><b>Filtern</b></label>
                            <select name="inKategorie" class="Kategorie" onchange="this.form.submit()">
                            <option value="" selected>Kategorie ausw&auml;hlen...</option>
                            <option value="">Alle anzeigen</option>
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
                        
                        </div>
                    </form>
                    
                    
                    
                    <?php              
                            // ich muss beides abfragen. die erste abfrage wirft eine exception beim ersten aufruf.
                            // dies ist aber durch isset abgefangen
                            // wenn ich aber extra ALLE news sehen möchte, dann ist es gesetzt aber mit Wert einem leeren String
                            // also muss ich das auch abfragen
                            if (isset($_POST['inKategorie']) && ($_POST['inKategorie'] != ""))
                            {
                                 // dann holen wir die sortierte liste nach kategorie
                                $sAusgewaehlteKategorie = $_POST['inKategorie'];
                                $myNewsListe = $myDB->GetNewsListeAktivGefiltert($sAusgewaehlteKategorie);
                            }
                            else
                            { 
                                // sonst holen wir die gesamte liste (unsortiert)
                                $myNewsListe = $myDB->GetNewsListeAktiv();
                            }
                    
                                // wir zeigen alle an
                        
                                $iLen = $myNewsListe->num_rows;

                                for($i = 0; $i < $iLen; $i++)
                                {
                                    $row = $myNewsListe->fetch_assoc();

                                    echo "<div class='news'>";
                                    echo "<div class='NewsTitel'>";
                                    echo "<h3>" . $row["name"]  . "</h3>";
                                    echo "</div>";
                                    echo "<div class='NewsBild'>";
                                    echo "<a href='NewsDetail.php?id=" .$row["id"].  "'><img src='" .$row["bild"] . "'/></a>";
                                    echo "</div>";
                                    echo "<div class='NewsKategorie'>";
                                    echo "<p>" .$row["Kategorie"] . "</p>";
                                    echo "</div>";
                                    echo "</div>";
                         }
                            
                        ?> 
                    
                </div>
            </div>
            
        </div>
        

    </body>
</html>
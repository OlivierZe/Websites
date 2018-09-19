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
        
    </head>
    
    <body>
            <header>
                 
                <nav>                    
                    <div class="Logo">
                        <a href="#"><img src="Bilder/LOGO_zNEWS.png"></a>
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

            <div class="AddNews">
                <button class="AddNews_Button" onclick="location.href='NewsHinzufuegen.php'">News hinzufügen</button>
            </div>
            
            <div class="News">
                <div class="NewsReihe">
                    
                    
                    
                    <?php                 
							$myNewsListe = $myDB->GetNewsListeVonUser($sUsername);
							$iLen = $myNewsListe->num_rows;                            

                            for($i = 0; $i < $iLen; $i++)
                            {
                                $row = $myNewsListe->fetch_assoc();
                                
                                echo "<div class='news'>";
                                echo "<div class='NewsTitel'>";
                                echo "<h3>" . $row["name"]  . "</h3>";
                                echo "</div>";
                                echo "<div class='NewsBild'>";
                                echo "<a href='NewsDetail.php?id=" .$row["id"]. "'><img src='" .$row["bild"] . "'/></a>";
                                echo "</div>";
                                echo "<div class='NewsKategorie'>";
                                echo "<p>" .$row["kategorie"] . "</p>";
                                echo "</div>";
                                echo "<div class='Edit'>";
                                echo "<a href='NewsEditieren.php?id=" .$row["id"]. "'>Editieren</a>";
                                echo "</div>";
                                echo "<div class='Delete'>";
                                echo "<a href='Loeschen.php?id=" .$row["id"]. "'>L&ouml;schen</a>";
                                echo "</div>"; 
                                echo "</div>";
                            }
                            
                        ?> 
                    
                </div>
            </div>
        </div>
        

    </body>
</html>
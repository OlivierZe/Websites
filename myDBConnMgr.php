<?php

require_once('Konstanten.php');

class myDBConnMgr
{
	private $conn;


    public function __construct() 
	{		
        $this->conn = new mysqli("localhost", "root", "", "mynews");
        
        if ($this->conn->connect_error) 
		{
			$this->conn = "";	    
			die("Connection failed: " . $this->conn->connect_error);
		}

	}
    
	function __destruct() 
	{
        $this->conn->close(); 
    }
		

	/* loeschen eines Datensatzes */
	public function DeleteNews($id, $sUsername)
	{
		$bReturn = false; // initial immer false

        $myNewsObjekt = $this->GetNewsItem($id); // holen des news objekts
		$row = $myNewsObjekt->fetch_assoc();
                
		// und nur dann löschen, wenn der autor dieses newsobjekts dem eingeloggtem user entspricht (oder admin)
        if($row["autor"] == $sUsername or $sUsername == strtoupper("admin"))
        {
            $sql="DELETE from tblNews WHERE tblNews.id = " .$id;
            $bReturn = $this->conn->query($sql); // nur hier kann bReturn true werden
        }
        else
        {
            $bReturn = false;
        }

		return $bReturn;
	}
    
    // bestehende news updaten
    public function UpdateNews($id, $sTitel, $sText, $iKategorie, $dGueltigVon, $dGueltigBis, $sBild, $sUsername)
    {
        $bSuccess = false; // initial immer false
                
        if ($sBild != "")  
        {
            $sSQL="UPDATE tblNews SET name='" . $sTitel. "', inhalt='" .$sText. "', idKategorie=" .$iKategorie . ", bild='Bilder/" .$sBild. "', GueltigVon='".$dGueltigVon."', GueltigBis='" .$dGueltigBis. "' WHERE id=".$id;
        }
        else 
        {   // falls beim update kein Bild angegeben wird, dann lasse ich das bestehende Bild. Es wird dann nur die News DB ohne Bildangabe updated
            $sSQL="UPDATE tblNews SET name='" . $sTitel. "', inhalt='" .$sText. "',  idKategorie=" .$iKategorie . ", GueltigVon='".$dGueltigVon."', GueltigBis='" .$dGueltigBis. "' WHERE id=".$id;
        }
                                
        $bSuccess = $this->conn->query($sSQL); // nur hier kann bReturn true werden
        
        return $bSuccess;        
    }
		
	// ausgeben aller news-beiträge des eingeloggten Autors (wir aufgerufen für die Bearbeitung von News)
	// es prüft, dass nur der eingeloggte Autor oder der Admin die News ändern kann
	public function GetNewsListeVonUser($sUsername)
	{
        if ($sUsername=='ADMIN')
        {
            // der ADMIN sieht immer alle News. Ist zwar nicht so schön, dass der ADMIN hardcodiert ist, aber das könnte man später noch über eine Art RechteRolle löschen.
            $sSQL = "SELECT tblNews.id as id, tblNews.name as name, tblNews.inhalt as inhalt, tblNews.bild as bild, tblNews.autor as autor, tblKategorie.id as idkategorie, tblKategorie.Kategorie as kategorie from tblNews inner join tblKategorie on tblNews.idKategorie=tblKategorie.id order by tblNews.id DESC";
        }
        else
        {
            // es werden nur die news angezeigt, die der übergebene USERNAME selbst erstellt hatte
            $sSQL = "SELECT tblNews.id as id, tblNews.name as name, tblNews.inhalt as inhalt, tblNews.bild as bild, tblNews.autor as autor, tblKategorie.id as idkategorie, tblKategorie.Kategorie as kategorie from tblNews inner join tblKategorie on tblNews.idKategorie=tblKategorie.id WHERE tblNews.autor = '" .$sUsername. "' order by tblNews.id DESC";
        }
        
        // feuern des SQL Queries
		$newsliste = $this->conn->query($sSQL); 

      
		return $newsliste;
	}

        
	// Alle inaktiven News holen (Archiv)
    public function GetNewsListeArchiv()
	{
        $dHeute = date("Y-m-d H:i:s"); // aktuelles Datum/Zeit

        $sSQL = "SELECT tblNews.id, tblNews.name, tblNews.inhalt, tblNews.bild, tblNews.autor, tblKategorie.Kategorie from tblNews inner join tblKategorie on tblNews.idKategorie=tblKategorie.id WHERE GueltigBis <'" . $dHeute. "' order by tblNews.id DESC";
        
		$newsliste = $this->conn->query($sSQL);

		return $newsliste;
	}
    
    public function GetNewsListeArchivGefiltert($idKategorie)
	{
        $dHeute = date("Y-m-d H:i:s"); // aktuelles Datum/Zeit

        $sSQL = "SELECT tblNews.id, tblNews.name, tblNews.inhalt, tblNews.bild, tblNews.autor, tblKategorie.Kategorie from tblNews inner join tblKategorie on tblNews.idKategorie=tblKategorie.id WHERE tblNews.idKategorie = '" .$idKategorie. "' AND GueltigBis <'" . $dHeute. "' order by tblNews.id DESC";
        
		$newsliste = $this->conn->query($sSQL);

		return $newsliste;
	}
    
	 // Alle im Moment aktuellen News holen
    public function GetNewsListeAktiv()
	{       
        $dHeute = date("Y-m-d H:i:s"); // aktuelles Datum/Zeit

        $sSQL = "SELECT tblNews.id, tblNews.name, tblNews.inhalt, tblNews.bild, tblNews.autor, tblKategorie.Kategorie from tblNews inner join tblKategorie on tblNews.idKategorie=tblKategorie.id WHERE GueltigVon <='" .$dHeute. "' AND GueltigBis >='" . $dHeute. "' order by tblNews.id DESC";
        		
        //feuern SQL Query
        $newsliste = $this->conn->query($sSQL);
		
		return $newsliste;
	}
    
      public function GetNewsListeAktivGefiltert($idKategorie)
      {
        $dHeute = date("Y-m-d H:i:s"); // aktuelles Datum/Zeit

        $sSQL = "SELECT tblNews.id, tblNews.name, tblNews.inhalt, tblNews.bild, tblNews.autor, tblKategorie.Kategorie from tblNews inner join tblKategorie on tblNews.idKategorie=tblKategorie.id WHERE tblKategorie.id='" .$idKategorie. "' AND GueltigVon <='" .$dHeute. "' AND GueltigBis >='" . $dHeute. "' order by tblNews.id DESC";
        		
        //feuern SQL Query
        $newsliste = $this->conn->query($sSQL);
		
		return $newsliste;	   
      
      }
	
	

	/* hinzufuegen eines neuen news-beitrages in die DB */
	public function AddNews($sName, $sInhalt, $sBild, $sAutor, $iKategorie, $dGueltigVon, $dGueltigBis)
	{
		$bReturn = false;

		$sSQL = "INSERT INTO tblNews (name, inhalt, bild, autor, idkategorie, GueltigVon, GueltigBis) values ('" .$sName . "', '" .$sInhalt ."', 'Bilder/" . $sBild . "', '". $sAutor ."', ". $iKategorie . ", '" .$dGueltigVon. "', '" .$dGueltigBis. "')"; 
        
		$bReturn = $this->conn->query($sSQL);

		return $bReturn;
	}
    
	// Kategorie Liste laden (ich lese die DB und liefere einfach nur den Array zurueck)
    public function GetKategorieList()
    {
        $sSQL = "SELECT id, Kategorie FROM tblKategorie ORDER BY Kategorie asc";
        
        $result = $this->conn->query($sSQL);
        
        return $result;
    }
    
    // einen neuen User Registrieren
    public function RegisterUser($sUsername, $sPasswort, $sEmail)
	{
		$bReturn = false;
        
        // ursprünglich hatte ich diese Logik eingebaut um abzufangen, dass kein doppelter User erfasst wird.
        // aber ich habe das nachträglich direkt in der Datenbank als UNIQUE Key erfasst. Es wäre also eigentlich die Abfage Exist User nicht nötig
        if($this->ExistUser($sUsername) == false)
        {            
            $sPasswortVerschluesselt = hash('sha256', $sPasswort . $sUsername);
            $sSQL = "INSERT INTO tblUser (username, passwort, email) values ('" .$sUsername . "', '" .$sPasswortVerschluesselt . "', '" . $sEmail . "')";
            $bReturn = $this->conn->query($sSQL);
        }

		return $bReturn;
    }
    
    // prüft, ob es diesen Usernamen in der Datenbank schon gibt
    public function ExistUser($sUsername)
    {
        $bReturn = false;
        
        $sSQL = "SELECT username from tblUser WHERE username = '" .$sUsername . "'";
        
		$result = $this->conn->query($sSQL);
        
        if($result->num_rows > 0)
        {
            $bReturn = true;
        }
                
        return $bReturn;        
    }
    
    
    
    // Verifiziert das Login. Gibt true zurück, wenn der User mit dem passwort übereinstimmt
    public function CheckLogIn($sUsername, $sPasswort)
    {
        $bReturn = false;
        
        $sPasswortVerschluesselt = hash('sha256', $sPasswort . $sUsername);
        
        $sSQL = "SELECT username from tbluser WHERE username = '" .$sUsername . "' AND passwort = '" .$sPasswortVerschluesselt . "'";
                
		$result = $this->conn->query($sSQL);
        
        if($result->num_rows > 0)
        {
            $bReturn = true;
        }
        
        return $bReturn;        
    }
    
    
    
    // ändern des passworts. ich überprüfe auch, ob das alte passwort stimmt, sonst gibts keine änderung
    public function ChangePasswort($sUsername, $sPasswort_alt, $sPasswort_neu)
    {
        $bReturn = false;
                
        if ($this->CheckLogin($sUsername, $sPasswort_alt) == true)
        {
            $sPasswortVerschluesselt = hash('sha256', $sPasswort_neu . $sUsername);
            $sSQL="UPDATE tblUser SET passwort = '" .$sPasswortVerschluesselt . "' WHERE username = '" .$sUsername ."'";
            $bReturn = $this->conn->query($sSQL);
        }
                    
        return $bReturn;
    }

	/* liefert ein News-Eintrag Objekt zurück aufgrund des übergebenen Schlüssels */
	public function GetNewsItem($id)
	{					
        $sSQL="SELECT tblNews.id as id, tblNews.name as name, tblNews.inhalt as inhalt, tblNews.bild as bild, tblNews.autor as autor, tblNews.idKategorie as idKategorie, tblKategorie.Kategorie as kategorie, tblNews.GueltigVon, tblNews.GueltigBis
from tblNews INNER join tblkategorie on tblnews.idKategorie = tblkategorie.id WHERE tblnews.id = " .$id . " ORDER by id desc";
        
		$newsobjekt = $this->conn->query($sSQL);

		return $newsobjekt;
	}

   


	// erster Paramter = Ort, wo PHP die ausgewählte Datei temporär hinlädt
	// zweiter Parameter = Zielort (echter Filename)
	// dritter Parameter = Groesse
	public function UploadPicture($sBildTemp, $sBildName, $iSize)	
	{
		$bSuccess = false;

		$bValidierungGroesse = false;
		$bValidierungDateityp = false;
		
		// pruefen auf erlaubte Dateitypen
		$sBildDateiTyp = strtolower(pathinfo($sBildName, PATHINFO_EXTENSION));

		if($sBildDateiTyp == "jpg" || $sBildDateiTyp == "png" || $sBildDateiTyp == "jpeg" || $sBildDateiTyp == "gif" )
		{			
			$bValidierungDateityp = true;
		}	
				
		// ueberpruefen der Groesse
		if ($iSize < C_MAXSIZE_PICTURE)
		{
			$bValidierungGroesse = true;						
		}

		// nur wenn alle Validierungen erfolgreich, versuchen wir die Datei hochzuladen
		if ($bValidierungGroesse == true and $bValidierungDateityp == true)
		{
			// und nur wenn das geklappt hat, wir der Rueckgabewert true
			$bSuccess = move_uploaded_file($sBildTemp, "Bilder/" . $sBildName);
		}

		// spätere Verbesserungen in diesem Code wäre das Zurückgeben eines Fehlercodes, so dass der aufrufende Client
		// anhand einer Liste auch gleich die richtige Fehlermeldung ausgeben kann.

		return $bSuccess;
	}
}

?>
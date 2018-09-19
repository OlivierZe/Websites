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
    

    $id = $_GET['id'];

    $bSuccess = $myDB->DeleteNews($id, $sUsername);
    
    if ($bSuccess == true)
    {
        echo "<script language='javascript' type='text/javascript'> alert('" .C_MSG_LOESCHEN_OK. "')</script>";
    }
    else
    {
        echo "<script language='javascript' type='text/javascript'> alert('" .C_MSG_LOESCHEN_NOK. "')</script>";
    }

     echo "<script language='javascript' type='text/javascript'> location.href='NewsBearbeiten.php'</script>";

?>
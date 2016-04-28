<?php
$id = $_POST['uid'];
$pass = $_POST['upass'];

// DB parameters for JoomlaHosting
$db_name = "uc6weaoq_cSHou";
$db_user = "uc6weaoq_cSHou";
$user_passwd= "pCKmyi31";
$db_host = "mysqlhost";
// --------------------------------

try {
    $DBH = new PDO('mysql:host='.$db_host.';dbname='.$db_name.,$db_user,$user_passwd);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

//$query = "SELECT id,pass FROM user WHERE id = ".$id." AND pass = ".$pass;
//
//$get = $DBH->prepare($query);
//$get->execute();
//
//if (!$get) {
//    echo "Error: couldn't execute query. ".$get->errorCode();
//    exit;
//}
//
//if ($get->fetchColumn() == 0) {
//    echo "Void query";
//    exit;
//}
//$rows = array();
//$row = $get->fetch(PDO::FETCH_ASSOC);

if($DBH)
{
    echo "Successfully Logged In";
}
else
{
    echo "Wrong Id Or Password";
}
?>
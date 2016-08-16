<?php
$db_host="127.0.0.1"; //hostname
$db_user="bracelet";  //mysql acc/ username
$db_pw="bracelet";  //mysql scc/ password
$db_name="bracelet_db"; //mysql database name
$db = new mysqli("$db_host", "$db_user", "$db_pw");
$db->select_db("$db_name"); 
?>

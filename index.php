<?php
echo "<p>hi, gkdfkgk</p>";
$hostname="localhost";
$username="webuser";
$password="";
$db="temp";
$mysqli=new mysqli($hostname,$username,$password,$db);
if (mysqli_connect_errno()){
	die("error connecting to database: ".mysqli_connect_error());
}
$sql="Select * from 'user_input' where 1";
$result=$mysqli->query($sql) or
	die("Something wrongg with $sql".$mysqli->error);
while ($data=$result->fetch_array(MYSQLI_ASSOC))
{
echo "<p>First entry: $data[input] - $data[user_id]</p?";
}

$sql="insert into 'user_input' ('input', user_id) values ('input from web','webuser@maill.com')";


?>
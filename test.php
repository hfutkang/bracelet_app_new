<form action="" method="post">
 
Name:<input type="text" name="fname">
 
 
Age:<input type="text" name="age">
 
<br />
 
<input type="submit">
 
</form>
 
<?php
$a=$_POST['fname'];
 
$b=$_POST['age'];
 
echo $a."<br />";
 
echo $b."<br />";
?>

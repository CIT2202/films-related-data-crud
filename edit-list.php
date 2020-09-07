<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}

//select all the films
$query = "SELECT * FROM films";
$resultset = $conn->query($query);
$films = $resultset->fetchAll();
$conn=NULL;

?>


<!DOCTYPE HTML>
<html>
<head>
<title>List the films</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
  <h1> Edit films</h1>
<form action="edit.php" method="POST">
<?php
foreach ($films as $film) {
  echo "<p>";
  echo "<label>";
  //outputs a radio button for each film e.g. <label><input type="radio" name="films" value="2" >Winter's Bone</label>
  echo "<input type='radio' name='id' value='{$film["id"]}''>";
  echo "{$film["title"]}</label>";
  echo "</p>";
}
?>
<input type="submit">
</form>
</body>
</html>

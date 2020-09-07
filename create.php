<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}



//select all the certificates
$query = "SELECT * FROM certificates";
$resultset = $conn->query($query);
$certificates = $resultset->fetchAll();

//select all the genres
$query = "SELECT * FROM genres";
$resultset = $conn->query($query);
$genres = $resultset->fetchAll();

$conn=NULL;

?>

<!DOCTYPE HTML>
<html>
<head>
<title>Add new film</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>Add a new film</h1>
<form action="save.php" method="post">
<label for="title">Title:</label>
<input type="text" id="title" name="title">
<label for="year">Year:</label>
<input type="text" id="year" name="year">
<label for="duration">Duration:</label>
<input type="text" id="duration" name="duration">
<label for="certificate">Certificate:</label>
<!-- Output a dropdown menu so the user can select a single certificate -->
<select id="certificate" name="certificate">
<?php
foreach($certificates as $certificate){
	echo "<option value='{$certificate["id"]}'>{$certificate["name"]}</option>";
}
?>
</select>
<!-- Output a checkbox, one for each genre -->
<fieldset>
<legend>Select the film genres</legend>

<?php
foreach($genres as $genre){
	//e.g. "<label for='Thriller'><input type='checkbox' name='genres[]' value='2' id='Thriller'>Thriller</label>";
	// The genres[] means pass the selected items as an array
	echo "<label for='{$genre["name"]}'><input type='checkbox' name='genres[]' value='{$genre["id"]}' id='{$genre["name"]}'>{$genre["name"]}</label>";
}
?>
</fieldset>


<input type="submit" name="submitBtn" value="Add Film">
</form>

</body>
</html>

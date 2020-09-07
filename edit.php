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

//select the details for the chosen film using the id from the query string e.g. edit.php?id=4
$filmId=$_POST['id'];
//prepared statement uses the id to select a single film
$stmt = $conn->prepare("SELECT * FROM films WHERE films.id = :id");
$stmt->bindValue(':id',$filmId);
$stmt->execute();
$film=$stmt->fetch();

//select the currently selected genre ids for the chosen film e.g. if Incredibles we will get ids of 2,3 and 4
$stmt = $conn->prepare("SELECT film_genre.genre_id AS genre_id FROM films
	INNER JOIN film_genre ON films.id=film_genre.film_id
	WHERE films.id = :id");
$stmt->bindValue(':id',$filmId);
$stmt->execute();
$selectedGenres=$stmt->fetchAll();

$selectedGenresArr=[]; //an emtpy array, we will populate this with the ids of genres
foreach($selectedGenres as $genre){
	$selectedGenresArr[]=$genre["genre_id"]; //add the genre id to the array
}

$conn=NULL;

?>

<!DOCTYPE HTML>
<html>
<head>
<title>Edit the film details</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>Edit film details</h1>
<form action="update.php" method="post">
<!-- <input type="hidden"> creates a hidden text field i.e. not visible to the user
	we use it to store the id number of the selected film -->
<input type="hidden" name="id" value="<?php echo $film["id"];?>">
<label for="title">Title:</label>
<input type="text" id="title" name="title" value="<?php echo $film["title"];?>">
<label for="year">Year:</label>
<input type="text" id="year" name="year" value="<?php echo $film["year"];?>">
<label for="duration">Duration:</label>
<input type="text" id="duration" name="duration" value="<?php echo $film["duration"];?>">
<label for="certificate">Certificate:</label>
<!-- Output a dropdown menu so the user can select a single certificate -->
<select id="certificate" name="certificate">
<?php
foreach($certificates as $certificate){
	if($film["certificate_id"]==$certificate["id"]){
		echo "<option value='{$certificate["id"]}' selected>{$certificate["name"]}</option>";
	}else{
		echo "<option value='{$certificate["id"]}'>{$certificate["name"]}</option>";
	}
}
?>
</select>
<!-- Output a checkbox, one for each genre -->
<fieldset>
<legend>Select the film genres</legend>

<?php
foreach($genres as $genre){

	$checked="";
	if(in_array($genre["id"], $selectedGenresArr)){
			$checked="checked";
	}
	echo "<label for='{$genre["name"]}'>";
	echo "<input type='checkbox' name='genres[]' value='{$genre["id"]}' id='{$genre["name"]}' {$checked}>";
	echo $genre["name"];
	echo "</label>";
}
?>
</fieldset>


<input type="submit" name="submitBtn" value="Update film details">
</form>

</body>
</html>

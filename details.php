<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=cit2202', 'cit2202', 'letmein');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
	echo "Oh no, there was a problem" . $exception->getMessage();
}
//the id from the query string e.g. details.php?id=4
$filmId=$_GET['id'];
//prepared statement uses the id to select a the film
//uses a JOIN to get the certificate details
$stmt = $conn->prepare("SELECT films.title, films.year, films.duration, certificates.name AS certificate FROM films
INNER JOIN certificates ON films.certificate_id = certificates.id
WHERE films.id = :id");
$stmt->bindValue(':id',$filmId);
$stmt->execute();
$film=$stmt->fetch(); //only need one row

//run a second statement to get the film's genres
$stmt = $conn->prepare("SELECT genres.name FROM films
INNER JOIN film_genre ON films.id = film_genre.film_id
INNER JOIN genres ON film_genre.genre_id = genres.id
WHERE films.id = :id");
$stmt->bindValue(':id',$filmId);
$stmt->execute();
$genres=$stmt->fetchAll(); //there can be multiple genres

$conn=NULL;
?>


<!DOCTYPE HTML>
<html>
<head>
<title>Display the details for a musician</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<?php
//simple validation to see if we found a film
if($film){
	echo "<h1>{$film['title']} ({$film["certificate"]})</h1>";
	echo "<p>Year:{$film['year']}</p>";
	echo "<p>Duration:{$film['duration']}</p>";
  echo "<p>Genres:</p>";
  echo "<ul>";
  foreach($genres as $genre){
    echo "<li>{$genre["name"]}</li>";
  }
  echo "</ul>";
}
else
{
	echo "<p>Can't find the film</p>";
}
?>
</body>
</html>

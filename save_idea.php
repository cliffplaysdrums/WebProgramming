<!DOCTYPE html>
<html>

<head><title>
Rockets and Stuff - Thank You
</title>
<link rel="stylesheet" type="text/css" href="project2.css">
</head>

<body>
<?php 
/* saves input from "idea_form.html" 
 * Each idea is saved on its own line in the format: <author> description
*/
if (strlen($_POST['author']) > 0 && strlen($_POST['description']) > 0) {
	$author = $_POST['author'];
	$description = $_POST['description'];
} else die('You left a field blank.');
$filename = 'propulsion_ideas.txt';

$complete_entry = "{$author} ~ {$description}\n";

$savefile = fopen($filename, "a") or die("Unable to save your submission.");
fwrite($savefile, $complete_entry);

fclose($savefile);
?>
<center>

<p>
<table>
<tr>
<ul class="navigation_home">
  <li><a class="active" href="project2.html">Homepage</a></li>
  <li><a href="thrust.html">Rocket Thrust</a></li>
  <li><a href="demo2.html">Advanced Propulsion</a></li>
  <li><a href="links.html">Useful Links</a></li>
  <li><a href="aboutus.html">About Us</a></li>
  <li><a href="ideas.php">Propulsion Ideas</a></li>
</ul>
</tr>
<tr><div class="content_home"><h1>
Rockets and Stuff
</h1>

<p>Thank you. Your submission has been saved. Please continue browsing via the links at the top or click <a href="ideas.php">here</a> to go back.</p>
</div>
</tr>
</table>
</center></p>

</body>

</html>
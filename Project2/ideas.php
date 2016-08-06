<!DOCTYPE html>
<html>
<head><title>Rockets and Stuff - Search and Submit</title>
<link rel="stylesheet" type="text/css" href="project2.css">
<link rel="stylesheet" type="text/css" href="search.css">
</head>

<h1 class="page_heading">Search and Submit</h1>

<body>

<center>
<table>
<tr>
<ul class="navigation">
  <li><a href="project2.html">Homepage</a></li>
  <li><a href="thrust.html">Rocket Thrust</a></li>
  <li><a href="demo2.html">Advanced Propulsion</a></li>
	<li><a class="active" href="ideas.php">Propulsion Ideas</a></li>
  <li><a href="links.html">Useful Links</a></li>
  <li><a href="aboutus.html">About Us</a></li>
</ul>
</tr>
</table>
<div class="content">
<h1>
Ideas
</h1>
<div class="left">
<h3>Search</h3>
<form action="ideas.php" method="post">
<p>Keywords: <input name="keywords" placeholder="plasma, vacuum, ion, etc" type="text" autofocus>
<input type="submit" value="Search"></p>
</form>

<?php
$filename = 'propulsion_ideas.txt';
$trending_filename = 'trending_keywords.txt';
$expire_time = 60; // time before trending words expire if not searched for (in seconds)

// class for storing a keyword, the number of times its searched, and it's last time used
class Tag {
	public $word;
	public $counted;
	public $last_access;
	public function __construct($keyword) {
		$this->word = $keyword;
		$this->counted = 1;
		$this->last_access = time();
	}
}

$trending_file = file_get_contents($trending_filename);
$trending_words = unserialize($trending_file);

/* This code is only necessary if the file is empty

if (!is_array($trending_words)) {
	$default_tag[] = new Tag("electrons");
	$sdata = serialize($default_tag);
	file_put_contents($trending_filename, $sdata);
} */

foreach ($trending_words as $trending_word) {
	if ($trending_word->last_access + $expire_time < time()) {
		$index = array_search($trending_word, $trending_words);
		unset($trending_words[$index]);
	}
}
// renumber array indexes because unset() does not
$trending_words = array_values($trending_words);

// get keywords if any have been provided
if (empty($_POST)) {
	$keyword = '*';
} 
else {
	$keyword = explode(",", $_POST['keywords']);
	for ($i=0; $i<count($keyword); $i++) {
		$match = false;
		$keyword[$i] = trim($keyword[$i]);
		foreach ($trending_words as $trending_word) {
			if (!(strcasecmp($keyword[$i], $trending_word->word))) {
				$trending_word->counted++;
				$trending_word->last_access = time();
				$match = true;
				break;
			}
		}
		if (!$match) {
			$trending_words[] = new Tag($keyword[$i]);
		}
		if (!strcasecmp($keyword[$i], '*')) {
			$keyword = '*';
			break;
		}
	}
}
$sdata = serialize($trending_words);
file_put_contents($trending_filename, $sdata);


echo '<div class="trending" style="font-weight: bold; font-size: 16pt;">Trending: ';
echo '<style="font-weight: normal; font-size: 14pt; color: #DDF">';

$trending_1 = new Tag("");
$trending_2 = new Tag("");
$trending_3 = new Tag("");

$trending_1->counted = 0;
$trending_2->counted = -1;
$trending_3->counted = -2;

// get top 3 trending in linear time
foreach ($trending_words as $trending_word) {
	if ($trending_word->counted > $trending_3->counted) {
		$trending_3 = $trending_word;
		if ($trending_3->counted > $trending_2->counted) {
			$trending_3 = $trending_2;
			$trending_2 = $trending_word;
			if ($trending_2->counted > $trending_1->counted) {
				$trending_2 = $trending_1;
				$trending_1 = $trending_word;
			}
		}
	}
}

// output trending words (if any)
if ($trending_3->counted > 0) {
	echo "{$trending_1->word}, {$trending_2->word}, {$trending_3->word}<br>";
} elseif ($trending_2->counted > 0) {
	echo "{$trending_1->word}, {$trending_2->word}<br>";
} elseif ($trending_1->counted >0) {
	echo "{$trending_1->word}<br>";
}

echo '</div>';

$ideasfile = fopen($filename, "r") or die("Unable to query file");
?>

<div class="submittedIdeas">
<h3>Submitted Propulsion Ideas:</h3>
<table class="submittedIdeasTbl">
<tr><th>Author</th><th>Idea</th></tr>

<?php
// display all results by default, else search for keywords
$results = array();
if ($keyword == '*') {
	while (!feof($ideasfile)) {
		$results[] = fgets($ideasfile);
	}
} 
else {
	while (!feof($ideasfile)) {
		$current_line = fgets($ideasfile);
		$current_line_words = explode(" ", $current_line);
		$breakval = false;
		
		foreach ($keyword as $key) {
			foreach ($current_line_words as $word) {
				$word = preg_replace("/[^a-zA-Z 0-9]+/", "", $word);
				$word = trim($word);
				if (!strcasecmp($key, $word)) {
					$results[] = $current_line;
					$breakval = true;	
					break;		
				}				
			}
			if ($breakval == true) {
				$breakval = false;
				break;				
			}			
		}
	}
}

$result_split = array();
foreach ($results as $result) {
	list($name, $descr) = array_pad(explode('~', $result, 2), 2, null);
	echo '<tr><td>' . $name . '</td>';
	echo '<td>' . $descr . '</td></tr>';
}

fclose($ideasfile) or die("Unable to close file");
?>
</table></div>
</div>

<div class="right">
<h3>Submit your own idea:</h3>
<form method="post" action="save_idea.php">
<p>Name: <input name="author" type="text"></p>
<p>Propulsion Idea:<br>
<textarea name="description" placeholder="Describe your idea here" rows="20" cols="80"></textarea></p>
<input type="submit" value="Submit">
</form>
</div>
</center>
</div>

</body>

</html>
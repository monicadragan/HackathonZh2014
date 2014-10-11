<html>
  <head>

    <title>

    </title>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="font-awesome-4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="bootstrap-3.2.0-dist/css/bootstrap.min.css">

    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <script src="js/fuse.min.js"></script> 

  </head>

<body>

<?php

// Parse the query

$query=$_POST["query"];


function in_arrayi($needle, $haystack) {
	return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

echo "Your query is: $query <br/>";

$queryTokens = array();

$delim = ' ,.!?:;-';
$tok = strtok($query, $delim);
$stopWords = array("the", "a", "of", "an", "in");
$concatenatedWords = "";

while ($tok != false) {
	if(!in_arrayi($tok, $stopWords) ) {
    		array_push($queryTokens, $tok);
		$concatenatedWords .= $tok." ";
	}
	$tok = strtok($delim);

}

// Load the data from the database

$db=mysql_connect("localhost","root","root");
if(!$db)
{
	die("Insucces.");
}

mysql_select_db("interq");
if(mysql_errno())
{
	die("<BR>" . mysql_errno().":".mysqlerror()."<BR>");
}

// Load resources from database

$query = "SELECT * FROM RESOURCES;";

$query = "SELECT * , levenshtein_string(".
			"`Title` , \"".$concatenatedWords."\") ".
			"AS score FROM RESOURCES ".
			"ORDER BY score ASC ".
			"LIMIT 10;";

$result=mysql_query($query);
$num=mysql_num_rows($result); 


?>

<div class="panel panel-default">

  <div class="panel-heading">
	<?php echo "$num resources <br/>"; ?>
  </div>

  <table class="table">

<?php

// Display the table 
for ($i=0; $i<$num; $i++) {
	$id = mysql_result($result, $i, "ID");
	$title = mysql_result($result, $i, "Title");
	$link = mysql_result($result, $i, "Link");
	$author_id = mysql_result($result, $i, "AuthorId");
	$fingerprint = mysql_result($result, $i, "Fingerprint");
	echo "<tr> <td>$id </td> <td> <a href=$link target=alt> $title </a> </td> <td> $author_id </td> <td> $fingerprint </td></tr>";

}

?>

  </table>
</div>


</body>
</html>

<html>
  <head>

    <title>

    </title>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="font-awesome-4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="bootstrap-3.2.0-dist/css/bootstrap.min.css">

    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script> 

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

$query = "SELECT * FROM RESOURCES WHERE levenshtein_string(".
			"`Title` , \"".$concatenatedWords."\") > 0.8";

$result=mysql_query($query);
$num=mysql_num_rows($result); 


?>

<div class="panel panel-default">

  <div class="panel-heading">
	<?php echo "$num resources <br/>"; ?>
  </div>

  <table class="table tablesorter" id="myTable" >
<thead> 
<tr style=\"display:none;\"> 
    <th></th> 
    <th></th> 
    <th></th> 
    <th></th> 
    <th></th> 
</tr> 
</thead> 
<tbody> 
<?php


// Sort table lines accordin to the profile
$user_profile = "101, 147, 1, 2, 214, 2, 3, 4, 12, 200, 201, 202";

// parse user's profile
$exams_user = array();
$delim = ' ,.!?:;-';
$tok = strtok($user_profile, $delim);
array_push($exams_user, $tok);
while ($tok != false) {
	array_push($exams_user, (int)$tok);
	$tok = strtok($delim);
}

sort($exams_user);

// Display the table 
for ($i=0; $i<$num; $i++) {
	$id = mysql_result($result, $i, "ID");
	$title = mysql_result($result, $i, "Title");
	$link = mysql_result($result, $i, "Link");
	$author_id = mysql_result($result, $i, "AuthorId");
	$fingerprint = mysql_result($result, $i, "Fingerprint");

	//parse the fingerprint

	$fingerprint = mysql_result($result, $i, "Fingerprint");

	$exams = array();
	$tok = strtok($fingerprint, $delim);

	while ($tok != false) {
    		array_push($exams, (int)($tok));
		$tok = strtok($delim);
	}

	sort($exams);
	
	// compute the intersection
	$intersection = array();
	$len1=count($exams_user);
	$len2=count($exams);
	$limit=min($len1, $len2);
	$j=0;$k=0;
	while($j<$len1 && $k<$len2)
	{
		if($exams_user[$j]==$exams[$k])
		{
			array_push($intersection, $exams[$k]);
			$j++; $k++;
		}
		elseif($exams_user[$j] < $exams[$k])
		{
			$j++;
		}
		else $k++;
				
	}

	array_push($intersections, count($intersection));
	$no_intersections = count($intersection);

	echo "<tr> <td style=\"display:none;\"> $no_intersections </td> <td style=\"display:none;\">$id </td>".
	" <td> <a href=$link target=\"_new\"> $title </a> </td>".
	" <td> <i class=\"fa fa-user\"></i> $author_id  <i class=\"fa fa-university\"></i> ETHZ</td><td>";

	for($ii=0; $ii<count($exams); $ii++){
		echo "<i class=\"fa fa-check-square\"></i> $exams[$ii]";
	}

	echo "</td></tr>";
	#echo "<tr> <td> $no_intersections </td> <td>$id </td> <td> <a href=$link target=\"_new\"> $title </a> </td> <td> $author_id </td> <td> $fingerprint </td></tr>";
}

?>
</tbody> 
  </table>
</div>

<script>
$(document).ready(function() 
    { 
        $("#myTable").tablesorter( {sortList: [[0,1]]} ); 
    } 
); 
</script>


</body>
</html>

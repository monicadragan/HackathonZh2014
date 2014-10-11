<html>

  <head>

    <title>

    </title>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="font-awesome-4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="bootstrap-3.2.0-dist/css/bootstrap.min.css">
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

  </head>

<body>

<?php

$query=$_POST["query"];

echo "Your query is: $query <br/>";

$words = array();

$delim = ' \n\t,.!?:;';

$tok = strtok($query, $delim);
array_push($words, $tok);
while ($tok != false) {
    array_push($words, $tok);
    $tok = strtok($delim);
}

print_r ($words);

?>

<?php

$db=mysql_connect("localhost","interq","qinter543");
if(!$db)
{
	die("Insucces.");
}

mysql_select_db("interq");
if(mysql_errno())
{
	die("<BR>" . mysql_errno().":".mysqlerror()."<BR>");
}

//---------- load resource

$query="SELECT * FROM RESOURCES";
$result=mysql_query($query);
$num=mysql_num_rows($result);

?>

<div class="panel panel-default">

  <div class="panel-heading">
	<?php echo "$num resources <br/>"; ?>
  </div>

  <table class="table">

<?php  
 
for ($i=0; $i<$num; $i++) {

	$id = mysql_result($result, $i, "ID");
	$title = mysql_result($result, $i, "Title");
	$link = mysql_result($result, $i, "Link");
	$author_id = mysql_result($result, $i, "AuthorId");
	$fingerprint = mysql_result($result, $i, "Fingerprint");
	echo "<tr> <td>$id </td> <td> <a href=$link target=alt> $title </a> </td> <td> $author_id </td> <td> $fingerprint </td></tr>";
	//echo "$id $title <a href=$link target=alt>link</a>  $author_id $fingerprint ";

}

?>

  </table>
</div>


</body>
</html>

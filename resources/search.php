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

5,l<?php

$query=$_POST["query"];

echo "Your query is: $query <br/>";

$queryTokens = array();

$delim = ' ,.!?:;-';

$tok = strtok($query, $delim);

while ($tok != false) {
    array_push($queryTokens, $tok);
    $tok = strtok($delim);
}

print_r ($queryTokens);

?>


<?php

$db=mysql_connect("localhost","root","root");
if(!$db)
{
	die("Insucces.");
}

mysql_select_db("hackzurich");
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
 
$concatenatedTitles = "";
$titlesArray = array(); 
 
for ($i=0; $i<$num; $i++) {

	$id = mysql_result($result, $i, "ID");
	$title = mysql_result($result, $i, "Title");
	$concatenatedTitles .= " "; $concatenatedTitles .= $title;
	array_push($titlesArray, $title);
	$link = mysql_result($result, $i, "Link");
	$author_id = mysql_result($result, $i, "AuthorId");
	$fingerprint = mysql_result($result, $i, "Fingerprint");
	echo "<tr> <td>$id </td> <td> <a href=$link target=alt> $title </a> </td> <td> $author_id </td> <td> $fingerprint </td></tr>";
	//echo "$id $title <a href=$link target=alt>link</a>  $author_id $fingerprint ";

}
  echo "all words from titles: $concatenatedTitles";

?>

<?php

    function in_arrayi($needle, $haystack) {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

//PREPROCESSING
//split into words
echo "The result: '$result' <br/>";

$words = array();

$delim = ' ,.!?:;-';
$stopWords = array("the", "a", "of", "an", "in");

$tok= strtok($concatenatedTitles, $delim);

//!!!! ADD also the words from the query
//store only distinct words and filter by stopwords
while ($tok != false) {
    if(!in_arrayi($tok, $stopWords) ) {
        if(!in_arrayi($tok, $words) ) {
            array_push($words, strtolower($tok) );
        }
    }
    $tok = strtok($delim);
}

echo "The array: ";
print_r ($titlesArray);

echo "The words: ";
print_r ($words);

$n = count($words);
$titlesRepres = array();

//split each title into words and compute a score
for ($i=0; $i<$num; $i++) {
    array_push($titlesRepres, array());
    for($j=0; $j<$n; $j++)
        array_push($titlesRepres[i], 0);
    $tok = strtok($titlesArray[$i], $delim);
    while ($tok != false) {
        $pos = array_search($titlesArray, $tok);
        $titlesRepres[$i][$pos]=1; //activate the position which contains this word
        $tok = strtok($delim);
    }
}



//compute scalar product with the query and choose the heighest score

?>

  </table>
</div>


</body>
</html>

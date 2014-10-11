<html>

  <head>

    <title>

    </title>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="../resources/font-awesome-4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../resources/bootstrap-3.2.0-dist/css/bootstrap.min.css">

    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="../resources/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>

  </head>

<body>

<div class="container">
  <?php

$con=mysqli_connect("localhost","root","root","hackzurich");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: ". mysqli_connect_error() ;
}

// escape variables for security
$title = mysqli_real_escape_string($con, $_POST['title']);
$link = mysqli_real_escape_string($con, $_POST['link']);
$author = mysqli_real_escape_string($con, $_POST['userid']);

$sql="INSERT INTO RESOURCES (title, link, AuthorId, Fingerprint)
VALUES ('$title', '$link', '$author','001100')";

if (!mysqli_query($con,$sql)) {
  die('Error: ' . mysqli_error($con));
}
echo "<p class=\"bg-success\">Resource ".$title." Added!</p>";

mysqli_close($con);
?>


  

<button type="button" class="btn btn-primary btn-lg btn-block">Share another resource</button>
<button type="button" class="btn btn-primary btn-lg btn-block">Find resources</button>

</div>
</body>
</html>

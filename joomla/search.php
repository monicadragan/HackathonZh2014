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

<form class="navbar-form navbar-left" role="search" action = "searchResults.php" method="post">
        <div class="form-group">
          <input name="query" type="text" class="form-control" placeholder="Search">
            <input name="userid" type="hidden" value="<?php echo htmlspecialchars($_GET['userid']); ?>">
        </div>
<button type="submit" class="btn btn-default">Submit</button>
      </form>

</body>
</html>

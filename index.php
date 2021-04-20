<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Short Link Database</title>
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link href="fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="style/sld.css" rel="stylesheet"> 
</head>
<body>
<div id="header">
<img src="pics/logo_sld.png" alt="Logo" class="center">
</div>
<?php
include 'secret.php';

$dbconnect=mysqli_connect($hostname,$username,$password,$db);

if ($dbconnect->connect_error) {
  die("Database connection failed: " . $dbconnect->connect_error);
}

if(isset($_POST['generate'])) {
  $url=$_POST['url'];
  $query = sprintf("SELECT MIN(id) FROM redirects WHERE end_date <=now()"); 
  $result = mysqli_query($dbconnect, $query);
  if (!$result) {
    $message  = 'Ungültige Abfrage: ' . mysqli_error() . "\n";
    $message .= 'Gesamte Abfrage: ' . $query;
   }
  $row = mysqli_fetch_row($result);
  $id = $row[0];
  //$query = sprintf("INSERT INTO redirects VALUES (%s, %s, adddate(now(), +7))", mysqli_real_escape_string($id), mysqli_real_escape_string($url));
  $query = sprintf("UPDATE redirects SET url = '%s', end_date = adddate(now(), +7) WHERE id = '%s'", mysqli_real_escape_string($dbconnect, $url), mysqli_real_escape_string($dbconnect, $id));
    if (!mysqli_query($dbconnect, $query)) {
       $message = 'Ungültige Abfrage: ' . mysqli_error() . "\n";
       $message = 'Gesamte Abfrage: ' . $query;
      ?>
      <div id="middle">
      <p class="center">An Error occurred. No shortlink for you.</p>
      <p><?=$message?></p>
      </div>
      <?php
    } else {
      ?>
      <div id="middle">
      <input type="text" id="urlOutput" name="url" class="center" value="https://sld.link/<?=$id?>" readonly onclick="this.select()">
      <p id="feedback" class="center">Your shortlink is generated and valid for 7 days.</p>
      <p><?=$message?></p>
      </div>
      <?php
    }

}
else {
?>
<div id="middle">
    <form action="" method="POST">
    <label for="homepage"></label>
    <input type="url" id="urlInsert" name="url" class="center" placeholder="https://..." required>
    <button type="submit" name="generate" class="center" id="genButton"><span>Generate</span></button> 
    </form> 
</div>
<?php
}
?>

<footer>
<p>Samuel Schumacher | <i class="fab fa-linkedin"></i> <a href="https://www.linkedin.com/in/blicknix/">Blicknix</a> | 
                               <i class="fab fa-github-square"></i>
                               <a href="https://github.com/blicknix/">Blicknix</a> | 
                                <i class="fab fa-twitter-square"></i>
                               <a href="https://twitter.com/blicknix3/">@blicknix3</a> | 
                               <a href="impressum.html">Impressum</a> </p>
</footer>
</body>
</html>

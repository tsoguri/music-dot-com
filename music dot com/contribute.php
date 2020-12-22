<?php include("includes/init.php");
$title = 'contribute';
$db = open_sqlite_db('secure/catalog.sqlite');
$showConfirmation = FALSE;
$showDouble = FALSE;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
  $artist = filter_input(INPUT_POST, "artist", FILTER_SANITIZE_STRING);
  $genre = filter_input(INPUT_POST, "genre", FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING);

  $title = str_replace("&#39;", "'", $title);

  $title = trim($title);
  $artist = trim($artist);
  $genre = trim($genre);
  $date = trim($date);

  if (!empty($title) && !empty($artist) && !empty($genre) && !empty($date)) {
    $sql = "SELECT title, artist, genre, releaseDate FROM music WHERE (title=:title) AND (artist=:artist);";
    $params = array(
      ':title' => htmlspecialchars($title),
      ':artist' => htmlspecialchars($artist)
    );
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      $records = $result->fetchAll();
      if (count($records) > 0) {
        $showDouble = TRUE;
      } else {
        $showConfirmation = TRUE;
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,800,900&display=swap" rel="stylesheet">

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>MUSIC.COM Come Contribute!</title>
  <link rel="stylesheet" href="styles/sites.css" />
</head>

<body>
  <header> <?php include('includes/header.php'); ?></header>

  <?php
  if ($showConfirmation) { ?>
    <?php
    $sql = "INSERT INTO music (title, artist, genre, releaseDate) VALUES(:title, :artist, :genre, :releasedate);";
    $params = array(
      ':title' => htmlspecialchars($title),
      ':artist' => htmlspecialchars($artist),
      ':genre' => htmlspecialchars($genre),
      ':releasedate' => htmlspecialchars($date)
    );
    $result = exec_sql_query($db, $sql, $params);
    if ($result) { ?>
      <h2> Thank You</h2>
      <p class="confirmation">Thank you for helping our catalog grow! <br> You can view your contribution below. </p>
      <?php
      $sql = "SELECT title, artist, genre, releaseDate FROM music ORDER BY title ASC;";
      $result = exec_sql_query($db, $sql);
      $records = $result->fetchAll();
      ?>
      <table>
        <?php
        printTableHeader();
        foreach ($records as $record) {
          printRecord($record);
        } ?>
      </table>

    <?php
    } else { ?>
      <p class="confirmation">Sorry, we failed to add your music selection. We would love to see your submission though so please fill out another form!</p>
    <?php
    }
  } else { ?>
    <h2> Come Contribute </h2>
    <?php if ($showDouble) { ?>
      <p class=confirmation> Looks like we already have that song selection in our catalog. If you have another, please fill out this form again, we'd love to see your song choices!</p>
    <?php
    } ?>
    <div class="contribute">
      <form id="contributeForm" action="contribute.php" method="post" novalidate>
        <div class="input">
          <label>Title:</label>
          <input class="inputTitle" type="text" name="title" required />
        </div>

        <div class="input">
          <label>Artist:</label>
          <input class="inputArtist" type="text" name="artist" required />
        </div>

        <div class="input">
          <label>Genre:</label>
          <input class="inputGenre" type="text" name="genre" required />
        </div>

        <div class="input">
          <label>Release Date:</label>
          <input class="inputReleaseDate" type="text" name="date" placeholder="year and month (XXXX-XX)" required />
        </div>

        <div class="submitInput">
          <button class="go" type="submit">Go</button>
        </div>
      </form>
    </div>

  <?php }
  ?>

</body>

</html>

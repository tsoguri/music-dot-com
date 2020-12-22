<?php include("includes/init.php");
$title = 'new';
$db = open_sqlite_db('secure/catalog.sqlite');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,800,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>MUSIC.COM New Releases</title>
    <link rel="stylesheet" href="styles/sites.css" />
</head>

<body>
    <header> <?php include('includes/header.php'); ?></header>
    <h2> New Releases </h2>
    <?php
    $sql = "SELECT title, artist, genre, releaseDate FROM music ORDER BY releaseDate DESC LIMIT 10;";
    $result = exec_sql_query($db, $sql, $params);
    $records = $result->fetchAll();
    ?>
    <table>
        <?php
        printTableHeader();
        foreach ($records as $record) {
            printRecord($record);
        } ?>
    </table>

</body>

</html>

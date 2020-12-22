<?php
include("includes/init.php");
$title = 'index';
$search = FALSE;

$db = open_sqlite_db('secure/catalog.sqlite');

if (isset($_GET['searchAll'])) {
    $search = TRUE;
    $allInput = filter_input(INPUT_GET, 'searchAll', FILTER_SANITIZE_STRING);
    $allInput = trim($allInput);
    $allInput = str_replace("&#39;", "'", $allInput);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,800,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>MUSIC.COM</title>
    <link rel="stylesheet" href="styles/sites.css" />
</head>

<body>
    <header> <?php include('includes/header.php'); ?></header>
    <main>
        <div class="searchForm">
            <form action="index.php" method="get" novalidate>
                <input class="searchInput" type="text" name="searchAll" placeholder="Search by titles, artists, genre, or year released">
                <button class="searchGo" type="submit">Go</button>
            </form>
        </div>

        <?php
        if ($search) {
            $sql = "SELECT title, artist, genre, releaseDate FROM music WHERE (title LIKE'%'|| :allInput || '%') OR (artist LIKE'%'|| :allInput|| '%') OR (genre LIKE'%'|| :allInput|| '%') OR (releaseDate LIKE'%'|| :allInput|| '%') ORDER BY releaseDate DESC;";
            $params = array(
                ':allInput' => htmlspecialchars($allInput)
            );
            $result = exec_sql_query($db, $sql, $params);
            if ($result) {
                $records = $result->fetchAll();
                if (count($records) > 0) { ?>
                    <p class="confirmation">Here are your results for "<?php echo htmlspecialchars($allInput) ?>."</p>
                    <table>
                        <?php
                        printTableHeader();
                        foreach ($records as $record) {
                            printRecord($record);
                        } ?>
                    </table>
                <?php
                } else { ?>
                    <p class="confirmation">Sorry, we were unable to find what you were looking for. Please search again or contribute your song selection on our Contribute page. Thank you!</p>
        <?php
                }
                $allInput = '';
            }
        }
        ?>
    </main>
</body>

</html>

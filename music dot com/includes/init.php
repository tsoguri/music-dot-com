<?php
// check current php version to ensure it meets 2300's requirements
function check_php_version()
{
  if (version_compare(phpversion(), '7.0', '<')) {
    define('VERSION_MESSAGE', "PHP version 7.0 or higher is required for 2300. Make sure you have installed PHP 7 on your computer and have set the correct PHP path in VS Code.");
    echo VERSION_MESSAGE;
    throw VERSION_MESSAGE;
  }
}
check_php_version();

function config_php_errors()
{
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 0);
  error_reporting(E_ALL);
}
config_php_errors();

// Open a connection to an SQLite database stored in filename: $db_filename.
// Returns: Connection to database.
function open_sqlite_db($db_filename)
{
  $db = new PDO('sqlite:' . $db_filename);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $db;
}

// Execute a query ($sql) against a datbase ($db).
// Returns query results if query was successful.
// Returns null if query was not successful.
function exec_sql_query($db, $sql, $params = array())
{
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return null;
}

function printRecord($record)
{ ?>
  <tr>
    <td><?php echo htmlspecialchars($record["title"]); ?></td>
    <td><?php echo htmlspecialchars($record["artist"]); ?></td>
    <td><?php echo htmlspecialchars($record["genre"]); ?></td>
    <td><?php echo htmlspecialchars($record["releaseDate"]); ?></td>
  </tr>
<?php
}

function printTableHeader()
{ ?>
  <tr>
    <th>Title</th>
    <th>Artist</th>
    <th>Genre</th>
    <th>Release Date</th>
  </tr>
<?php
}
?>

<?php
// search.php
require_once 'shared/config.inc';
/**
 * @var mysqli $conn
 */

$results = [];
$constructed_sql = '';
$term = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q']))
{
    // TODO

    $response = []; // TODO

    if ($response)
    {
        while ($row = $response->fetch_assoc())
        {
            $results[] = $row;
        }
        $response->free();
    }
    else
    {
        $error = $conn->error;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Search Posts (vulnerable)</title>
</head>
<body>
<h1>Search Posts - Vulnerable</h1>

<form method="get" action="">
    <label for="q">Search term:</label>
    <input id="q" name="q" value="<?php echo htmlspecialchars($term); ?>" style="width:40%;" />
    <button type="submit">Search</button>
</form>

<?php if ($constructed_sql !== ''): ?>
    <h3>Constructed SQL (for demo)</h3>
    <pre><?php echo htmlspecialchars($constructed_sql); ?></pre>
<?php endif; ?>

<h3>Results</h3>
<?php if (!empty($results)): ?>
    <ul>
        <?php foreach ($results as $r): ?>
            <li><?php echo htmlspecialchars($r['POST_ID']); ?> — <?php echo htmlspecialchars($r['POST_CONTENT']); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No rows returned.</p>
<?php endif; ?>

<p><a href="index.php">Back</a></p>
</body>
</html>

<?php $conn->close(); ?>
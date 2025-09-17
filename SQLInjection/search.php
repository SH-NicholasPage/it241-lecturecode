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
    $term = $_GET['q'];

    // VULNERABLE: direct concatenation into SQL (LIKE) — no parameterization
    $constructed_sql = "SELECT POST_ID, POST_CONTENT, POST_HIDDEN FROM POST WHERE POST_HIDDEN = FALSE AND POST_CONTENT LIKE '%" . $term . "%'";

    $res = $conn->query($constructed_sql);

    if ($res)
    {
        while ($row = $res->fetch_assoc())
        {
            $results[] = $row;
        }
        $res->free();
    }
    else
    {
        $error = $conn->error;
    }
}
?>
<!doctype html>
<html>
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
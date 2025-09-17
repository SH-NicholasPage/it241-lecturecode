<?php
// create_post.php
require_once 'shared/config.inc';
/**
 * @var mysqli $conn
 */

$message = '';
$constructed_sql = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $content = $_POST['content'] ?? '';
    // If the hidden checkbox is checked, value will be '1'
    $hidden = isset($_POST['hidden']) && ($_POST['hidden'] === '1');

    // VULNERABLE: direct concatenation of user input into SQL
    // Note: we intentionally interpolate the boolean as TRUE/FALSE (no quotes)
    $constructed_sql = "INSERT INTO POST (POST_CONTENT, POST_HIDDEN) VALUES ('" . $content . "', " . ($hidden ? 'TRUE' : 'FALSE') . ")";

    // Execute the vulnerable query
    $ok = $conn->query($constructed_sql);

    if ($ok)
    {
        $message = 'Post created (check DB).';
    }
    else
    {
        $message = 'Error: ' . $conn->error;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create Post (vulnerable)</title>
</head>
<body>
<h1>Create Post - Vulnerable</h1>

<?php if ($message !== ''): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<form method="post" action="">
    <label for="content">Post content:</label><br />
    <textarea id="content" name="content" style="width:40%;" required></textarea><br /><br />

    <label for="hidden">
        <input id="hidden" name="hidden" type="checkbox" value="1" />
        Mark as hidden
    </label>
    <br /><br />

    <button type="submit">Create</button>
</form>

<?php if ($constructed_sql !== ''): ?>
    <h3>Constructed SQL (for demo)</h3>
    <pre><?php echo htmlspecialchars($constructed_sql); ?></pre>
<?php endif; ?>

<p><a href="index.php">Back</a></p>
</body>
</html>

<?php $conn->close(); ?>
<?php
require 'functions.php';

// Initialize variables
$file = isset($_GET['file']) ? $_GET['file'] : null;
$entry_content = '';
$success_message = '';
$error_message = '';

if ($file && file_exists('entries/' . $file)) {
    $entry_content = file_get_contents('entries/' . $file);
    $entry_content = htmlspecialchars_decode($entry_content); // Decode HTML entities
    $entry_content = renderMarkdown($entry_content); // Render Markdown to HTML

    // Get adjacent entries
    list($prevFile, $nextFile) = getAdjacentEntries($file);
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if ($file && file_exists('entries/' . $file)) {
        if (unlink('entries/' . $file)) {
            $success_message = "Entry deleted successfully.";
            header("Location: /");
            exit;
        } else {
            $error_message = "Failed to delete the entry.";
        }
    } else {
        $error_message = "File does not exist.";
    }
}
?>

<!doctype html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 

    <title>Entry</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <link rel="stylesheet" href="custom.css">
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this entry?');
        }
    </script>
</head>
<body>
    <main>
        <div class="button-group">
            <a class="button" href="/">Back to Journal</a>

            <?php if ($file && file_exists('entries/' . $file)): ?>
                <form method="POST" action="" onsubmit="return confirmDelete();">
                    <button class="delete" type="submit" name="delete">Delete Entry</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="entry">
            <?php if ($entry_content): ?>
                <div><?php echo $entry_content; ?></div>
            <?php else: ?>
                <p>Entry not found.</p>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <!-- Previous Entry -->
            <?php if ($prevFile): ?>
                <a href="?file=<?php echo urlencode($prevFile); ?>">← Previous Entry</a>
            <?php else: ?>
                <span class="disabled">← Previous Entry</span>
            <?php endif; ?>

            <!-- Next Entry -->
            <?php if ($nextFile): ?>
                <a href="?file=<?php echo urlencode($nextFile); ?>">Next Entry →</a>
            <?php else: ?>
                <span class="disabled">Next Entry →</span>
            <?php endif; ?>
        </div>

    </main>
</body>
</html>

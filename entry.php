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
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 

    <title>Entry</title>
    <link rel="stylesheet" href="simple.css">
    <link rel="stylesheet" href="custom.css">
    <script>
        function confirmDelete() {
            return confirm('Bist du dir sicher, dass du diesen Eintrag löschen möchtest?');
        }
    </script>
</head>
<body>
    <main>
        <div class="button-group">
            <a class="button" href="/tagebuch">Zurück zum Tagebuch</a>

            <?php if ($file && file_exists('entries/' . $file)): ?>
                <form method="POST" action="" onsubmit="return confirmDelete();">
                    <button class="delete" type="submit" name="delete">Eintrag löschen</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="entry">
            <?php if ($entry_content): ?>
                <div><?php echo $entry_content; ?></div>
            <?php else: ?>
                <p>Eintrag nicht gefunden.</p>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <!-- Previous Entry -->
            <?php if ($prevFile): ?>
                <a href="?file=<?php echo urlencode($prevFile); ?>">← Vorheriger Eintrag</a>
            <?php else: ?>
                <span class="disabled">← Vorheriger Eintrag</span>
            <?php endif; ?>

            <!-- Next Entry -->
            <?php if ($nextFile): ?>
                <a href="?file=<?php echo urlencode($nextFile); ?>">Nächster Eintrag →</a>
            <?php else: ?>
                <span class="disabled">Nächster Eintrag →</span>
            <?php endif; ?>
        </div>

    </main>
</body>
</html>

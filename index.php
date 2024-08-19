<?php
require 'functions.php';
?>

<!doctype html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 

    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <link rel="apple-touch-icon" href="/images/favicon.webp">
    <link rel="icon" type="image/png" href="/images/favicon.webp" />

    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <link rel="stylesheet" href="custom.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($page_title); ?></h1>
        <p><?php echo htmlspecialchars($page_description); ?></p>
    </header>
    <main>
        <form method="POST" action="index.php">
            <label class="hidden" for="content">Content</label>
            <textarea id="content" rows="6" name="content" required oninput="resizeTextarea(this)" placeholder="Today I..."></textarea>
            <button type="submit">Save entry</button>
            <?php echo $success_message; ?>
        </form>

        <?php if (empty($entries)): ?>
            <p>No entries found.</p>
        <?php else: ?>
            <?php foreach ($entries as $monthYear => $entriesByMonth): ?>
                <h2><?php echo date('F Y', strtotime($monthYear . '-01')); // Convert YYYY-MM to "Month Year" ?></h2>
                <ul class="entry-list">
                <?php foreach ($entriesByMonth as $entry): ?>
                    <li>
                        <a href="entry.php?file=<?php echo urlencode(basename($entry['file'])); ?>">
                            <strong><?php echo htmlspecialchars($entry['display_timestamp']); ?></strong>
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <script src="scripts.js"></script>
</body>
</html>

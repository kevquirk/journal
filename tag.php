<?php
require 'bootstrap.php';

$tag = strtolower($_GET['tag'] ?? '');

if (!$tag) {
    die('Tag not specified.');
}

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch paginated entries for the tag
$entries = getEntriesByTag($tag, $limit, $offset);

// Get total count for pagination
global $db;
$totalEntries = $db->prepare("SELECT COUNT(DISTINCT e.id) FROM entries e 
                              JOIN entry_tags et ON e.id = et.entry_id 
                              JOIN tags t ON et.tag_id = t.id 
                              WHERE lower(t.name) = :tag");
$totalEntries->execute([':tag' => $tag]);
$totalEntries = $totalEntries->fetchColumn();
$totalPages = ceil($totalEntries / $limit);

$page_title = "Entries tagged: " . htmlspecialchars($tag);
$page_description = "Journal entries tagged as '" . htmlspecialchars($tag) . "'";
?>

<!doctype html>
<html lang="en-GB">

<?php include 'head.php'; ?>

<body>
    <header>
        <h1><?php echo $page_title; ?></h1>
        <p><?php echo $page_description; ?></p>
        
        <?php include 'nav.php'; ?>
    </header>
    <main>
        <?php if (empty($entries)): ?>
            <p>No entries found for this tag.</p>
        <?php else: ?>
            <?php foreach ($entries as $entry): ?>
                <article>
                <h2><?php echo formatTimestampForLocal($entry['timestamp']); ?></h2>

                    <div><?php echo $Parsedown->text($entry['content']); ?></div>
                    <p><strong>Mood:</strong> <?php echo htmlspecialchars($entry['mood']); ?></p>
                    <p><strong>Tag(s):</strong> 
                        <?php 
                        if (!empty($entry['tags'])) {
                            $tags = explode(',', $entry['tags']);
                            $tagLinks = array_map(fn($tag) => '<a href="tag.php?tag=' . urlencode(trim($tag)) . '">' . htmlspecialchars(trim($tag)) . '</a>', $tags);
                            echo implode(', ', $tagLinks);
                        } else {
                            echo 'None';
                        }
                        ?>
                    </p>
                    <div class="delete-container">
                        <form method="POST" action="index.php" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                            <input type="hidden" name="delete_id" value="<?php echo $entry['id']; ?>">
                            <button class="delete" type="submit">Delete</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <nav>
            <?php if ($page > 1): ?>
                <a href="?tag=<?php echo urlencode($tag); ?>&page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>

            <span>Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?tag=<?php echo urlencode($tag); ?>&page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </nav>

    </main>

    <?php include 'footer.php'; ?>
</body>
</html>

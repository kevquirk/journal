<?php
// Load configuration
$config = require 'config.php';
$default_title = $config['title'];
$default_description = $config['description'];

// Include Parsedown
require 'Parsedown.php';

function handleJournalEntry() {
    global $success_message, $page_title, $default_title;
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
        $content = htmlspecialchars($_POST['content']);
        $date = date('Y-m-d-His'); // Include seconds
        $timestamp = date('Y-m-d H:i:s'); // Display seconds in timestamp
        $content_with_metadata = "$timestamp\n\n$content";
        $directory = 'entries/';
        $filename = "{$date}.txt";

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($directory . $filename, $content_with_metadata);
        $success_message = "<p>Entry saved successfully!</p>";
    }
}

function renderMarkdown($text) {
    $Parsedown = new Parsedown();
    return $Parsedown->text($text);
}

// Extract the full timestamp - assumes format YYYY-MM-DD-HHMMSS.txt
function extractFullTimestamp($filename) {
    $matches = [];
    if (preg_match('/(\d{4}-\d{2}-\d{2})-(\d{6})/', $filename, $matches)) {
        $date = $matches[1];
        $time = substr($matches[2], 0, 2) . ':' . substr($matches[2], 2, 2) . ':' . substr($matches[2], 4, 2); // HH:MM:SS
        return $date . ' ' . $time;
    }
    return 'Unknown Date';
}

// Extract timestamp from the filename; used to display the time without seconds on the entry list
function extractTimestamp($filename) {
    $matches = [];
    if (preg_match('/(\d{4}-\d{2}-\d{2})-(\d{6})/', $filename, $matches)) {
        $date = $matches[1];
        $time = substr($matches[2], 0, 2) . ':' . substr($matches[2], 2, 2); // HH:MM
        return $date . ' ' . $time;
    }
    return 'Unknown Date';
}

// List all entries in the entries directory on the homepage
function listEntries() {
    $directory = 'entries/';
    $entries = [];

    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $full_timestamp = extractFullTimestamp($file); // Use the full timestamp with seconds for sorting
                $display_timestamp = extractTimestamp($file);  // Use the trimmed timestamp for display
                $date_key = extractMonthYear($file); // Group by month and year
                if ($full_timestamp !== 'Unknown Date') {
                    $entries[$date_key][] = [
                        'file' => htmlspecialchars($file),
                        'timestamp' => $full_timestamp,     // Full timestamp for sorting
                        'display_timestamp' => $display_timestamp // Trimmed timestamp for display
                    ];
                }
            }
        }

        // Sort entries by month and year in reverse order (most recent first)
        krsort($entries);

        // Sort each month's entries by full timestamp in reverse chronological order, including seconds
        foreach ($entries as $monthYear => &$entriesByMonth) {
            usort($entriesByMonth, function($a, $b) {
                $dateA = DateTime::createFromFormat('Y-m-d H:i:s', $a['timestamp']);
                $dateB = DateTime::createFromFormat('Y-m-d H:i:s', $b['timestamp']);
                return $dateB <=> $dateA; // Most recent first
            });
        }
        unset($entriesByMonth); // Break reference to the last element
    }

    return $entries;
}

// Extract month and year from the filename, assumes format: YYYY-MM-DD-Hi.txt
function extractMonthYear($filename) {
    $matches = [];
    if (preg_match('/(\d{4}-\d{2})/', $filename, $matches)) {
        return $matches[1]; // Return YYYY-MM
    }
    return 'Unknown Month';
}

// Setup for next/prev entries
function getAdjacentEntries($currentFile) {
    $entries = listEntries(); // Get all entries
    $allFiles = [];

    // Flatten the array into a single list
    foreach ($entries as $entriesByMonth) {
        foreach ($entriesByMonth as $entry) {
            $allFiles[] = $entry['file'];
        }
    }

    // Find the current index
    $currentIndex = array_search($currentFile, $allFiles);

    // Determine next and previous entries
    // Note: $prevFile will point to the next newer file, and $nextFile to the next older file
    $prevFile = ($currentIndex < count($allFiles) - 1) ? $allFiles[$currentIndex + 1] : null;
    $nextFile = ($currentIndex > 0) ? $allFiles[$currentIndex - 1] : null;

    return [$prevFile, $nextFile];
}

// Main script execution
handleJournalEntry();
$page_title = $default_title;
$page_description = $default_description;
$entries = listEntries();

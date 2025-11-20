<?php

return [
    'page_title' => "Journal Demo",
    'page_description' => 'A lightweight SQLite journal with Markdown support.',
	'auth_user' => 'your-username',
    'auth_pass' => 'your-password-hash',
    'timezone' => 'Europe/London',
	'db_path' => __DIR__ . '/data/journal.db',
    'date_only' => False,        // Set to True to only have one entry per day
];
    
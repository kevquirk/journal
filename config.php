<?php

return [
    'page_title' => "Journal Demo",
    'page_description' => 'A lightweight SQLite journal with Markdown support.',
	'auth_user' => 'your-username',
    'auth_pass' => 'your-password-hash',
    // Demo creds, uncomment these, and comment out the above.
    //'auth_user' => 'demo',
    //'auth_pass' => '$2a$12$u/8fgF.qac86HZVm4LwMkucmg9/ic67nQqyJZN8W2c5eDVK77bbYi',
    'timezone' => 'Europe/London',
	'db_path' => __DIR__ . '/data/journal.db',
    'date_only' => False,        // Set to True to only have one entry per day
];
    
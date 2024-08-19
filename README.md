# Journal
A super simple, self-hosted Journal application that's written in PHP.

*Journal* was created by [Kev Quirk](https://kevquirk.com) and uses the following packages:

* [Simple.css](https://simplecss.org) - to make it look pretty
* [Parsedown](https://parsedown.org) - to add Markdown support

## How to update title and description

If you want to update the title and description that are shown on the header of the homepage, just edit the `config.php` file.

```
<?php
return [
    'title' => "Kev's Journal", // Edit this line to change the title
    'description' => "This is my personal journal where I document my thoughts and experiences." // Edit this line to change the description
];
```

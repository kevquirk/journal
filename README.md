# Journal
A super simple, self-hosted Journal application that's written in PHP.

*Journal* was created by [Kev Quirk](https://kevquirk.com) and uses the following packages:

* [Simple.css](https://simplecss.org) - to make it look pretty
* [Parsedown](https://parsedown.org) - to add Markdown support

I created this little application for my own use, but figured it might be useful for others. Originally it created a plaintext file and rendered them, but I've since improved the functionality by moving from plaintext files, to an SQLite database.

Here's the features that are currently supported in *Journal*:

* Markdown within journal entries.
* Tagging support for entries.
* Ability to delete entries.
* Entry pagination.
* An *On This Day* page that allows you to look back at previous entries from a week, month, and previous years.
* A stats page that shows numbers of entries, as well as entries by mood and tag.
* Full text search.
* An authentication system to keep your Journal private.

## Demo

Journal has been deliberately designed to be clean, basic and simple to use. Here is what it looks like:

![screenshot](https://github.com/user-attachments/assets/09b0746b-e632-41be-be3b-0da7a7ceea37)

If have also setup a full demo of *Journal* so you can get an idea of what it looks like and how it works. [You can view the demo here](https://journal-demo.kevquirk.com).

## Hosting requirement

The hosting requirements are *extremely* simple. All that is needed is PHP support and SQLite, both of which are available with most hosting providers.

## Getting started

1. Fork or download the project.
2. Upload the project to your hosting provider.
3. Complete the settings in config.php.
4. Enjoy!

Even though *Journal* requires SQLite, no configuration is needed. If no existing database is detected, then a new one will be automatically created when you publish your first entry.

## Securing your data

If you are using a publicly accessible web server, you must ensure the `data` folder is secured.  This will prevent someone browsing to the folder, or downloading the database.

For those running an Apache2 web server, your data folder is already secured with the`.htaccess` file in the data folder.

If you are using Nginx you will need an entry in the server section of your config file, something like:

```
location ^~ /data/ {
        deny all;
        return 403;
    }
```
Then restart NGINX

Caddy has a number of ways to do this -- consult your documentation.

To test your security, add `/data/journal.db` to the end of your web address in the browser location bar.  You should be Denied access and it will NOT download the file.



## Setting up the config file

There are 4 simple options to complete in the config file:

1. The title of your Journal.
2. The Journal's description (this will be shown under the title within the site header).
3. Your timezone.
4. Your username & password for authentication.

1 & 2 are pretty straightforward, so I won't go into detail on them. To setup your timezone, find the correct one for your location from [this list](https://www.php.net/manual/en/timezones.php), then replace `Europe/London` within the config.

### Setting up authentication

This is still very simple, but requires a little more work as you can't just put the password in ths config file in plaintext - that wouldn't be very secure now would it. Instead we use a [hash function](https://en.wikipedia.org/wiki/Hash_function) to obfuscate the password. You can do this in a couple of ways. If you have PHP installed on your local machine, run the following command replacing `your-secure-password` with whatever you want you password to be:

```
php -r "echo password_hash('your-secure-password', PASSWORD_DEFAULT) . PHP_EOL;"
```

This command you output something like this, which is your password hash:

```
$2y$10$w17iaaeoqfn96w9jlFK3t.NdltDqVcndArseVoPaWjKrbBe4wngSy
```

All you need to do then is paste this hash in the config file, replacing `your-password-hash`. Your Journal should then accept whatever password you set.

Alternatively, if you don't have PHP installed, or you aren't comfortable in the command line, you can use [this online tool](https://codeshack.io/php-password-hash-generator/) to generate the hash for you. Just leave the settings as default (BCRYPT & 12), type in your password and git the generate button.

## Roadmap

*Journal* is pretty much feature complete at this point, as it does everything I need it to. I *might* add additional features in the future, but this project is driven by *my* needs. If there's a feature you'd like me to add, please [submit an issue](https://github.com/kevquirk/journal/issues) and I'll consider it.

## No warranty

Please note that this was always designed to be a personal project to help me learn PHP. If you decide to self-host a copy of *Journal* it comes with no warranty whatsoever and I can't be held liable for any issues as a result of using this software.

## Thanks

Finally, I'd like to thank [Martijn van der Ven](https://vanderven.se/martijn/) for reporting some critical security bug that I was able to fix.

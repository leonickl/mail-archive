# Mail Archive Viewer

Archive your mails as `.eml` files and access them locally within your browser.

## Prepare

-   Install PHP and composer
-   Run `composer install`
-   Run `php artisan migrate`

## Import Mails

-   Just put a bunch of `.eml` files in `storage/app/mails`.
-   You can sort them into subfolders, but that doesn't matter.
-   Run `php artisan import` to store the mail information (subject, date, from, to, content in plain text and HTML) in the database.
-   Run `php artisan serve` to view your emails in the browser.
-   Now you can view them in a neat, paginated table with a search option.

## New UI

-   You can also use [mail-frontend]([https://github.com/gehmasse/mail-frontend](https://github.com/Gehmasse/mail-frontend/tree/mail-archive) at branch `mail-archive` to view the archive in a more modern way.

# Mail Archive Viewer

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

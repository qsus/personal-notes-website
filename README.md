# personal-notes-website
> [!WARNING] 
> This readme is not up-to-date. Project is under development.
## Motivation
Many times I was working on someone elses computer and needed to make some note for myself or to save some file for later. The most common solution to this problem is sending the file / note to myself via email. This works, but come on... why would you need email for this? Not even mentioning the security problems of logging into email on someone elses computer. Is there really not a more elegant solution? Now there is.
## Prerequisites
* Apache webserver with PHP, working .htaccess, mysql server
## Setup
1. Download the repository.
```bash
git clone https://github.com/qsus/personal-notes-website
cd personal-notes-website
```
2. Create table `user` in database `notes` with collumns `user` and `password`. Create user `notes@localhost` which has access to the table `notes.user`.
4. Make sure the `upload` folder, `notes.html` and `notes.txt` are writable by the webserver. (Try `chown -R www-data .`.)
5. If using Apache, see the `personal-notes-website.conf` file for an example configuration and hope it is up-to-date. This should usually be placed in `/etc/apache2/sites-available/` and enabled with `a2ensite personal-notes-website.conf` (don't forget to restart Apache `systemctl reload apache2`).
6. Add `SetEnv SENTRY_DSN "https://<key>@sentry.io/<key>"` to the Apache configuration file (usually `/etc/apache2/apache2.conf`) to enable error logging with [Sentry](https://sentry.io/).
## Warning
This solution is under development and may contain bugs or security risks. Keyloggers on public computers could be used to steal your password.
## TODO
* Security
	* One-time passwords
	* Lockdown mode – one click to stop all access to the website untill manually modifying files on the server
* Deleting files
* Uploading folders
## Other
* Favicon downloaded from [icon-icons.com](https://icon-icons.com/icon/notepad-notes/22522), author [hopstarter](https://www.deviantart.com/hopstarter).

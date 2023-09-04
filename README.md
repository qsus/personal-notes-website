# personal-notes-website
## Motivation
Many times I was working on someone elses computer and needed to make some note for myself or to save some file for later. The most common solution to this problem is sending the file / note to myself via email. This works, but come on... why would you need email for this? Not even mentioning the security problems of logging into email on someone elses computer. Is there really not a more elegant solution? Now there is.
## Prerequisites
* Apache webserver with PHP, working .htaccess
## Setup
1. Download the repository.
```bash
git clone https://github.com/qsus/personal-notes-website
cd personal-notes-website
```
2. Generate password file. You will be prompted for a password. (-c = create, -B = use bcrypt)
```bash
htpasswd -c -B passwords <username>
```
3. Make sure the AuthUserFile path in `.htaccess` points to the password file.
4. Make sure the `upload` folder, `notes.html` and `notes.txt` are writable by the webserver. (Try `chown -R www-data .`.)
5. If using Apache, see the `personal-notes-website.conf` file for an example configuration. This usually goes to `/etc/apache2/sites-available/` and then you need to enable it with `a2ensite personal-notes-website.conf` and restart Apache with `systemctl reload apache2`.
## Warning
This solution is very simple and not very secure. Anyone who passes the password authentication should be considered to have full access to your server. Keyloggers on public computers could be used to steal your password.
## TODO
* Security
	* One-time passwords
	* Lockdown mode – one click to stop all access to the website untill manually modifying files on the server
* Deleting files
* Uploading folders
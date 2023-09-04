# personal-notes-website
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
3. Make sure the path to the password file is correct in the `.htaccess` file.
## Warning
This solution is very simple and not very secure. Anyone who passes the password authentication should be considered to have full access to your server.
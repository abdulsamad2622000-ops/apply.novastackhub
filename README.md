# NovaStackHub — Careers / Job Portal

Laravel 12 job portal for `apply.novastackhub.com`.

- **Public careers page** at `/` — lists all open positions
- Each job has its own detail page + application form (`/jobs/{slug}`)
- Every application + uploaded CV is stored in the database
- Email notification to your inbox on each new application
- **Admin dashboard** at `/admin`:
  - Post / edit / open-close / delete jobs
  - View applications (filter by job, search), download CVs

---

## 1. Requirements

- PHP 8.2+ (extensions: mbstring, openssl, pdo, fileinfo, ctype, json)
- Composer
- MySQL / MariaDB
- Apache (mod_rewrite) or Nginx

---

## 2. Setup (local ya server)

```bash
cd novastack-apply
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate

# .env me DB + ADMIN_PASSWORD set karo (section 4)

php artisan migrate:fresh --seed   # tables banao + example jobs seed karo
php artisan storage:link           # CV uploads ke liye (ZAROORI)
php artisan serve
```

Open:
- Careers: `http://127.0.0.1:8000`
- Admin:   `http://127.0.0.1:8000/admin`

> `migrate:fresh` saari tables drop karke naye se banata hai. Pehli baar ya jab
> bhi schema badle to yehi chalao. **Live data hone ke baad `migrate:fresh` mat
> chalana** — woh sab kuch delete kar deta hai; us waqt sirf `php artisan migrate`.

---

## 3. Deploy on `apply.novastackhub.com`

**Sabse zaroori:** subdomain ka **document root `public/` folder** par point ho
(pura project folder nahi).

### cPanel / shared hosting
1. Subdomain banao: `apply.novastackhub.com`
2. Project upload karo, Document Root → `.../novastack-apply/public`
3. Terminal: `composer install`, `php artisan key:generate`,
   `php artisan migrate --seed`, `php artisan storage:link`
4. SSL enable karo

### Nginx (VPS)
```nginx
server {
    listen 80;
    server_name apply.novastackhub.com;
    root /var/www/novastack-apply/public;
    index index.php;
    client_max_body_size 12M;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    location ~ /\.(?!well-known).* { deny all; }
}
```
Then: `composer install --no-dev -o`, `php artisan migrate --seed`,
`php artisan storage:link`, `php artisan config:cache`.

---

## 4. .env settings

```env
APP_NAME="NovaStackHub Careers"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://apply.novastackhub.com

ADMIN_PASSWORD=YourStrongPasswordHere     # /admin login
RECRUITMENT_NOTIFY_EMAIL=info@novastackhub.com   # khaali = off

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=novastack_apply
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

Email ke liye (optional) `.env` me SMTP daalo (MAIL_MAILER=smtp ...).
`MAIL_MAILER=log` rakho to emails `storage/logs/laravel.log` me jate hain.

---

## 5. File upload size (10 MB CV)

`php.ini` (ya cPanel MultiPHP INI Editor):
```ini
upload_max_filesize = 12M
post_max_size = 14M
```
Nginx: `client_max_body_size 12M;`

---

## 6. Admin dashboard

- URL: `/admin`  ·  Password: `.env` ka `ADMIN_PASSWORD`
- **Jobs** tab: naya job post karo, edit/close/delete, har job ki application count
- **Applications** tab: job se filter, search, CV download

### Naya job add karne ka tareeqa
Admin → Jobs → **+ New job** → title, type, location, summary, full description.
- *Commission-only* aur *Outreach platforms* sawaal sirf un jobs me dikhte hain
  jahan aap ne unke toggle ON kiye hain (sales roles ke liye).
- Description me: chhoti line = heading ban jati hai, `- ` se shuru line = bullet,
  khaali line = naya section.

---

## 7. Permissions (Linux)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Data model

- `jobs_listings` — job postings (table "jobs_listings" taake Laravel queue ki
  default `jobs` table se collision na ho)
- `applications` — submissions, har ek `job_id` se linked

Form fields edit karne ke liye:
`resources/views/careers/show.blade.php`,
`app/Http/Requests/StoreApplicationRequest.php`, aur applications migration.

Built for **NovaStackHub** · Karachi, Pakistan.

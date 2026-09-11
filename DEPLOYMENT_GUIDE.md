# Laravel Deployment Guide - Free Cloud Hosting

## Overview
This guide will help you deploy your Laravel STEAM application to Render.com with automated GitHub deployment, including database, storage symlinks, and WebSocket configuration.

## Prerequisites
- GitHub account with repository
- Render.com account (free tier)
- Free MySQL database (PlanetScale or similar)

---

## Step 1: Prepare Local Laravel Codebase

### 1.1 Clean up temporary files
Run these commands in your terminal:

```bash
# Remove temporary files from root directory
cd C:\xampp\htdocs\STEAM
Remove-Item -Path "0","999","1299","1499","1599","1799","1899","1999","2199","2399","2499","2999","3499","3999","4299","4499","4999" -Force -ErrorAction SilentlyContinue
Remove-Item -Path "*.bat","*.php" -Exclude "artisan","server.php" -Force -ErrorAction SilentlyContinue
Remove-Item -Path "nested-css.php","setpw.php","testhttp.php" -Force -ErrorAction SilentlyContinue
```

### 1.2 Update .gitignore
Your `.gitignore` already looks good. Ensure it contains:
```
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
```

### 1.3 Create production-ready .env.example
Update your `.env.example` with production values:

```bash
APP_NAME="STEAM"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password

BROADCAST_DRIVER=pusher
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-redis-password
REDIS_PORT=6379

PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-key
PUSHER_APP_SECRET=your-pusher-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=your-pusher-host

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 1.4 Optimize Laravel for production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## Step 2: Set Up Free Cloud MySQL Database

### Option A: PlanetScale (Recommended - Free Tier)
1. Go to [planetscale.com](https://planetscale.com) and create account
2. Create new database: `steam-production`
3. Get connection details from PlanetScale dashboard
4. Copy credentials for Render environment variables

### Option B: Aiven (Free Tier)
1. Go to [aiven.io](https://aiven.io) and create account
2. Create MySQL service (free tier available)
3. Get connection string and credentials

### Database Migration Script
Add this to your Render build script:

```bash
php artisan migrate --force
php artisan db:seed --force
```

---

## Step 3: Storage Symlinks Configuration

### 3.1 The Problem
Your app stores files in `storage/app/public` and uses a symlink to `public/storage`. This symlink must be created on deployment.

### 3.2 Solution for Render
Create a custom build script that handles the symlink:

```bash
# Create storage symlink
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### 3.3 Alternative: Use Cloud Storage
For better reliability, consider using AWS S3 or similar for file storage. Update your filesystem configuration:

```php
// In config/filesystems.php
'default' => env('FILESYSTEM_DRIVER', 's3'),
```

---

## Step 4: WebSocket Configuration for Real-time Features

### 4.1 Recommended Free WebSocket Service: Soketi (Pusher Alternative)

**Why Soketi?**
- Free and open-source
- Pusher-compatible API
- Self-hostable or use free cloud version
- Perfect for Laravel broadcasting

### 4.2 Set up Soketi
1. Create account at [soketi.app](https://soketi.app) (free tier)
2. Create new application
3. Get credentials (app ID, key, secret, host)

### 4.3 Configure .env for Soketi
```bash
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-soketi-app-id
PUSHER_APP_KEY=your-soketi-key
PUSHER_APP_SECRET=your-soketi-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=your-soketi-host
PUSHER_PORT=443
PUSHER_SCHEME=https
```

### 4.4 Update Broadcasting Configuration
Your current config in `config/broadcasting.php` is already set up correctly for Pusher, which works with Soketi.

### 4.5 Frontend Configuration
Update your broadcasting.js or similar to use Soketi:

```javascript
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: process.env.MIX_PUSHER_HOST,
    wsPort: 80,
    wssPort: 443,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});
```

---

## Step 5: Render.com Deployment Configuration

### 5.1 Create render.yaml
Create `render.yaml` in your project root:

```yaml
services:
  - type: web
    name: steam-app
    env: php
    plan: free
    buildCommand: |
      composer install --no-dev --optimize-autoloader
      php artisan key:generate
      php artisan storage:link
      php artisan migrate --force
      php artisan config:cache
      php artisan route:cache
      php artisan view:cache
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: APP_KEY
        generateValue: true
      - key: APP_URL
        value: https://steam-app.onrender.com
      - key: DB_CONNECTION
        value: mysql
      - key: DB_HOST
        value: your-planetscale-host
      - key: DB_PORT
        value: 3306
      - key: DB_DATABASE
        value: steam-production
      - key: DB_USERNAME
        value: your-planetscale-username
      - key: DB_PASSWORD
        value: your-planetscale-password
      - key: BROADCAST_DRIVER
        value: pusher
      - key: CACHE_DRIVER
        value: file
      - key: QUEUE_CONNECTION
        value: sync
      - key: SESSION_DRIVER
        value: file
      - key: PUSHER_APP_ID
        value: your-soketi-app-id
      - key: PUSHER_APP_KEY
        value: your-soketi-key
      - key: PUSHER_APP_SECRET
        value: your-soketi-secret
      - key: PUSHER_APP_CLUSTER
        value: mt1
      - key: PUSHER_HOST
        value: your-soketi-host

databases:
  - name: steam-db
    databaseName: steam_production
    user: steam_user
```

### 5.2 Alternative: Manual Render Setup
If you prefer manual setup:

1. Go to Render.com dashboard
2. Click "New +" → "Web Service"
3. Connect your GitHub repository
4. Configure:
   - **Name**: steam-app
   - **Environment**: PHP
   - **Branch**: main
   - **Root Directory**: Leave empty
   - **Build Command**: 
     ```bash
     composer install --no-dev --optimize-autoloader
     php artisan key:generate
     php artisan storage:link
     php artisan migrate --force
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

5. Add environment variables in Render dashboard
6. Deploy

---

## Step 6: GitHub Setup and Push Commands

### 6.1 Initialize Git Repository
```bash
cd C:\xampp\htdocs\STEAM
git init
git add .
git commit -m "Initial commit - Laravel STEAM application"
```

### 6.2 Create GitHub Repository
1. Go to GitHub.com
2. Click "+" → "New repository"
3. Name: `steam-app`
4. Make it public (for free Render deployment)
5. Don't initialize with README

### 6.3 Push to GitHub
```bash
git remote add origin https://github.com/YOUR_USERNAME/steam-app.git
git branch -M main
git push -u origin main
```

### 6.4 Link GitHub to Render
1. In Render dashboard, click "New +" → "Web Service"
2. Click "Connect GitHub repository"
3. Authorize Render to access your GitHub
4. Select `steam-app` repository
5. Follow the configuration steps above

---

## Step 7: Routing Configuration

### 7.1 Ensure Correct Entry Point
Your `public/index.php` is already correctly set up as the entry point. Render's PHP runtime will automatically serve this file.

### 7.2 Route Configuration
Your `routes/web.php` correctly routes the home page:
```php
Route::get('/', function () {
    return view('notlogin.index');
})->name('home');
```

This will work correctly on Render since the web server points to `public/index.php`.

### 7.3 Asset URL Configuration
Ensure your `APP_URL` in Render environment variables matches your Render URL:
```
APP_URL=https://your-app-name.onrender.com
```

---

## Step 8: Troubleshooting Common Issues

### 8.1 Storage Symlink Issues
If images don't load:
```bash
# SSH into your Render service
php artisan storage:link
php artisan cache:clear
```

### 8.2 Database Connection Issues
- Verify database credentials in Render environment variables
- Check if database is accessible from Render's network
- Ensure SSL certificates are properly configured

### 8.3 WebSocket Connection Issues
- Verify Pusher/Soketi credentials
- Check firewall settings allow WebSocket connections
- Ensure `BROADCAST_DRIVER=pusher` is set

### 8.4 Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chmod -R 777 storage/app/public
```

---

## Step 9: Post-Deployment Checklist

- [ ] Test homepage loads correctly
- [ ] Test user registration/login
- [ ] Test file uploads (avatars, game images)
- [ ] Test real-time chat functionality
- [ ] Test voice chat signaling
- [ ] Verify database migrations ran successfully
- [ ] Check storage symlink is working
- [ ] Test game download functionality
- [ ] Verify admin panel access

---

## Additional Recommendations

### Security
- Set `APP_DEBUG=false` in production
- Use HTTPS only (Render provides free SSL)
- Keep dependencies updated
- Use environment variables for all sensitive data

### Performance
- Use Redis for caching (Render provides free Redis)
- Enable Laravel queues for background jobs
- Use CDN for static assets
- Optimize images before upload

### Monitoring
- Set up error logging (Sentry, Bugsnag)
- Monitor database performance
- Track API response times
- Set up uptime monitoring

---

## Quick Reference Commands

### Local Development
```bash
php artisan serve
php artisan migrate
php artisan storage:link
php artisan cache:clear
```

### Production Deployment
```bash
git add .
git commit -m "Update production"
git push origin main
# Render will auto-deploy
```

### Database Operations
```bash
# Backup local database
mysqldump -u root firstproject > backup.sql

# Export to cloud
mysql -h remote-host -u user -p database < backup.sql
```

---

This guide should get your Laravel STEAM application deployed to Render.com with all the features working correctly. The automated deployment will handle future updates when you push to GitHub.
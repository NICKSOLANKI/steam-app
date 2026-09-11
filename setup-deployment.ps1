# Laravel Deployment Setup Script
# This script prepares your Laravel application for deployment to Render.com

Write-Host "=== Laravel Deployment Setup Script ===" -ForegroundColor Green
Write-Host "Preparing STEAM application for cloud deployment..." -ForegroundColor Yellow

# Navigate to project directory
Set-Location "C:\xampp\htdocs\STEAM"

# Step 1: Clean up temporary files
Write-Host "`n[1/6] Cleaning up temporary files..." -ForegroundColor Cyan
$tempFiles = @("0","999","1299","1499","1599","1799","1899","1999","2199","2399","2499","2999","3499","3999","4299","4499","4999")
foreach ($file in $tempFiles) {
    if (Test-Path $file) {
        Remove-Item $file -Force
        Write-Host "  Removed: $file" -ForegroundColor Gray
    }
}

# Remove other temporary files
$tempPHPFiles = @("nested-css.php","setpw.php","testhttp.php")
foreach ($file in $tempPHPFiles) {
    if (Test-Path $file) {
        Remove-Item $file -Force
        Write-Host "  Removed: $file" -ForegroundColor Gray
    }
}

# Remove game folders that might be in root
$gameFolders = Get-ChildItem -Directory | Where-Object { $_.Name -match "^[a-z0-9-]+$" -and $_.Name -notmatch "^(app|bootstrap|config|database|public|resources|routes|storage|tests|vendor|\.idea|\.devin)$" }
foreach ($folder in $gameFolders) {
    Remove-Item $folder.FullName -Recurse -Force
    Write-Host "  Removed folder: $($folder.Name)" -ForegroundColor Gray
}

Write-Host "  Temporary files cleaned up!" -ForegroundColor Green

# Step 2: Optimize Laravel for production
Write-Host "`n[2/6] Optimizing Laravel for production..." -ForegroundColor Cyan
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
Write-Host "  Caches cleared!" -ForegroundColor Green

# Step 3: Create storage link locally (for testing)
Write-Host "`n[3/6] Creating storage symlink..." -ForegroundColor Cyan
if (!(Test-Path "public\storage")) {
    php artisan storage:link
    Write-Host "  Storage symlink created!" -ForegroundColor Green
} else {
    Write-Host "  Storage symlink already exists!" -ForegroundColor Yellow
}

# Step 4: Export local database
Write-Host "`n[4/6] Exporting local database..." -ForegroundColor Cyan
$dbExportPath = "C:\xampp\htdocs\STEAM\database_backup.sql"
if (Test-Path $dbExportPath) {
    Remove-Item $dbExportPath -Force
}
# Assuming XAMPP MySQL with default credentials
mysqldump -u root firstproject > $dbExportPath
if (Test-Path $dbExportPath) {
    Write-Host "  Database exported to: $dbExportPath" -ForegroundColor Green
} else {
    Write-Host "  Warning: Database export failed. You may need to export manually." -ForegroundColor Yellow
}

# Step 5: Git initialization
Write-Host "`n[5/6] Setting up Git repository..." -ForegroundColor Cyan
if (!(Test-Path ".git")) {
    git init
    Write-Host "  Git repository initialized!" -ForegroundColor Green
} else {
    Write-Host "  Git repository already exists!" -ForegroundColor Yellow
}

# Step 6: Create .env.production.example
Write-Host "`n[6/6] Creating production environment example..." -ForegroundColor Cyan
$envExample = @"
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
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-key
PUSHER_APP_SECRET=your-pusher-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=your-pusher-host
PUSHER_PORT=443
PUSHER_SCHEME=https

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
"@

Set-Content -Path ".env.production.example" -Value $envExample
Write-Host "  Production environment example created!" -ForegroundColor Green

# Final summary
Write-Host "`n=== Setup Complete! ===" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "1. Review the DEPLOYMENT_GUIDE.md for detailed instructions" -ForegroundColor White
Write-Host "2. Set up a free MySQL database (PlanetScale recommended)" -ForegroundColor White
Write-Host "3. Set up a free WebSocket service (Soketi recommended)" -ForegroundColor White
Write-Host "4. Update render.yaml with your actual credentials" -ForegroundColor White
Write-Host "5. Create a GitHub repository and push your code" -ForegroundColor White
Write-Host "6. Connect your GitHub repository to Render.com" -ForegroundColor White
Write-Host "`nCommands to push to GitHub:" -ForegroundColor Yellow
Write-Host "git add ." -ForegroundColor Gray
Write-Host "git commit -m 'Ready for deployment'" -ForegroundColor Gray
Write-Host "git branch -M main" -ForegroundColor Gray
Write-Host "git remote add origin https://github.com/YOUR_USERNAME/steam-app.git" -ForegroundColor Gray
Write-Host "git push -u origin main" -ForegroundColor Gray
Write-Host "`nReplace YOUR_USERNAME with your actual GitHub username." -ForegroundColor Yellow
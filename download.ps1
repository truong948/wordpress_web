$phpUrl = "https://windows.php.net/downloads/releases/php-8.2.27-nts-Win32-vs16-x64.zip"
$wpUrl = "https://wordpress.org/latest.zip"
$phpDest = "e:\wordpress_web\php.zip"
$wpDest = "e:\wordpress_web\wordpress.zip"

Write-Host "Downloading PHP 8.2..."
try {
    Invoke-WebRequest -Uri $phpUrl -OutFile $phpDest -UseBasicParsing
    Write-Host "PHP downloaded OK"
} catch {
    Write-Host "PHP download failed: $_"
}

Write-Host "Downloading WordPress..."
try {
    Invoke-WebRequest -Uri $wpUrl -OutFile $wpDest -UseBasicParsing
    Write-Host "WordPress downloaded OK"
} catch {
    Write-Host "WordPress download failed: $_"
}

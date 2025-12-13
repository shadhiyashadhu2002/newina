<?php
$file = 'resources/views/profile/my_assigned_profiles.blade.php';
$content = file_get_contents($file);

// Backup
copy($file, $file . '.final_backup');

// Remove any previous cache-busting comments
$content = preg_replace('/\/\/ Cache bust:.*\n/', '', $content);

// Add unique timestamp to EVERY script tag
$timestamp = microtime(true);
$content = str_replace('<script>', '<script>/* v' . $timestamp . ' */', $content);

// Add aggressive no-cache meta tags
$headSearch = '<head>';
$headReplace = '<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">';

$content = str_replace($headSearch, $headReplace, $content);

file_put_contents($file, $content);
echo "✅ Applied aggressive cache busting\n";
echo "Version: $timestamp\n";

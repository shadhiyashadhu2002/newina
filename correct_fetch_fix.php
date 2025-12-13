<?php

$file = 'resources/views/stafftarget.blade.php';
$content = file_get_contents($file);

// Fix editTarget fetch - replace backtick with opening parenthesis
$content = str_replace(
    '    fetch`/staff-target/${id}/edit`)',
    '    fetch(`/staff-target/${id}/edit`)',
    $content
);

// Fix viewDetails fetch - replace backtick with opening parenthesis  
$content = str_replace(
    '    fetch`/staff-target/${id}/view`)',
    '    fetch(`/staff-target/${id}/view`)',
    $content
);

file_put_contents($file, $content);
echo "Fixed! Replaced backticks with parentheses.\n";

// Verify
$lines = explode("\n", $content);
echo "Line 977: " . trim($lines[976]) . "\n";
echo "Line 995: " . trim($lines[994]) . "\n";
?>

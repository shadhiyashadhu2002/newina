<?php
$file = 'resources/views/stafftarget.blade.php';
$content = file_get_contents($file);

// Direct replacement: change fetch` to fetch(`
$content = str_replace(
    "    });    fetch`/staff-target/\${id}`, {",
    "    });
    
    fetch(`/staff-target/\${id}`, {",
    $content
);

file_put_contents($file, $content);
echo "Fixed fetch backticks to parentheses!\n";
?>

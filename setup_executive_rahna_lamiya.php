<?php
/**
 * Set Rahna and Lamiya as executives with limited navigation
 * Following the same pattern as Mizhi (sales team)
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "Updating Rahna and Lamiya to executive status with limited navigation...\n";
echo "========================================================================\n\n";

// Update Rahna (ID 28680)
$rahna = User::find(28680);
if($rahna) {
    $old_team = $rahna->team;
    $rahna->update(['team' => 'sales']);
    echo "✓ Rahna Updated:\n";
    echo "  - ID: 28680\n";
    echo "  - Email: rahna@inamatrimony.site\n";
    echo "  - Old Team: " . ($old_team ?? 'null') . "\n";
    echo "  - New Team: sales\n";
    echo "  - Navigation: Profiles, Sales, HelpLine, abc\n\n";
} else {
    echo "✗ Rahna (ID 28680) not found!\n\n";
}

// Update Lamiya (ID 28681)
$lamiya = User::find(28681);
if($lamiya) {
    $old_team = $lamiya->team;
    $lamiya->update(['team' => 'sales']);
    echo "✓ Lamiya Updated:\n";
    echo "  - ID: 28681\n";
    echo "  - Email: lamiya@inamatrimony.site\n";
    echo "  - Old Team: " . ($old_team ?? 'null') . "\n";
    echo "  - New Team: sales\n";
    echo "  - Navigation: Profiles, Sales, HelpLine, abc\n\n";
} else {
    echo "✗ Lamiya (ID 28681) not found!\n\n";
}

echo "========================================================================\n";
echo "✓ Successfully set Rahna and Lamiya as executives\n";
echo "✓ They now have limited navigation like other sales executives\n";

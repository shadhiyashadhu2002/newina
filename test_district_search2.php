<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Address;
use App\Models\City;
use App\Models\Member;

echo "Testing District Search with Kannur\n";
echo "===================================\n\n";

// Test district search with Kannur (which we know has data)
$searchDistrict = 'Kannur';

$query = Member::with([
    'user.addresses.city'
])->whereHas('user.addresses.city', function($q) use ($searchDistrict) {
    $q->where('name', 'like', '%' . $searchDistrict . '%');
});

$count = $query->count();
echo "Found {$count} members in {$searchDistrict}\n";

if($count > 0) {
    $results = $query->limit(3)->get();
    foreach($results as $member) {
        $cityName = 'Unknown';
        if($member->user && $member->user->addresses->count() > 0) {
            $address = $member->user->addresses->first();
            $cityName = $address->city ? $address->city->name : 'No City';
        }
        echo "- Member: {$member->user->first_name} (ID: {$member->id}) - City: {$cityName}\n";
    }
}

echo "\n";

// Test case-insensitive search
echo "Testing case-insensitive search with 'kannur':\n";
$searchDistrict = 'kannur';

$query = Member::with([
    'user.addresses.city'
])->whereHas('user.addresses.city', function($q) use ($searchDistrict) {
    $q->where('name', 'like', '%' . $searchDistrict . '%');
});

$count = $query->count();
echo "Found {$count} members in {$searchDistrict} (lowercase)\n";

echo "\n";

// Test partial search
echo "Testing partial search with 'Kann':\n";
$searchDistrict = 'Kann';

$query = Member::with([
    'user.addresses.city'
])->whereHas('user.addresses.city', function($q) use ($searchDistrict) {
    $q->where('name', 'like', '%' . $searchDistrict . '%');
});

$count = $query->count();
echo "Found {$count} members with 'Kann' in city name\n";

echo "\nTest completed!\n";
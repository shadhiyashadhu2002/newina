<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Boot Eloquent
use Illuminate\Support\Facades\Artisan;

// Use the models
$shortlists = App\Models\Shortlist::orderByDesc('created_at')->limit(50)->get(['id','profile_id','prospect_id','prospect_name','shortlisted_by','user_id','source','created_at']);

echo json_encode($shortlists->map(function($s){
    return [
        'id' => $s->id,
        'profile_id' => $s->profile_id,
        'prospect_id' => $s->prospect_id,
        'prospect_name' => $s->prospect_name,
        'shortlisted_by' => $s->shortlisted_by,
        'user_id' => $s->user_id,
        'source' => $s->source,
        'created_at' => $s->created_at ? $s->created_at->format('Y-m-d H:i:s') : null,
    ];
})->toArray(), JSON_PRETTY_PRINT);

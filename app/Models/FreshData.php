<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FreshData extends Model
{
    use HasFactory;
    protected $table = 'fresh_data';
    protected $fillable = [
        'name',
        'customer_name',
        'mobile',
        'source',
        'welcome_call',
        'status',
        'remarks',
        'follow_up_date',
        'imid',
        'assigned_to',
        'secondary_phone',
        'is_new_lead',
        'last_touched_at'
    ];
    protected $casts = [
        'follow_up_date' => 'date',
        'last_touched_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    public function freshDataAssignment()
    {
        return $this->hasOne(FreshDataAssignment::class, 'profile_id');
    }
    
    public function isAssignedInFreshData()
    {
        return $this->freshDataAssignment()->exists();
    }
    
    // Method to update last touched timestamp
    public function updateLastTouched()
    {
        $this->last_touched_at = now();
        $this->save();
    }
}

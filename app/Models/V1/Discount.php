<?php

namespace App\Models\V1;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'user_id', 'discount_type', 
        'discount', 'description', 'expiration_date'
    ];

    // public function business(): BelongsTo
    // {
    //     return $this->belongsTo(Business::class);
    // }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customerDiscounts(): HasMany
    {
        return $this->hasMany(CustomerDiscount::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignatureVerification extends Model
{
    protected $fillable = [
        'signature_id',
        'verified_by_id',
        'status',
        'remarks',
        'verification_date',
    ];

    protected $casts = [
        'verification_date' => 'datetime',
    ];

    /**
     * Get the signature
     */
    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    /**
     * Get the verifier (HR Administrator/Admin who verified)
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by_id');
    }
}

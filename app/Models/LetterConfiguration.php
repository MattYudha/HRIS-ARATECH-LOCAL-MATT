<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterConfiguration extends Model
{
    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_website',
        'letterhead_footer',
        'letter_number_format',
        'current_number',
        'is_active',
    ];
}

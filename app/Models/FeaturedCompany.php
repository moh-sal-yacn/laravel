<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedCompany extends Model
{
    use HasFactory;

    protected $table = 'featured_companies';

    protected $fillable = ['company_name', 'logo_url', 'contract_date', 'description'];

    protected function casts(): array
    {
        return ['contract_date' => 'date'];
    }
}

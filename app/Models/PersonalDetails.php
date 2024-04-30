<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDetails extends Model
{
    use HasFactory;
    protected $fillable = ['profile_img', 'first_name', 'middle_name', 'last_name', 'birthdate', 'address', 'gender', 'contact_no'];

}

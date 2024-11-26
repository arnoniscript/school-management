<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'max_students',
        'final_date',
        'type',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function remainingSpots()
    {
        return $this->max_students - $this->enrollments()->count();
    }
}

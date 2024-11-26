<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'mother_name',
        'birth_date',
        'email',
        'cpf',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Validação no construtor
        if (!empty($attributes)) {
            $validator = Validator::make($attributes, [
                'full_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'cpf' => 'required|string|size:11|unique:students,cpf',
                'birth_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }
}

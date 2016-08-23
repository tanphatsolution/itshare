<?php
namespace App\Services;
use App\Data\Blog\Domain;

class DomainService
{
    public static function create($email)
    {
        if (!(Domain::where('name', $email)->exists())) {
            Domain::create([
                'name' => $email,
            ]);
        }
        return Domain::where('name', $email)->first();
    }
}

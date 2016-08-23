<?php namespace App\Data\Faq;

use Illuminate\Database\Eloquent\Model;
use App\Data\System\User;

class Clip extends Model
{
    protected $table = 'clips';

    protected $fillable = [
        'question_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);   
    }
}

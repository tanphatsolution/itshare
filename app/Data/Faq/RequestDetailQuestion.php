<?php

namespace App\Data\Faq;

use Illuminate\Database\Eloquent\Model;
use App\Data\System\User;

class RequestDetailQuestion extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function answerDetailQuestions() {
        return $this->hasMany('App\Data\Faq\AnswerDetailQuestion');
    }
}

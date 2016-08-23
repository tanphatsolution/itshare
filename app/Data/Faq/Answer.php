<?php namespace App\Data\Faq;

use Illuminate\Database\Eloquent\Model;
use App\Data\System\User;
use Auth;

class Answer extends Model
{

    protected $fillable = [
        'content',
        'number_helpful',
        'blocked',
        'best_answer',
        'user_id',
        'question_id',
        'parent'
    ];

    protected $dates = [
        'published_at'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);   
    }
    public function subQuestion() {
        return $this->hasMany('App\Data\Faq\Answer', 'parent', 'id');
    }
    public function anserHelpfuls() {
        return $this->hasMany('App\Data\Faq\AnswerHelpful');
    }

    public function getHelpFullButton() {
        if (Auth::check()) {
            $helpful = $this->anserHelpfuls()->where('user_id', Auth::user()->id)->first();
            if ($helpful) {
                return '<button class="unhelpful" data-anserId = "' . $this->id . '">' . trans('labels.question.unhelpful') . '</button>';
            }
        }
        return '<button class="helpful" data-anserId = "' . $this->id . '">' . trans('labels.question.helpful') . '</button>';
    }

}

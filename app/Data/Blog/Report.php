<?php 
namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Report extends BaseModel
{
    const STATUS_ALL = 0;
    const STATUS_UNREAD = 1;
    const STATUS_READ = 2;
    const STATUS_RESOLVED = 3;

    const TYPE_SPAM = 1;
    const TYPE_ILLEGAL_CONTENT = 2;
    const TYPE_HARASSMENT = 3;

    use SoftDeletes;

    protected $table = 'reports';

    protected $guarded = ['id'];

    protected $fillable = ['user_id', 'post_id', 'type', 'status'];

    public static $storeRules = [
        'user_id' => 'required',
        'post_id' => 'required',
        'type' => 'required',
    ];

    public static function filterByStatus($status)
    {
        $all = DB::table('reports')
            ->select(DB::raw('reports.*, reports.username, posts.title, posts.encrypted_id'))
            ->join('users', 'reports.user_id', '=', 'users.id')
            ->join('posts', 'reports.post_id', '=', 'posts.id')
            ->whereNull('reports.deleted_at')
            ->orderBy('reports.created_at', 'desc');
        if ($status) {
            $all = $all->where('reports.status', $status);
        }
        return $all;
    }

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post')->withTrashed();
    }
}

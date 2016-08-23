<?php namespace App\Data\Blog;

class UserRelationships extends BaseModel
{
    // The database table used by the model.
    protected $table = 'user_relationships';

    protected $guarded = ['id'];
    protected $fillable = ['follower_id', 'followed_id'];


    public function follower()
    {
        return $this->belongsTo('App\Data\System\User', 'follower_id');
    }

    public function followed()
    {
        return $this->belongsTo('App\Data\System\User', 'followed_id');
    }

    public function scopeFindId($query, $followerId, $followedId)
    {
        return $query->where('follower_id', $followerId)->where('followed_id', $followedId);
    }
}

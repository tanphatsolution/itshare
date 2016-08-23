<?php namespace App\Data\Blog;

class OauthClientEndpoint extends BaseModel
{

    // Database table used by the model
    protected $table = 'oauth_client_endpoints';

    protected $guarded = ['id'];

    protected $fillable = ['client_id', 'redirect_uri'];
    
}

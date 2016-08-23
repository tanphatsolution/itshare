<?php namespace App\Data\Blog;

class OauthClient extends BaseModel
{

    CONST DISPLAY_CLIENT_PER_PAGE = 10;
    // Database table used by the model
    protected $table = 'oauth_clients';
    protected $guarded = ['id'];

    protected $fillable = ['id', 'secret', 'name'];

    public function oauthClientEndpoint()
    {
        return OauthClientEndpoint::where('client_id', $this->id)->first();
    }
    
}

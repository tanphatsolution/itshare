<?php namespace App\Http\Controllers;

use App\Data\Blog\Post;
use Response;
use Input;
use View;

class StocksController extends BaseController
{
    /**
     * Remove a post from user's stock
     *
     * @return string      true if success ; false if fail.
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        return Response::json($post->removeStock($this->currentUser));
    }

    /**
     * Add a post to user's stock
     *
     * @return string      true if success ; false if fail.
     */
    public function store()
    {
        $post = Post::find((int) Input::get('postId'));
        $html = View::make('post._a_user_stock', ['userStock' => $this->currentUser])->render();
        return Response::json([$post->addStock($this->currentUser), $html]);
    }

    /**
     * Get number of people stored a post
     *
     * @return json      First part : 1 if current user stored, 0 if not - Second part : number of other people stored this post
     */
    public function count()
    {
        $post = Post::find((int) Input::get('postId'));
        $currentUserStored = $post->isStockedBy($this->currentUser) ? 1 : 0;

        return Response::json([$currentUserStored, $post->stocksCount]);
    }
}

<?php namespace App\Http\Controllers;

use App\Data\Blog\Comment;
use App\Data\Blog\Post;
use App\Services\CommentService;
use App\Services\PostService;
use GrahamCampbell\Markdown\Facades\Markdown;
use Input;
use View;
use Response;

class CommentsController extends BaseController
{

    /**
     * Default comment page
     * Route /comment/index
     * @return response
     */
    public function index()
    {
        $limit = Input::get('limit');
        $offset = Input::get('offset');
        $postId = Input::get('post_id');

        if (!is_null($postId) && !empty($postId)) {
            $comments = PostService::getComments($postId, $limit, $offset);
            $view = View::make('comments._list_comments', ['comments' => $comments, 'currentUser' => $this->currentUser, 'lang' => $this->lang])->render();
            $totalComments = Post::find((int)$postId)->comments()->get()->count();

            if ($totalComments - ($offset * CommentService::COMMENT_DISPLAY_PER_PAGE + CommentService::COMMENT_DISPLAY_PER_PAGE) > 0) {
                return ['hasMore' => true, 'view' => $view];
            }

            return ['hasMore' => false, 'view' => $view];
        }

        return false;
    }

    /**
     * Instantiate a new HomeController instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get one comment
     * @param $id
     * Route /comment/{id}
     * @return response
     */
    public function edit($id)
    {
        $comment = Comment::find($id);
        $this->viewData['comment'] = $comment;

        return View::make('comments._a_comment_edit', $this->viewData)->render();
    }

    /**
     * Save a new comment
     * @return response
     */
    public function store()
    {
        $input = Input::all();
        $input['user_id'] = $this->currentUser->id;
        $result = array_combine(['error', 'data'], CommentService::create($input));
        $commentCount = Comment::where('post_id', $input['post_id'])->count();

        if ($result['error'] === false) {
            $this->viewData['comment'] = $result['data'];
            $result['data'] = View::make('comments._a_comment', $this->viewData)->render();
            $result['commentCount'] = $commentCount;
        }

        return Response::json($result);
    }

    /**
     * Update a comment
     * @return response
     */
    public function update()
    {
        $input = Input::all();
        $result = array_combine(['error', 'data'], CommentService::update($input));

        if (!$result['error']) {
            // return comment content parsed by markdown
            $result['data'] = $result['data']->getParsedContent();
        }

        return Response::json($result);
    }

    /**
     * Delete a comment
     * @param $id
     * @return response
     */
    public function destroy($id)
    {
        $result = array_combine(['error', 'message'], CommentService::delete($id));

        return Response::json($result);
    }

    /**
     * Parse a comment's content
     * @return string
     */
    public function preview()
    {
        $input = Input::all();
        $preview = htmlentities($input['content']);
        $markdown = Markdown::convertToHtml($preview);
        return $markdown;
    }

    /**
     * Parse a comment's content
     * @return string
     */
    public function loadComment()
    {
        $postId = Input::get('postId');
        if (!is_null($postId) && !empty($postId)) {
            $post = Post::findByEncryptedId($postId);
            $helpful = $post->getCountHelpful()[0];
            return View::make('comments.comment_form', ['post' => $post, 'currentUser' => $this->currentUser, 'helpful' => $helpful, 'lang' => $this->lang]);
        }
        return null;
    }
}

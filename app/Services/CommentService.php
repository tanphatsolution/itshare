<?php
namespace App\Services;

use App\Data\Blog\Comment;
use View;
use Auth;

class CommentService
{
    const COMMENT_DISPLAY_PER_PAGE = 10;

    /**
     * Save a new comment
     * @param  $input
     * @return array
     */
    public static function create($input)
    {
        $comment = new Comment();
        $comment->content = trim($input['content']);
        $comment->postId = $input['post_id'];
        $comment->userId = $input['user_id'];
        $validator = Comment::validateComment($input);
        $error = true;
        $data = null;

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $message) {
                $data .= $message . "\r\n";
            }
            return [$error, $data];
        }

        if ($comment->save()) {
            $error = false;
            $data = $comment;
        }
        return [$error, $data];
    }

    /**
     * Update a comment
     * @param array $input
     * @return array
     */
    public static function update($input)
    {
        $comment = Comment::find((int) $input['id']);
        $error = true;
        $data = null;

        if (is_null($comment) || (isset($comment) && $input['content'] == $comment->content)) {
            return [$error, $data];
        }

        $comment->content = trim($input['content']);
        if ($comment->save()) {
            $error = false;
            $data = $comment;
        }
        return [$error, $data];
    }

    /**
     * Delete new comment
     * @param string $id
     * @return array
     */
    public static function delete($id)
    {
        $comment = Comment::find($id);
        $error = true;
        $message = trans('comments.delete_failed');

        if (is_null($comment)) {
            return [$error, $message];
        }

        if ($comment->delete()) {
            $error = false;
            $message = trans('comments.has_deleted');
        }
        return [$error, $message];
    }

    /**
     * Get a number of comments
     * @param string $limit
     * @param string $offset
     * @return array
     */
    public static function get($limit = null, $offset = null)
    {
        if (is_null($limit) && is_null($offset)) {
            $comments = Comment::all();
            return $comments;
        } else {
            $limit = is_null($limit) ? CommentService::COMMENT_DISPLAY_PER_PAGE : $limit;
            $offset = is_null($offset) ? 0 : ($offset * CommentService::COMMENT_DISPLAY_PER_PAGE);
            $comments = Comment::with('user')->take($limit)->offset($offset)->get();
            $views = [];
            foreach ($comments as $comment) {
                $views[] = View::make('comments._a_comment', ['comment' => $comment, 'currentUser' => Auth::user()])->render();
            }

            if (Comment::count() - ($offset + CommentService::COMMENT_DISPLAY_PER_PAGE) > 0) {
                return ['hasMore' => true, 'views' => $views];
            }

            return ['hasMore' => false, 'views' => $views];
        }
    }
}

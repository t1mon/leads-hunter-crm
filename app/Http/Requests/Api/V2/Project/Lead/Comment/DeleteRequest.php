<?php

namespace App\Http\Requests\Api\V2\Project\Lead\Comment;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Project\Lead\Comment;
use App\Repositories\Project\Lead\Comment\ReadRepository as CommentReadRepository;

class DeleteRequest extends FormRequest
{
    public function __construct(
        private CommentReadRepository $commentRepository,
    )
    {
        
    } //Конструктор

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $comment = $this->commentRepository->findById(id: $this->comment_id, fail: true);

        return $this->user()->can('delete', [Comment::class, $comment]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment_id' => 'required|exists:leads_comments,id',
        ];
    }
}

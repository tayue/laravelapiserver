<?php
/**
 * Created by PhpStorm.
 * User: hdeng
 * Date: 2019/5/16
 * Time: 14:14
 */

namespace App\Http\Requests;
use Dingo\Api\Http\FormRequest;


class ArticleFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required|string',
            'is_completed' => 'required|boolean'
        ];
    }
}

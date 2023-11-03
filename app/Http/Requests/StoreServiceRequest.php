<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response as HTTP;
use Illuminate\Support\Facades\Response;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            "name" => "required",
            // "parent_id" => "required",
            "category_id" => "required|exists:categories,id",
            "subcategory_id" => "required|exists:categories,id",
            // "provider_id" => "required",
            "zone_id" => "required|integer",
            "price" => "required|integer",
            "type" => "required|in:fixed,hourly",
            "duration" => "required",
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            "discount" => "required|integer",
            // 'status' => 'boolean', // Validate status field
            "short_description" => "required|string",
            "long_description" => "required|string",
            "tax" => "required|integer",
            // "order_count" => "required",
            // "rating_count" => "required",
            // "avg_rating" => "required",
            "is_featured" => "boolean",
            // "by_admin" => "required",
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    // public function attributes()
    // {
    //     return [
    //         "name" => "required",
    //         "email" => "required|email|unique:users,email",
    //         "password" => "required|min:8|max:12",
    //         "confirmed" => "required|same:password",
    //     ];
    // }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // "first_name.required" => Lang::get("auth.first_name.required"),
            // "last_name.required" => Lang::get("auth.last_name.required"),
            // "email.required" => Lang::get("auth.email.required"),
            // "password.required" => Lang::get("auth.password.required"),
            // "confirmed.required" => Lang::get("auth.confirmed.required"),
        ];
    }


    /**
     * @throws \HttpResponseException When the validation rules is not valid
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::json([
            'success'   => false,
            'status' => HTTP::HTTP_UNPROCESSABLE_ENTITY,
            'message'   => 'Validation failed.',
            'errors'     => $validator->errors(),
        ], HTTP::HTTP_UNPROCESSABLE_ENTITY)); // HTTP::HTTP_OK);
    }
}
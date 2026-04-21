<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'category_id'    => ['required', 'exists:categories,id'],
            'title'          => ['required', 'string', 'max:255'],
            'author'         => ['required', 'string', 'max:255'],
            'isbn'           => ['required', 'string', 'unique:books,isbn'],
            'price'          => ['required', 'numeric', 'min:50'],
            'stock_quantity' => ['required', 'integer', 'min:1'],
            'description'    => ['nullable', 'string'],
            'cover_image'    => ['nullable', 'image', 'max:2048'],
        ];
    }
}
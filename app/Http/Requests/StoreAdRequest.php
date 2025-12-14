<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CategoryField;

class StoreAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Must allow authenticated users
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
        ];

        if ($this->category_id) {
            $fields = CategoryField::where('category_id', $this->category_id)->get();
            foreach ($fields as $field) {
                $rule = [];
                if ($field->required) $rule[] = 'required';

                switch ($field->type) {
                    case 'text': $rule[] = 'string'; break;
                    case 'number': $rule[] = 'numeric'; break;
                    case 'select':
                    case 'radio':
                        $choices = $field->options->pluck('value')->toArray();
                        $rule[] = 'in:' . implode(',', $choices);
                        break;
                }

                $rules['fields.' . $field->name] = implode('|', $rule);
            }
        }

        return $rules;
    }
}

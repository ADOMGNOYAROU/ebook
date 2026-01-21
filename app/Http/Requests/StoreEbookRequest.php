<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEbookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // On autorise tout le monde à faire cette requête, la vérification se fera via les middlewares
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ebooks')->ignore($this->route('ebook')?->id)
            ],
            'description' => 'required|string|min:50',
            'author' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'pages' => 'nullable|integer|min:1',
            'language' => 'required|string|size:2',
            'is_free' => 'required|boolean',
        ];

        // Règles spécifiques pour la création
        if ($this->isMethod('post')) {
            $rules['file'] = 'required|file|mimes:pdf|max:10240'; // 10MB max
            $rules['cover'] = 'required|image|mimes:jpeg,png,webp|max:2048'; // 2MB max
        } 
        // Règles spécifiques pour la mise à jour
        else {
            $rules['file'] = 'nullable|file|mimes:pdf|max:10240';
            $rules['cover'] = 'nullable|image|mimes:jpeg,png,webp|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'title.unique' => 'Ce titre est déjà utilisé',
            'description.min' => 'La description doit faire au moins 50 caractères',
            'file.required' => 'Le fichier PDF est obligatoire',
            'file.mimes' => 'Le fichier doit être au format PDF',
            'file.max' => 'Le fichier ne doit pas dépasser 10 Mo',
            'cover.image' => 'La couverture doit être une image valide',
            'cover.mimes' => 'La couverture doit être au format JPG, PNG ou WebP',
            'cover.max' => 'La couverture ne doit pas dépasser 2 Mo',
            'category_id.exists' => 'La catégorie sélectionnée est invalide',
        ];
    }
}
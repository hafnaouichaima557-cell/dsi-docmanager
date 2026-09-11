<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        /*
         * Seul l'Administrateur peut modifier son email.
         */
        $canEditEmail = $user && $user->hasRole('administrateur');

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];

        /*
         * Email :
         * Administrateur → modification autorisée
         * User simple + Responsable → email actuel obligatoire et inchangé
         */
        if ($canEditEmail) {
            $rules['email'] = [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ];
        } else {
            $rules['email'] = [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::in([$user->email]),
            ];
        }

        return $rules;
    }
}
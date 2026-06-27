<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route is already behind the admin middleware.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $jobId = $this->route('job')?->id;

        return [
            'title'       => ['required', 'string', 'max:150'],
            'slug'        => [
                'nullable', 'string', 'max:160', 'alpha_dash',
                Rule::unique('jobs_listings', 'slug')->ignore($jobId),
            ],
            'department'  => ['nullable', 'string', 'max:80'],
            'location'    => ['required', 'string', 'max:80'],
            'type'        => ['required', 'string', 'max:80'],
            'summary'     => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:20000'],
            'form_type'   => ['required', 'in:professional,internship'],
            'ask_commission_question' => ['nullable', 'boolean'],
            'ask_outreach_question'   => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    /**
     * Normalize checkbox values to real booleans before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'ask_commission_question' => $this->boolean('ask_commission_question'),
            'ask_outreach_question'   => $this->boolean('ask_outreach_question'),
            'is_active'               => $this->boolean('is_active'),
        ]);
    }
}

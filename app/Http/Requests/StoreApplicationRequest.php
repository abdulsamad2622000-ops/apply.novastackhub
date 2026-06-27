<?php

namespace App\Http\Requests;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function job(): Job
    {
        return $this->route('job');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $job = $this->job();

        // Shared fields for every form
        $rules = [
            'full_name'       => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email:rfc', 'max:255'],
            'phone'           => ['required', 'string', 'max:50'],
            'additional_info' => ['nullable', 'string', 'max:5000'],
            'website'         => ['nullable', 'prohibited'], // honeypot
        ];

        if ($job->form_type === 'internship') {
            // Student-friendly internship form
            $rules += [
                'city'      => ['required', 'string', 'max:120'],
                'education' => ['required', 'string', 'max:160'],
                'skills'    => ['required', 'string', 'max:2000'],
                'cv'        => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // optional
            ];
        } else {
            // Default professional form
            $rules += [
                'linkedin_url'           => ['required', 'url', 'max:255'],
                'portfolio_url'          => ['nullable', 'url', 'max:255'],
                'experience_years'       => ['required', 'in:Fresher,0–1 year,1–3 years,3+ years'],
                'experience_description' => ['required', 'string', 'min:20', 'max:5000'],
                'cv'                     => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            ];

            if ($job->ask_commission_question) {
                $rules['commission_only'] = ['required', 'in:Yes,No'];
            }
            if ($job->ask_outreach_question) {
                $rules['outreach_platforms']   = ['nullable', 'array'];
                $rules['outreach_platforms.*'] = ['in:LinkedIn,Upwork,Fiverr,Cold Email,Other'];
            }
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'linkedin_url'           => 'LinkedIn profile link',
            'portfolio_url'          => 'portfolio link',
            'experience_years'       => 'years of experience',
            'experience_description' => 'experience description',
            'commission_only'        => 'commission-only answer',
            'outreach_platforms'     => 'outreach platforms',
            'cv'                     => 'CV / resume',
            'additional_info'        => 'additional information',
            'education'              => 'education / semester',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cv.max'             => 'The CV / resume may not be larger than 10 MB.',
            'cv.mimes'           => 'The CV / resume must be a PDF, DOC, or DOCX file.',
            'website.prohibited' => 'Spam detected. Please try again.',
        ];
    }
}

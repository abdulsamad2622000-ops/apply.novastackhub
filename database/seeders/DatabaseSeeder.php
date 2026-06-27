<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title'      => 'Client Acquisition Specialist',
                'department' => 'Sales',
                'location'   => 'Remote',
                'type'       => 'Commission-based',
                'summary'    => 'Generate leads and bring new clients to NovaStackHub. Remote, commission-based role for a driven closer.',
                'description' => "About the role\n"
                    ."We're looking for a Client Acquisition Specialist to drive new business for NovaStackHub. You'll find prospects, run outreach, and convert leads into paying clients.\n\n"
                    ."What you'll do\n"
                    ."- Identify and reach out to potential clients across LinkedIn, Upwork, Fiverr and cold email\n"
                    ."- Build relationships and book qualified calls\n"
                    ."- Work closely with our delivery team to close deals\n\n"
                    ."What we're looking for\n"
                    ."- Experience in lead generation or client acquisition\n"
                    ."- Comfortable working on a commission-only basis\n"
                    ."- Strong written communication in English",
                'ask_commission_question' => true,
                'ask_outreach_question'   => true,
                'is_active'  => true,
            ],
            [
                'title'      => 'Free Web Development Internship',
                'department' => 'Engineering',
                'location'   => 'Remote',
                'type'       => 'Internship · 2 months',
                'form_type'  => 'internship',
                'summary'    => 'A FREE 2-month remote web development internship for passionate students who want real-world experience and professional skills.',
                'description' => "About the internship\n"
                    ."NovaStackHub is offering a FREE 2-month remote Web Development Internship for passionate students who want to gain real-world experience and build professional skills.\n\n"
                    ."What you'll get\n"
                    ."- Hands-on experience on real projects\n"
                    ."- Mentorship from our development team\n"
                    ."- A certificate on successful completion\n"
                    ."- 100% remote — work from anywhere\n\n"
                    ."Who can apply\n"
                    ."- Students passionate about web development\n"
                    ."- Basic knowledge of HTML, CSS and JavaScript is a plus\n"
                    ."- Eager to learn and grow\n\n"
                    ."How to apply\n"
                    ."- Submit your application using the form below\n"
                    ."- Join our internship WhatsApp group: https://chat.whatsapp.com/CCNrW3ugt6Z9mpN94EqyAw\n"
                    ."- Questions? WhatsApp +92 316 8738819 or email info@novastackhub.com",
                'ask_commission_question' => false,
                'ask_outreach_question'   => false,
                'is_active'  => true,
            ],
        ];

        foreach ($jobs as $data) {
            $data['slug'] = Str::slug($data['title']);
            Job::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}

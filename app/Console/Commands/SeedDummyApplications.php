<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedDummyApplications extends Command
{
    /**
     * Usage:
     *   php artisan app:dummy-applications          (creates 50)
     *   php artisan app:dummy-applications 200      (creates 200)
     *   php artisan app:dummy-applications 200 --fresh   (delete old dummy first)
     */
    protected $signature = 'app:dummy-applications {count=50 : How many to create} {--fresh : Delete existing dummy applications first}';

    protected $description = 'Insert dummy applications for testing the admin panel';

    public function handle(): int
    {
        $count = (int) $this->argument('count');

        $jobs = Job::all();
        if ($jobs->isEmpty()) {
            $this->error('No jobs found. Run "php artisan migrate --seed" first.');
            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            $deleted = Application::where('email', 'like', '%@example.test')->delete();
            $this->warn("Deleted {$deleted} previous dummy application(s).");
        }

        if ($count < 1) {
            $this->info('No new records requested. Done.');
            return self::SUCCESS;
        }

        $first = ['Ali', 'Sara', 'Ahmed', 'Ayesha', 'Bilal', 'Fatima', 'Hassan', 'Zainab', 'Usman', 'Hira', 'Omar', 'Maryam', 'Saad', 'Noor', 'Hamza', 'Iqra', 'Talha', 'Amna', 'Faizan', 'Rabia'];
        $last  = ['Khan', 'Ahmed', 'Malik', 'Hussain', 'Raza', 'Sheikh', 'Butt', 'Qureshi', 'Siddiqui', 'Chaudhry', 'Iqbal', 'Javed', 'Aslam', 'Farooq'];
        $statuses = Application::STATUSES;
        $years = ['Fresher', '0–1 year', '1–3 years', '3+ years'];
        $platforms = ['LinkedIn', 'Upwork', 'Fiverr', 'Cold Email', 'Other'];
        $cities = ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta', 'Hyderabad', 'Sialkot'];
        $education = ['BSCS — 3rd semester', 'BSSE — 5th semester', 'BSIT — 7th semester', 'Intermediate (ICS)', 'BSCS — final year', 'BSc — 4th semester'];
        $skillsets = ['HTML, CSS, JavaScript', 'HTML, CSS, Bootstrap, JS', 'React, Tailwind', 'PHP, Laravel, MySQL', 'JavaScript, Node.js', 'WordPress, HTML, CSS'];

        $this->info("Creating {$count} dummy application(s)...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            $job = $jobs->random();
            $fn = $first[array_rand($first)];
            $ln = $last[array_rand($last)];
            $handle = strtolower($fn.'.'.$ln.'.'.Str::random(4));
            $isInternship = $job->form_type === 'internship';

            Application::create([
                'job_id'                 => $job->id,
                'status'                 => $statuses[array_rand($statuses)],
                'full_name'              => "{$fn} {$ln}",
                'email'                  => "{$handle}@example.test",
                'phone'                  => '+92 3'.rand(0, 4).rand(0, 9).' '.rand(1000000, 9999999),
                'city'                   => $isInternship ? $cities[array_rand($cities)] : null,
                'education'              => $isInternship ? $education[array_rand($education)] : null,
                'skills'                 => $isInternship ? $skillsets[array_rand($skillsets)] : null,
                'linkedin_url'           => $isInternship ? null : "https://linkedin.com/in/{$handle}",
                'portfolio_url'          => (! $isInternship && rand(0, 1)) ? "https://github.com/{$handle}" : null,
                'experience_years'       => $isInternship ? null : $years[array_rand($years)],
                'experience_description' => $isInternship ? null : 'Sample experience for testing. I have worked on various projects relevant to this role.',
                'commission_only'        => (! $isInternship && $job->ask_commission_question) ? (bool) rand(0, 1) : null,
                'outreach_platforms'     => (! $isInternship && $job->ask_outreach_question) ? array_slice($platforms, 0, rand(1, 4)) : null,
                'cv_path'                => $isInternship && rand(0, 1) ? null : 'cvs/sample.pdf',
                'cv_original_name'       => $isInternship && rand(0, 1) ? null : "{$fn}_{$ln}_CV.pdf",
                'additional_info'        => rand(0, 1) ? 'Available to start immediately.' : null,
                'ip_address'             => '127.0.0.1',
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! Total applications now: ".Application::count());
        $this->comment('Tip: remove dummy data later with  php artisan app:dummy-applications --fresh 0');

        return self::SUCCESS;
    }
}

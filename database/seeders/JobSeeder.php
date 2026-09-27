<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $recruiters = User::query()->where('status', 'job_poster')->orderBy('id')->get();

        if ($recruiters->isEmpty()) {
            return;
        }

        foreach ($this->listings() as $index => $listing) {
            $recruiter = $recruiters[$index % $recruiters->count()];

            $job = $recruiter->jobs()->firstOrNew(
                ['title' => $listing['title'], 'company' => $listing['company']]
            );

            $job->fill($listing);

            if (! $job->exists && $listing['status'] === 'published') {
                $job->published_at = Carbon::now();
            }

            $job->save();
        }
    }

    private function listings(): array
    {
        return [
            [
                'title' => 'Senior Product Designer',
                'company' => 'Northstar Labs',
                'department' => 'Design',
                'category' => 'Product',
                'employment_type' => 'full_time',
                'experience_level' => 'senior',
                'description' => 'Join a focused, collaborative team building products that make complex work feel clear and human. You will own end-to-end design for the onboarding surface used by thousands of teams.',
                'responsibilities' => [
                    'Lead design for one product area from discovery to delivery',
                    'Grow and maintain the shared design system in Figma',
                    'Run usability sessions with customers every sprint',
                ],
                'requirements' => [
                    '6+ years designing B2B products',
                    'Portfolio showing shipped, measurable work',
                    'Comfort working directly with engineers and PMs',
                ],
                'skills' => ['Product design', 'Figma', 'Design systems'],
                'salary_min' => 145000,
                'salary_max' => 175000,
                'pay_period' => 'annual',
                'show_salary_range' => true,
                'work_mode' => 'hybrid',
                'location' => 'New York, NY',
                'location_note' => 'Two days a week in the office, flexible time zone',
                'application_method' => 'platform',
                'status' => 'published',
                'featured' => true,
            ],
            [
                'title' => 'Staff Frontend Engineer',
                'company' => 'Lattice Health',
                'department' => 'Engineering',
                'category' => 'Web',
                'employment_type' => 'full_time',
                'experience_level' => 'lead',
                'description' => 'Join a focused, collaborative team building products that make complex care feel clear and human. You will set the frontend technical direction across four product squads.',
                'responsibilities' => [
                    'Own architecture decisions for the React application',
                    'Mentor engineers and review design documents',
                    'Improve accessibility to a WCAG AA baseline',
                ],
                'requirements' => [
                    '8+ years of frontend engineering',
                    'Deep TypeScript and React knowledge',
                    'Experience shipping to regulated environments',
                ],
                'skills' => ['React', 'TypeScript', 'Accessibility'],
                'salary_min' => 170000,
                'salary_max' => 205000,
                'pay_period' => 'annual',
                'show_salary_range' => true,
                'work_mode' => 'remote',
                'location' => 'United States',
                'location_note' => 'Fully remote within US time zones',
                'application_method' => 'platform',
                'status' => 'published',
                'featured' => false,
            ],
            [
                'title' => 'Product Marketing Manager',
                'company' => 'Common Thread',
                'department' => 'Marketing',
                'category' => 'Growth',
                'employment_type' => 'full_time',
                'experience_level' => 'senior',
                'description' => 'Join a focused, collaborative team building products that make complex work feel clear and human. You will own positioning and launch for the analytics suite.',
                'responsibilities' => [
                    'Write positioning, messaging and launch narratives',
                    'Run competitive research every quarter',
                    'Partner with sales on enablement material',
                ],
                'requirements' => [
                    '5+ years in B2B SaaS marketing',
                    'Track record of product launches',
                    'Strong analytical writing',
                ],
                'skills' => ['B2B SaaS', 'GTM', 'Research'],
                'salary_min' => 125000,
                'salary_max' => 150000,
                'pay_period' => 'annual',
                'show_salary_range' => true,
                'work_mode' => 'hybrid',
                'location' => 'Austin, TX',
                'location_note' => 'One office day per week',
                'application_method' => 'email',
                'status' => 'published',
                'featured' => false,
            ],
            [
                'title' => 'UX Researcher',
                'company' => 'Fable Finance',
                'department' => 'Design',
                'category' => 'Research',
                'employment_type' => 'contract',
                'experience_level' => 'mid',
                'description' => 'Join a focused, collaborative team building products that make money management feel clear and human. Six month contract with a likely extension.',
                'responsibilities' => [
                    'Plan and run qualitative studies',
                    'Synthesise findings into readable reports',
                    'Build the research repository',
                ],
                'requirements' => [
                    '4+ years in product research',
                    'Fintech or regulated domain experience',
                    'Comfortable recruiting participants yourself',
                ],
                'skills' => ['Qualitative', 'Fintech', 'Strategy'],
                'salary_min' => 130000,
                'salary_max' => 160000,
                'pay_period' => 'annual',
                'show_salary_range' => true,
                'work_mode' => 'onsite',
                'location' => 'San Francisco, CA',
                'location_note' => 'Onsite five days a week during onboarding',
                'application_method' => 'link',
                'status' => 'published',
                'featured' => false,
            ],
            [
                'title' => 'Customer Success Lead',
                'company' => 'Riverbend Studio',
                'department' => 'Customer',
                'category' => 'Support',
                'employment_type' => 'full_time',
                'experience_level' => 'mid',
                'description' => 'Join a focused, collaborative team building products that make creative work feel clear and human. You will lead three customer success specialists.',
                'responsibilities' => [
                    'Own onboarding for the top 50 accounts',
                    'Build renewal and expansion playbooks',
                    'Report quarterly health to leadership',
                ],
                'requirements' => [
                    '5+ years in customer success',
                    'Experience managing a small team',
                    'Comfort with CRM and product analytics tools',
                ],
                'skills' => ['Onboarding', 'Retention', 'SaaS'],
                'salary_min' => 95000,
                'salary_max' => 115000,
                'pay_period' => 'annual',
                'show_salary_range' => true,
                'work_mode' => 'hybrid',
                'location' => 'Chicago, IL',
                'location_note' => null,
                'application_method' => 'platform',
                'status' => 'published',
                'featured' => false,
            ],
            [
                'title' => 'Senior Backend Engineer',
                'company' => 'Northstar Labs',
                'department' => 'Engineering',
                'category' => 'Platform',
                'employment_type' => 'full_time',
                'experience_level' => 'senior',
                'description' => 'Join a focused, collaborative team building products that make complex work feel clear and human. You will design services behind the Laravel monolith and split them out safely.',
                'responsibilities' => [
                    'Ship and operate Laravel services',
                    'Improve query performance on large tables',
                    'Write migration plans other engineers can run',
                ],
                'requirements' => [
                    '6+ years with PHP and a modern framework',
                    'Strong MySQL and queue background',
                    'Experience with automated test suites',
                ],
                'skills' => ['PHP', 'Laravel', 'MySQL'],
                'salary_min' => 130000,
                'salary_max' => 160000,
                'pay_period' => 'annual',
                'show_salary_range' => true,
                'work_mode' => 'remote',
                'location' => 'Europe',
                'location_note' => 'Overlap of four hours with CET',
                'application_method' => 'platform',
                'status' => 'published',
                'featured' => true,
            ],
            [
                'title' => 'Data Analyst',
                'company' => 'Lattice Health',
                'department' => 'Data',
                'category' => 'Analytics',
                'employment_type' => 'full_time',
                'experience_level' => 'entry',
                'description' => 'Join a focused, collaborative team building products that make care decisions feel clear and human. Great first role for someone who learns fast and writes careful SQL.',
                'responsibilities' => [
                    'Maintain dashboards for the clinical team',
                    'Answer ad hoc questions with SQL',
                    'Check data quality before publishing numbers',
                ],
                'requirements' => [
                    '1+ year with SQL in a work setting',
                    'Spreadsheet fluency',
                    'Interest in healthcare data',
                ],
                'skills' => ['SQL', 'Looker', 'Python'],
                'salary_min' => 90000,
                'salary_max' => 110000,
                'pay_period' => 'annual',
                'show_salary_range' => true,
                'work_mode' => 'onsite',
                'location' => 'Boston, MA',
                'location_note' => null,
                'application_method' => 'email',
                'status' => 'published',
                'featured' => false,
            ],
            [
                'title' => 'Design Engineering Intern',
                'company' => 'Common Thread',
                'department' => 'Design',
                'category' => 'Internship',
                'employment_type' => 'internship',
                'experience_level' => 'entry',
                'description' => 'Join a focused, collaborative team building products that make complex work feel clear and human. Twelve week paid internship with a mentor in the design systems team.',
                'responsibilities' => [
                    'Build Figma-to-code component prototypes',
                    'Fix small frontend bugs',
                    'Document component usage',
                ],
                'requirements' => [
                    'Currently studying design or engineering',
                    'Basic HTML and CSS',
                    'A portfolio or GitHub you can walk through',
                ],
                'skills' => ['React', 'CSS', 'Figma'],
                'salary_min' => 3200,
                'salary_max' => 3600,
                'pay_period' => 'monthly',
                'show_salary_range' => true,
                'work_mode' => 'remote',
                'location' => 'Worldwide',
                'location_note' => 'Async first, weekly video check-in',
                'application_method' => 'link',
                'status' => 'published',
                'featured' => false,
            ],
            [
                'title' => 'Growth Marketing Manager',
                'company' => 'Northstar Labs',
                'department' => 'Marketing',
                'category' => 'Growth',
                'employment_type' => 'full_time',
                'experience_level' => 'mid',
                'description' => 'Draft listing kept unpublished so the marketplace filter can be tested end to end.',
                'responsibilities' => ['Own paid experiments', 'Report weekly on funnel movement'],
                'requirements' => ['4+ years in growth roles'],
                'skills' => ['SEO', 'Paid media', 'Experimentation'],
                'salary_min' => 110000,
                'salary_max' => 135000,
                'pay_period' => 'annual',
                'show_salary_range' => false,
                'work_mode' => 'hybrid',
                'location' => 'New York, NY',
                'location_note' => null,
                'application_method' => 'platform',
                'status' => 'draft',
                'featured' => false,
            ],
        ];
    }
}

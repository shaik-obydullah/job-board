<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Programming & Web
            'PHP', 'Laravel', 'JavaScript', 'TypeScript', 'Python', 'Ruby', 'Go', 'Java', 'C#', 'C++',
            'React', 'Vue.js', 'Angular', 'Next.js', 'Node.js', 'Express', 'Django', 'Rails',
            'HTML', 'CSS', 'Tailwind CSS', 'Bootstrap', 'SASS', 'jQuery', 'Livewire', 'Alpine.js',
            'SQL', 'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Elasticsearch',
            'Docker', 'Kubernetes', 'AWS', 'Azure', 'Google Cloud', 'Nginx', 'Linux', 'Git', 'CI/CD',
            // Data & AI
            'Machine Learning', 'Deep Learning', 'Data Analysis', 'Data Science', 'TensorFlow', 'PyTorch', 'Power BI', 'Tableau', 'Excel', 'Statistics',
            // Design
            'UI Design', 'UX Design', 'Figma', 'Adobe Photoshop', 'Illustrator', 'Sketch', 'Wireframing', 'Prototyping', 'Design Systems',
            // Marketing & Sales
            'SEO', 'SEM', 'Google Analytics', 'Content Marketing', 'Social Media Marketing', 'Email Marketing', 'Copywriting',
            'Salesforce', 'CRM', 'Lead Generation', 'Negotiation', 'Public Speaking', 'Cold Calling',
            // Business
            'Project Management', 'Agile', 'Scrum', 'Kanban', 'Jira', 'Product Management', 'Business Analysis', 'Finance', 'Accounting', 'Budgeting',
            // Soft skills
            'Communication', 'Leadership', 'Teamwork', 'Problem Solving', 'Time Management', 'Adaptability', 'Creativity', 'Critical Thinking',
            // Other
            'Photography', 'Videography', 'Video Editing', 'Digital Marketing', 'Blockchain', 'Cybersecurity', 'Network Security', 'DevOps', 'QA Testing', 'Technical Writing',
        ];

        foreach ($skills as $name) {
            Skill::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}

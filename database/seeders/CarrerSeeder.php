<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Carrer;
use Illuminate\Database\Seeder;

class CarrerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carrers = [
            [
                'name' => 'Software Engineer',
                'description' => '👋 About Us:\nWe are a fast-growing tech company powered by coffee, Wi-Fi, and occasional panic before deadlines. We believe in clean code, messy whiteboards, and the idea that every bug is just a misunderstood feature.\n\n💼 What You Will Do:\nAs a Software Engineer, you will heroically battle bugs, wrestle with APIs, and argue politely with your compiler. You will design, develop, test, deploy, break, fix, and re-fix software applications for web, mobile, and backend platforms. You will collaborate with designers who love colors, product managers who love deadlines, and other developers who love semicolons.\n\n🧠 What We Expect:\nWe expect you to write clean, efficient, and readable code that future you (and future teammates) will not hate. You will review code, optimize performance, refactor legacy code written by someone who has clearly left the company, and occasionally explain to non-technical people why “just a small change” takes three days.\n\n🛠 What You Need:\nExperience with modern programming languages, frameworks, Git, debugging skills, and the emotional strength to handle merge conflicts. Ability to Google efficiently is considered a superpower.\n\n🎁 What You Get:\nCompetitive salary, flexible working hours, remote-friendly culture, learning opportunities, and the satisfaction of seeing your code work (sometimes).\n\n📩 How to Apply:\nFill out the form below and send us your CV and cover letter. Bonus points if your cover letter includes a joke about JavaScript.',
                'created_at' => '2025-11-01 09:00:00',
                'updated_at' => '2025-11-01 09:00:00',
                'location' => 'Helsinki, Texas',
            ],

            [
                'name' => 'UI/UX Designer',
                'description' => '👋 About Us:\nWe design products that users actually enjoy using — or at least complain less about. Our mission is to make buttons clickable, colors readable, and users happy.\n\n💼 What You Will Do:\nAs a UI/UX Designer, you will turn confusing ideas into beautiful, user-friendly designs. You will create wireframes, prototypes, and designs that developers will try very hard to implement correctly. You will argue about font sizes, spacing, and whether that button should be 2 pixels to the left.\n\n🧠 What We Expect:\nWe expect you to deeply understand users, empathize with their pain, and defend good design decisions with passion. You will collaborate with developers, product managers, and stakeholders who think design is “just making things look pretty.”\n\n🛠 What You Need:\nExperience with design tools, a strong portfolio, attention to detail, and the ability to explain why Comic Sans is not acceptable.\n\n🎁 What You Get:\nCreative freedom, collaborative environment, modern tools, and the joy of seeing users actually enjoy your designs.\n\n📩 How to Apply:\nFill out the form below and send us your CV, cover letter, and portfolio. Extra love if your portfolio has a dark mode.',
                'created_at' => '2025-11-02 10:15:00',
                'updated_at' => '2025-11-02 10:15:00',
                'location' => 'Helsinki, Nepal, US, Estonia',

            ],

            [
                'name' => 'Digital Marketing Specialist',
                'description' => '👋 About Us:\nWe believe marketing is part science, part art, and part guessing what the algorithm wants today. Our brand grows because of creativity, data, and sometimes pure luck.\n\n💼 What You Will Do:\nAs a Digital Marketing Specialist, you will plan, launch, analyze, and optimize marketing campaigns across social media, SEO, and content platforms. You will write catchy headlines, track metrics, adjust strategies, and explain to everyone why a post with a cat performed better.\n\n🧠 What We Expect:\nWe expect you to be data-driven, creative, and slightly obsessed with analytics dashboards. You will stay up to date with trends, algorithms, and new platforms that appear overnight.\n\n🛠 What You Need:\nExperience in digital marketing tools, SEO knowledge, content creation skills, and the patience to wait for organic reach.\n\n🎁 What You Get:\nGrowth opportunities, creative freedom, measurable impact, and the satisfaction of watching numbers go up.\n\n📩 How to Apply:\nFill out the form below and send us your CV and cover letter. Show us a campaign you’re proud of.',
                'created_at' => '2025-11-03 11:30:00',
                'updated_at' => '2025-11-03 11:30:00',
                'location' => 'Remote, Helsinki, Texas',

            ],

            [
                'name' => 'Cloud Solutions Architect',
                'description' => '👋 About Us:\nWe love the cloud. Not the sky ones — the scalable, secure, always-on infrastructure kind. We help companies move to the cloud without losing sleep.\n\n💼 What You Will Do:\nAs a Cloud Solutions Architect, you will design and implement cloud infrastructures that are fast, scalable, and surprisingly affordable. You will diagram systems, choose services wisely, and explain to others why the cloud is not just “someone else’s computer.”\n\n🧠 What We Expect:\nWe expect you to think in architectures, anticipate failures, and build systems that survive traffic spikes and human mistakes.\n\n🛠 What You Need:\nExperience with cloud platforms, networking, security best practices, and the ability to read documentation without fear.\n\n🎁 What You Get:\nChallenging projects, cutting-edge technology, flexible work environment, and bragging rights.\n\n📩 How to Apply:\nFill out the form below and send us your CV and cover letter. Cloud diagrams are welcome.',
                'created_at' => '2025-11-04 12:45:00',
                'updated_at' => '2025-11-04 12:45:00',
                'location' => 'Helsinki, Texas',

            ],

            [
                'name' => 'Cybersecurity Analyst',
                'description' => '👋 About Us:\nWe take security seriously — because hackers never sleep. Our mission is to protect systems, data, and everyone’s peace of mind.\n\n💼 What You Will Do:\nAs a Cybersecurity Analyst, you will monitor systems, analyze threats, respond to incidents, and stop attacks before anyone notices. You will think like a hacker, but behave like a hero.\n\n🧠 What We Expect:\nWe expect attention to detail, strong analytical thinking, and the ability to stay calm when alarms go off at 3 a.m.\n\n🛠 What You Need:\nKnowledge of security tools, networking, threat analysis, and a healthy level of paranoia.\n\n🎁 What You Get:\nMeaningful work, professional growth, and the satisfaction of knowing you kept everything safe.\n\n📩 How to Apply:\nFill out the form below and send us your CV and cover letter. Ethical hackers welcome.',
                'created_at' => '2025-11-05 14:00:00',
                'updated_at' => '2025-11-05 14:00:00',
                'location' => 'Helsinki, Texas',

            ],
        ];


        Career::insert($carrers);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Confession;
use Illuminate\Database\Seeder;

/**
 * ConfessionSeeder
 *
 * Seeds the database with realistic sample confessions
 * for KIU (Kutaisi International University).
 *
 * Run with: php artisan db:seed
 */
class ConfessionSeeder extends Seeder
{
    public function run(): void
    {
        $confessions = [
            // ── Approved ──────────────────────────────────────────────────────
            [
                'title'       => 'I pulled an all-nighter and still failed',
                'description' => 'Studied for 18 hours straight for the Web Dev midterm, drank 4 energy drinks, and somehow still couldn\'t remember what MVC stands for during the exam. The irony is that I wrote a whole CRUD app last week. I think I just freeze under pressure. Does anyone else experience this? 😭',
                'category'    => 'Study Life',
                'status'      => 'approved',
                'deadline'    => now()->addDays(7)->toDateString(),
            ],
            [
                'title'       => 'I have a crush on someone from Room 204',
                'description' => 'There\'s this person who always sits by the window in Room 204 during the Tuesday morning lectures. They have the most genuine smile whenever the professor makes a terrible programming joke. I\'ve been trying to say hello for three weeks. Maybe next Tuesday? 🫣',
                'category'    => 'Crush',
                'status'      => 'approved',
                'deadline'    => now()->addDays(14)->toDateString(),
            ],
            [
                'title'       => 'Accidentally submitted homework to the wrong professor',
                'description' => 'Sent my Database homework to my English Literature professor. She replied saying it was "creative" and gave me feedback on my variable naming. Best English grade I\'ve ever gotten. 💀',
                'category'    => 'Funny',
                'status'      => 'approved',
                'deadline'    => null,
            ],
            [
                'title'       => 'I copy-pasted code from Stack Overflow and it worked',
                'description' => 'Not proud of it, but I had 20 minutes left on my lab assignment. Found a Stack Overflow answer from 2017, changed the variable names, and it passed all the tests. The professor said my logic was "impressively clean." I feel like an imposter.',
                'category'    => 'Study Life',
                'status'      => 'approved',
                'deadline'    => null,
            ],
            [
                'title'       => 'The campus WiFi is the real final boss',
                'description' => 'I have successfully written better code than some senior devs on GitHub, but I cannot submit it because the university WiFi disconnects every 8 minutes. I have started timing my git pushes between disconnections. I have lost 3 assignments this semester.',
                'category'    => 'Serious',
                'status'      => 'approved',
                'deadline'    => now()->addDays(3)->toDateString(),
            ],
            [
                'title'       => 'Our professor arrived 40 mins late then marked us for being late',
                'description' => 'Had a 9am class. Professor arrived at 9:40. Two students who left at 9:35 were marked absent. We were too scared to say anything. I respect the consistency at least? 😅',
                'category'    => 'Professor',
                'status'      => 'approved',
                'deadline'    => null,
            ],

            // ── Pending ───────────────────────────────────────────────────────
            [
                'title'       => 'I genuinely don\'t understand what a linked list is',
                'description' => 'I\'m in my second year of Computer Science and I have been faking my way through every data structures class. I nod along, write things down, but the moment class ends it\'s gone. Please someone explain like I\'m 5. I cannot be alone in this.',
                'category'    => 'Study Life',
                'status'      => 'pending',
                'deadline'    => now()->addDays(1)->toDateString(),
            ],
            [
                'title'       => 'I laughed at the wrong time in a presentation',
                'description' => 'My classmate was presenting their final project on food waste management. They said something completely normal and I accidentally burst out laughing because I was thinking about a meme. The whole room went silent. I wanted to disappear.',
                'category'    => 'Funny',
                'status'      => 'pending',
                'deadline'    => now()->addDays(2)->toDateString(),
            ],

            // ── Rejected ──────────────────────────────────────────────────────
            [
                'title'       => 'SPAM TEST - ignore',
                'description' => 'This is a test spam submission that should be rejected by the admin.',
                'category'    => 'Other',
                'status'      => 'rejected',
                'deadline'    => null,
            ],
        ];

        foreach ($confessions as $data) {
            Confession::create($data);
        }

        $this->command->info('✅ Seeded ' . count($confessions) . ' sample confessions.');
    }
}

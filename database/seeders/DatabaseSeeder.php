<?php
namespace Database\Seeders;

use App\Models\{Badge, Faq, Setting, Testimonial, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        // Build data without optional columns that may not exist
        $adminData = [
            'name'           => 'Hilly Chilly Admin',
            'password'       => Hash::make('password'),
            'is_admin'       => true,
            'points_balance' => 0,
        ];
        // Support username column if it exists on the shared users table
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'username')) {
            $adminData['username'] = 'admin_hc';
        }
        User::firstOrCreate(['email' => 'admin@hillychilly.com'], $adminData);

        // Default settings
        $settings = [
            ['key' => 'app_store_url',    'label' => 'App Store URL',    'group' => 'app',     'value' => '#'],
            ['key' => 'play_store_url',   'label' => 'Play Store URL',   'group' => 'app',     'value' => '#'],
            ['key' => 'contact_email',    'label' => 'Contact Email',     'group' => 'contact', 'value' => 'hello@hillychilly.com'],
            ['key' => 'contact_phone',    'label' => 'Contact Phone',     'group' => 'contact', 'value' => '+977 1 XXXX XXXX'],
            ['key' => 'instagram_url',    'label' => 'Instagram',         'group' => 'social',  'value' => '#'],
            ['key' => 'facebook_url',     'label' => 'Facebook',          'group' => 'social',  'value' => '#'],
        ];
        foreach ($settings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }

        // Sample badges
        $badges = [
            ['name' => 'First Trek',       'category' => 'beginner',  'required_points' => 50,   'description' => 'Complete your first quest package'],
            ['name' => 'Trail Blazer',     'category' => 'trekking',  'required_points' => 200,  'description' => 'Complete 3 trekking packages'],
            ['name' => 'Culture Seeker',   'category' => 'cultural',  'required_points' => 150,  'description' => 'Complete a cultural package'],
            ['name' => 'Wildlife Watcher', 'category' => 'wildlife',  'required_points' => 200,  'description' => 'Complete a wildlife package'],
            ['name' => 'Point Collector',  'category' => 'rewards',   'required_points' => 500,  'description' => 'Accumulate 500 points'],
            ['name' => 'EBC Legend',       'category' => 'legendary', 'required_points' => 1000, 'description' => 'Complete the Everest Base Camp package'],
        ];
        foreach ($badges as $b) Badge::firstOrCreate(['name' => $b['name']], $b);

        // Sample FAQs
        $faqs = [
            ['question' => 'What is Hilly Chilly?',         'answer' => 'Hilly Chilly is Nepal\'s first gamified adventure app. You choose a quest package, complete tasks along your journey (GPS check-ins, QR scans, photo proofs), earn points, and unlock badges and rewards.', 'category' => 'general'],
            ['question' => 'How do I earn points?',         'answer' => 'You earn points by completing tasks within each quest package — GPS check-ins, QR code scans, photo proof uploads, and cultural quizzes. Each task has a points value.', 'category' => 'rewards'],
            ['question' => 'Can I use the app offline?',    'answer' => 'Yes! You can download quest packages before your trek. You can complete GPS and photo tasks offline, and they sync when you regain connectivity.', 'category' => 'app'],
            ['question' => 'How do I redeem my points?',    'answer' => 'Points can be redeemed for discounts on future bookings, free nights at partner hotels, and exclusive Hilly Chilly merchandise. Visit the Rewards section in the app.', 'category' => 'rewards'],
            ['question' => 'What payment methods are accepted?', 'answer' => 'We accept eSewa, Khalti, bank transfer, and cash. All payments are processed securely.', 'category' => 'payments'],
            ['question' => 'Is Chilly AI free to use?',    'answer' => 'Yes! Chilly AI is included with the free app. Just open the AI chat and describe your travel plans — Chilly will recommend the best packages for you.', 'category' => 'app'],
        ];
        foreach ($faqs as $i => $faq) Faq::firstOrCreate(['question' => $faq['question']], array_merge($faq, ['sort_order' => $i, 'is_visible' => true]));

        // Sample testimonials
        $testimonials = [
            ['name' => 'Riya Sharma',    'package_name' => 'Pokhara Trek',       'content' => 'Hilly Chilly turned my vacation into a game! The QR scanning at Barahi Temple was so fun. Earned 250 points in one weekend!',     'rating' => 5],
            ['name' => 'Aakash Tamang',  'package_name' => 'EBC Explorer',        'content' => 'Standing at Namche Bazaar and scanning the QR — I felt like a real adventurer completing a mission. Brilliant app!',              'rating' => 5],
            ['name' => 'Priya Rai',      'package_name' => 'Chitwan Safari',      'content' => 'The AI suggested the Chitwan wildlife package when I said I love animals. Perfect recommendation! Booked in 2 minutes.',           'rating' => 5],
            ['name' => 'Bikram KC',      'package_name' => 'Annapurna Circuit',   'content' => 'Completed 700 points from the Annapurna circuit! Used them for a discount on my next booking. Amazing reward system.',            'rating' => 5],
        ];
        foreach ($testimonials as $i => $t) Testimonial::firstOrCreate(['name' => $t['name']], array_merge($t, ['sort_order' => $i, 'is_visible' => true]));
    }
}

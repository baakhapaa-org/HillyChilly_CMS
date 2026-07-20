<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * PilotQuestSeeder
 *
 * Run this on production to:
 *  1. Deactivate every package that is NOT part of the Palungtar/Kathmandu pilot.
 *  2. Ensure the existing Palungtar Explorer quest is active.
 *  3. Create the new "Palungtar Heritage & Discovery Trail" quest with 9 tasks.
 *  4. Ensure two Kathmandu quests (Heritage Walk + Swayambhunath) are active.
 *
 * Usage:
 *   php artisan db:seed --class=PilotQuestSeeder
 */
class PilotQuestSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Deactivate everything ──────────────────────────────────────────
        Package::query()->update(['is_active' => false]);
        $this->command->info('All packages deactivated.');

        // ── 2. Activate / upsert the existing Palungtar Explorer quest ────────
        $palungtar1 = Package::updateOrCreate(
            ['slug' => 'palungtar-explorer-internal-pilot'],
            [
                'title'          => 'Palungtar Explorer — Internal Pilot',
                'description'    => 'An internal pilot quest across 5 checkpoints in Palungtar, Gorkha. '
                    . 'Scan QR codes, meet local businesses, contribute a community story, '
                    . 'reflect at a sacred site, and close with a team photo at the viewpoint.',
                'category'       => 'cultural',
                'duration_days'  => 1,
                'price_npr'      => 0,
                'points_reward'  => 340,
                'image_url'      => 'https://images.unsplash.com/photo-1598971639058-fab3c3109a73?w=800',
                'location_lat'   => 28.0750,
                'location_lng'   => 84.7130,
                'location_label' => 'Palungtar, Gorkha',
                'is_active'      => true,
                'is_featured'    => true,
                'is_free'        => true,
            ]
        );

        // Seed its tasks only if none exist yet
        if ($palungtar1->tasks()->count() === 0) {
            $this->seedPalungtar1Tasks($palungtar1->id);
        } else {
            $this->command->info("Palungtar Explorer tasks already exist — skipping.");
        }
        $this->command->info("Palungtar Explorer (ID {$palungtar1->id}) activated.");

        // ── 3. Create the NEW Palungtar Heritage & Discovery Trail (9 tasks) ──
        $palungtar2 = Package::updateOrCreate(
            ['slug' => 'palungtar-heritage-discovery-trail'],
            [
                'title'          => 'Palungtar Heritage & Discovery Trail',
                'description'    => 'A deep-dive quest across 9 checkpoints in Palungtar Municipality. '
                    . 'Visit the riverside, the municipal hub, agricultural terraces, a sacred temple, '
                    . 'a local school, and the hilltop viewpoint overlooking the Ganesh Himal range.',
                'category'       => 'cultural',
                'duration_days'  => 1,
                'price_npr'      => 0,
                'points_reward'  => 550,
                'image_url'      => 'https://images.unsplash.com/photo-1598971639058-fab3c3109a73?w=800',
                'location_lat'   => 28.0750,
                'location_lng'   => 84.7130,
                'location_label' => 'Palungtar, Gorkha',
                'is_active'      => true,
                'is_featured'    => true,
                'is_free'        => true,
            ]
        );

        // Always re-seed tasks (idempotent: delete + recreate)
        $palungtar2->tasks()->delete();
        $this->seedPalungtar2Tasks($palungtar2->id);
        $this->command->info("Palungtar Heritage Trail (ID {$palungtar2->id}) created with 9 tasks.");

        // ── 4. Activate the two Kathmandu quests ─────────────────────────────
        $ktmQuests = [
            [
                'slug'           => 'kathmandu-heritage-walk',
                'title'          => 'Kathmandu Heritage Walk',
                'description'    => 'Walk through the ancient streets of Kathmandu, visiting Pashupatinath, '
                    . 'Boudhanath, and the old Durbar Square area. Complete cultural tasks along the way.',
                'category'       => 'cultural',
                'duration_days'  => 1,
                'price_npr'      => 0,
                'points_reward'  => 320,
                'image_url'      => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'location_lat'   => 27.7172,
                'location_lng'   => 85.3240,
                'location_label' => 'Kathmandu, Nepal',
                'is_active'      => true,
                'is_featured'    => false,
                'is_free'        => true,
            ],
            [
                'slug'           => 'swayambhunath-sunrise-walk',
                'title'          => 'Swayambhunath Sunrise Walk',
                'description'    => 'Climb the 365 steps to the Monkey Temple at dawn and complete spiritual '
                    . 'tasks while watching the sun rise over the Kathmandu Valley.',
                'category'       => 'spiritual',
                'duration_days'  => 1,
                'price_npr'      => 0,
                'points_reward'  => 280,
                'image_url'      => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=800',
                'location_lat'   => 27.7149,
                'location_lng'   => 85.2905,
                'location_label' => 'Swayambhunath, Kathmandu',
                'is_active'      => true,
                'is_featured'    => false,
                'is_free'        => true,
            ],
        ];

        foreach ($ktmQuests as $data) {
            $pkg = Package::updateOrCreate(['slug' => $data['slug']], $data);
            $this->command->info("Kathmandu quest '{$pkg->title}' (ID {$pkg->id}) activated.");
        }

        $this->command->info('✓ Pilot quest seeder complete. Active packages: ' . Package::where('is_active', true)->count());
    }

    // ── Palungtar Explorer tasks (5 tasks, 340 pts total) ─────────────────────

    private function seedPalungtar1Tasks(int $packageId): void
    {
        $tasks = [
            ['type' => 'qr_scan',    'title' => 'Scan QR at Heritage Location (CP1)',              'points' => 40,  'sort_order' => 1,  'config' => ['qrCode' => 'PLT-Q1-CP01']],
            ['type' => 'quiz',       'title' => 'Heritage Story Quiz (CP1)',                        'points' => 40,  'sort_order' => 2,  'config' => ['questions' => [['question' => 'What makes Palungtar significant as a regional hub in Gorkha?', 'options' => ['It is the birthplace of Prithvi Narayan Shah', 'It is the headquarters of Gorkha district', 'It is the seat of Palungtar Municipality', 'It hosts the highest tea garden in Nepal'], 'correctIndex' => 2]]]],
            ['type' => 'photo_proof','title' => 'Photo with local business owner (CP2 — with consent)', 'points' => 60, 'sort_order' => 3,  'config' => []],
            ['type' => 'photo_proof','title' => 'Submit your community contribution note (CP3)',     'points' => 80,  'sort_order' => 4,  'config' => []],
            ['type' => 'code_entry', 'title' => 'Enter the sacred-site reflection code (CP4)',      'points' => 60,  'sort_order' => 5,  'config' => ['codeHash' => 'PLT-Q1-CP04', 'hint' => 'Read the posted sign respectfully at the temple entrance']],
            ['type' => 'photo_proof','title' => 'Team photo at the viewpoint (CP5)',                'points' => 60,  'sort_order' => 6,  'config' => []],
        ];

        foreach ($tasks as $t) {
            PackageTask::create(array_merge($t, ['package_id' => $packageId]));
        }
    }

    // ── Palungtar Heritage & Discovery Trail tasks (9 tasks, 550 pts total) ──

    private function seedPalungtar2Tasks(int $packageId): void
    {
        $tasks = [
            [
                'type' => 'gps_checkin', 'title' => 'Check in at Palungtar Bazar (CP1)',
                'points' => 50, 'sort_order' => 1,
                'config' => ['lat' => 28.0750, 'lng' => 84.7130, 'radiusMeters' => 100],
            ],
            [
                'type' => 'photo_proof', 'title' => 'Photograph the Daraundi River bridge (CP2)',
                'points' => 60, 'sort_order' => 2, 'config' => [],
            ],
            [
                'type' => 'qr_scan', 'title' => 'Scan QR at Palungtar Municipality Office (CP3)',
                'points' => 50, 'sort_order' => 3,
                'config' => ['qrCode' => 'PLT2-CP03-MUNI'],
            ],
            [
                'type' => 'quiz', 'title' => 'Gorkha & Palungtar History Quiz (CP4)',
                'points' => 60, 'sort_order' => 4,
                'config' => ['questions' => [
                    ['question' => 'Which legendary ruler was born in Gorkha district?', 'options' => ['Ram Shah', 'Prithvi Narayan Shah', 'Rana Bahadur Shah', 'Jung Bahadur Rana'], 'correctIndex' => 1],
                    ['question' => 'Palungtar is the headquarters of which municipality?', 'options' => ['Gorkha Municipality', 'Arughat Rural Municipality', 'Palungtar Municipality', 'Barpak Sulikot Rural Municipality'], 'correctIndex' => 2],
                    ['question' => 'Which mountain range is visible from the Palungtar hilltop viewpoint?', 'options' => ['Annapurna range', 'Langtang range', 'Ganesh Himal range', 'Dhaulagiri range'], 'correctIndex' => 2],
                ]],
            ],
            [
                'type' => 'gps_checkin', 'title' => 'Check in at Palungtar Health Post (CP5)',
                'points' => 50, 'sort_order' => 5,
                'config' => ['lat' => 28.0735, 'lng' => 84.7115, 'radiusMeters' => 80],
            ],
            [
                'type' => 'photo_proof', 'title' => 'Photograph a traditional agricultural terrace (CP6)',
                'points' => 70, 'sort_order' => 6, 'config' => [],
            ],
            [
                'type' => 'code_entry', 'title' => 'Enter the code at the Community Cultural Hall (CP7)',
                'points' => 60, 'sort_order' => 7,
                'config' => ['codeHash' => 'PLT2-CP07-HALL', 'hint' => 'Look for the posted notice board at the hall entrance'],
            ],
            [
                'type' => 'gps_checkin', 'title' => 'Reach the Palungtar Hilltop Viewpoint (CP8)',
                'points' => 70, 'sort_order' => 8,
                'config' => ['lat' => 28.0802, 'lng' => 84.7188, 'radiusMeters' => 150],
            ],
            [
                'type' => 'photo_proof', 'title' => 'Team photo with Ganesh Himal range in the background (CP9)',
                'points' => 80, 'sort_order' => 9, 'config' => [],
            ],
        ];

        foreach ($tasks as $t) {
            PackageTask::create(array_merge($t, ['package_id' => $packageId]));
        }
    }
}

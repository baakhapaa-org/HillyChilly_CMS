<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PilotQuestsSeeder extends Seeder
{
    public function run(): void
    {
        $quests = [
            // ── Palungtar Explorer — Internal Pilot ──────────────────────────
            [
                'package' => [
                    'title'          => 'Palungtar Explorer — Internal Pilot',
                    'slug'           => 'palungtar-explorer-internal-pilot',
                    'description'    => 'An internal pilot quest across 5 checkpoints in Palungtar, Gorkha. Scan QR codes, meet local businesses, contribute a community story, reflect at a sacred site, and close with a team photo at the viewpoint.',
                    'category'       => 'cultural',
                    'duration_days'  => 1,
                    'price_npr'      => 0,
                    'price_usd'      => 0,
                    'is_free'        => true,
                    'points_reward'  => 340,
                    'image_url'      => 'https://images.unsplash.com/photo-1598971639058-fab3c3109a73?w=800',
                    'location_lat'   => 28.075,
                    'location_lng'   => 84.713,
                    'location_label' => 'Palungtar, Gorkha',
                    'is_active'      => true,
                    'is_featured'    => false,
                ],
                'tasks' => [
                    [
                        'type'       => 'qr_scan',
                        'title'      => 'Scan QR at Heritage Location (CP1)',
                        'points'     => 40,
                        'sort_order' => 1,
                        'config'     => ['qrCode' => 'PLT-Q1-CP01'],
                    ],
                    [
                        'type'       => 'quiz',
                        'title'      => 'Heritage Story Quiz (CP1)',
                        'points'     => 40,
                        'sort_order' => 2,
                        'config'     => [
                            'questions' => [[
                                'question'     => 'What makes Palungtar significant as a regional hub in Gorkha?',
                                'options'      => [
                                    'It is the birthplace of Prithvi Narayan Shah',
                                    'It is the headquarters of Gorkha district',
                                    'It is the seat of Palungtar Municipality',
                                    'It hosts the highest tea garden in Nepal',
                                ],
                                'correctIndex' => 2,
                            ]],
                        ],
                    ],
                    [
                        'type'       => 'photo_proof',
                        'title'      => 'Photo with local business owner (CP2 — with consent)',
                        'points'     => 60,
                        'sort_order' => 3,
                        'config'     => [],
                    ],
                    [
                        'type'       => 'photo_proof',
                        'title'      => 'Submit your community contribution note (CP3)',
                        'points'     => 80,
                        'sort_order' => 4,
                        'config'     => [],
                    ],
                    [
                        'type'       => 'code_entry',
                        'title'      => 'Enter the sacred-site reflection code (CP4)',
                        'points'     => 60,
                        'sort_order' => 5,
                        'config'     => [
                            'codeHash' => 'PLT-Q1-CP04',
                            'hint'     => 'Read the posted sign respectfully at the temple entrance',
                        ],
                    ],
                    [
                        'type'       => 'photo_proof',
                        'title'      => 'Team photo at the viewpoint (CP5)',
                        'points'     => 60,
                        'sort_order' => 6,
                        'config'     => [],
                    ],
                ],
            ],

            // ── Baakhapaa Office Test ─────────────────────────────────────────
            [
                'package' => [
                    'title'          => 'Baakhapaa Office Test',
                    'slug'           => 'baakhapaa-office-test',
                    'description'    => 'A short internal test quest: GPS check-in at Baakhapaa Digital Market, capture the office monitor on camera, then GPS check-in back home at Pulchowk. Used to validate the full free-quest flow.',
                    'category'       => 'cultural',
                    'duration_days'  => 1,
                    'price_npr'      => 0,
                    'price_usd'      => 0,
                    'is_free'        => true,
                    'points_reward'  => 120,
                    'image_url'      => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800',
                    'location_lat'   => 27.7016967,
                    'location_lng'   => 85.2851966,
                    'location_label' => 'Bijaya Marg, Kathmandu',
                    'is_active'      => true,
                    'is_featured'    => false,
                ],
                'tasks' => [
                    [
                        'type'       => 'gps_checkin',
                        'title'      => 'Check in at Baakhapaa Digital Market',
                        'points'     => 50,
                        'sort_order' => 1,
                        'config'     => [
                            'lat'          => 27.7016967,
                            'lng'          => 85.2851966,
                            'radiusMeters' => 100,
                        ],
                    ],
                    [
                        'type'       => 'photo_proof',
                        'title'      => 'Capture the office monitor screen',
                        'points'     => 40,
                        'sort_order' => 2,
                        'config'     => [],
                    ],
                    [
                        'type'       => 'gps_checkin',
                        'title'      => 'Return home — check in at Pulchowk',
                        'points'     => 30,
                        'sort_order' => 3,
                        'config'     => [
                            'lat'          => 27.6829,
                            'lng'          => 85.3166,
                            'radiusMeters' => 150,
                        ],
                    ],
                ],
            ],

            // ── Fizzy Liquor World Cup Night ──────────────────────────────────
            [
                'package' => [
                    'title'          => 'Fizzy Liquor World Cup Night',
                    'slug'           => 'fizzy-liquor-world-cup-night',
                    'description'    => 'Head to Fizzy Liquor at Dhaugal, Patan to watch the World Cup final, snap the action on screen, then GPS check-in back home at Pulchowk. A fun test of the GPS + photo flow in a real social setting.',
                    'category'       => 'adventure',
                    'duration_days'  => 1,
                    'price_npr'      => 0,
                    'price_usd'      => 0,
                    'is_free'        => true,
                    'points_reward'  => 130,
                    'image_url'      => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800',
                    'location_lat'   => 27.6739,
                    'location_lng'   => 85.3239,
                    'location_label' => 'Dhaugal, Patan',
                    'is_active'      => true,
                    'is_featured'    => false,
                ],
                'tasks' => [
                    [
                        'type'       => 'gps_checkin',
                        'title'      => 'Check in at Fizzy Liquor, Dhaugal Patan',
                        'points'     => 50,
                        'sort_order' => 1,
                        'config'     => [
                            'lat'          => 27.6739,
                            'lng'          => 85.3239,
                            'radiusMeters' => 100,
                        ],
                    ],
                    [
                        'type'       => 'photo_proof',
                        'title'      => 'Snap the World Cup final on the big screen',
                        'points'     => 50,
                        'sort_order' => 2,
                        'config'     => [],
                    ],
                    [
                        'type'       => 'gps_checkin',
                        'title'      => 'Return home — check in at Pulchowk',
                        'points'     => 30,
                        'sort_order' => 3,
                        'config'     => [
                            'lat'          => 27.6829,
                            'lng'          => 85.3166,
                            'radiusMeters' => 150,
                        ],
                    ],
                ],
            ],
        ];

        foreach ($quests as $data) {
            // Skip if already seeded (idempotent)
            if (Package::where('slug', $data['package']['slug'])->exists()) {
                $this->command->info("Skipping '{$data['package']['title']}' — already exists.");
                continue;
            }

            $package = Package::create($data['package']);

            foreach ($data['tasks'] as $taskData) {
                PackageTask::create([
                    'package_id' => $package->id,
                    'type'       => $taskData['type'],
                    'title'      => $taskData['title'],
                    'points'     => $taskData['points'],
                    'sort_order' => $taskData['sort_order'],
                    'config'     => $taskData['config'] ?: null,
                ]);
            }

            $this->command->info("Seeded: {$package->title} (id={$package->id})");
        }
    }
}

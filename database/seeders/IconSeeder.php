<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    /**
     * Seed the icons table with all 30 default icons.
     */
    public function run(): void
    {
        $icons = [
            // Free tier icons (10)
            ['slug' => 'house',      'name' => 'House',        'emoji' => "\u{1F3E0}", 'svg_path' => 'icons/house.svg',        'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 1],
            ['slug' => 'tree',       'name' => 'Tree',         'emoji' => "\u{1F333}", 'svg_path' => 'icons/tree.svg',         'tier' => 'free', 'category' => 'nature',      'sort_order' => 2],
            ['slug' => 'key',        'name' => 'Key',          'emoji' => "\u{1F511}", 'svg_path' => 'icons/key.svg',          'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 3],
            ['slug' => 'building',   'name' => 'Building',     'emoji' => "\u{1F3E2}", 'svg_path' => 'icons/building.svg',     'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 4],
            ['slug' => 'pin',        'name' => 'Pin',          'emoji' => "\u{1F4CD}", 'svg_path' => 'icons/pin.svg',          'tier' => 'free', 'category' => 'general',     'sort_order' => 5],
            ['slug' => 'door',       'name' => 'Door',         'emoji' => "\u{1F6AA}", 'svg_path' => 'icons/door.svg',         'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 6],
            ['slug' => 'star',       'name' => 'Star',         'emoji' => "\u{2B50}",  'svg_path' => 'icons/star.svg',         'tier' => 'free', 'category' => 'general',     'sort_order' => 7],
            ['slug' => 'sunset',     'name' => 'Sunset',       'emoji' => "\u{1F305}", 'svg_path' => 'icons/sunset.svg',       'tier' => 'free', 'category' => 'nature',      'sort_order' => 8],
            ['slug' => 'cottage',    'name' => 'Cottage',      'emoji' => "\u{1F3E1}", 'svg_path' => 'icons/cottage.svg',      'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 9],
            ['slug' => 'diamond',    'name' => 'Diamond',      'emoji' => "\u{1F48E}", 'svg_path' => 'icons/diamond.svg',      'tier' => 'free', 'category' => 'general',     'sort_order' => 10],

            // Pro tier icons (20)
            ['slug' => 'window',       'name' => 'Window',       'emoji' => "\u{1FA9F}", 'svg_path' => 'icons/window.svg',       'tier' => 'pro', 'category' => 'real-estate',   'sort_order' => 11],
            ['slug' => 'furniture',    'name' => 'Furniture',    'emoji' => "\u{1F6CB}", 'svg_path' => 'icons/furniture.svg',    'tier' => 'pro', 'category' => 'real-estate',   'sort_order' => 12],
            ['slug' => 'beach',        'name' => 'Beach',        'emoji' => "\u{1F3D6}", 'svg_path' => 'icons/beach.svg',        'tier' => 'pro', 'category' => 'nature',        'sort_order' => 13],
            ['slug' => 'mountain',     'name' => 'Mountain',     'emoji' => "\u{1F3D4}", 'svg_path' => 'icons/mountain.svg',     'tier' => 'pro', 'category' => 'nature',        'sort_order' => 14],
            ['slug' => 'pool',         'name' => 'Pool',         'emoji' => "\u{1F3CA}", 'svg_path' => 'icons/pool.svg',         'tier' => 'pro', 'category' => 'amenities',     'sort_order' => 15],
            ['slug' => 'target',       'name' => 'Target',       'emoji' => "\u{1F3AF}", 'svg_path' => 'icons/target.svg',       'tier' => 'pro', 'category' => 'general',       'sort_order' => 16],
            ['slug' => 'bell',         'name' => 'Bell',         'emoji' => "\u{1F514}", 'svg_path' => 'icons/bell.svg',         'tier' => 'pro', 'category' => 'general',       'sort_order' => 17],
            ['slug' => 'flower',       'name' => 'Flower',       'emoji' => "\u{1F33A}", 'svg_path' => 'icons/flower.svg',       'tier' => 'pro', 'category' => 'nature',        'sort_order' => 18],
            ['slug' => 'castle',       'name' => 'Castle',       'emoji' => "\u{1F3F0}", 'svg_path' => 'icons/castle.svg',       'tier' => 'pro', 'category' => 'real-estate',   'sort_order' => 19],
            ['slug' => 'warehouse',    'name' => 'Warehouse',    'emoji' => "\u{1F3ED}", 'svg_path' => 'icons/warehouse.svg',    'tier' => 'pro', 'category' => 'real-estate',   'sort_order' => 20],
            ['slug' => 'cityscape',    'name' => 'Cityscape',    'emoji' => "\u{1F303}", 'svg_path' => 'icons/cityscape.svg',    'tier' => 'pro', 'category' => 'real-estate',   'sort_order' => 21],
            ['slug' => 'pine',         'name' => 'Pine',         'emoji' => "\u{1F332}", 'svg_path' => 'icons/pine.svg',         'tier' => 'pro', 'category' => 'nature',        'sort_order' => 22],
            ['slug' => 'palm',         'name' => 'Palm',         'emoji' => "\u{1F334}", 'svg_path' => 'icons/palm.svg',         'tier' => 'pro', 'category' => 'nature',        'sort_order' => 23],
            ['slug' => 'construction', 'name' => 'Construction', 'emoji' => "\u{1F3D7}", 'svg_path' => 'icons/construction.svg', 'tier' => 'pro', 'category' => 'real-estate',   'sort_order' => 24],
            ['slug' => 'paintbrush',   'name' => 'Paintbrush',   'emoji' => "\u{1F3A8}", 'svg_path' => 'icons/paintbrush.svg',   'tier' => 'pro', 'category' => 'general',       'sort_order' => 25],
            ['slug' => 'fire',         'name' => 'Fire',         'emoji' => "\u{1F525}", 'svg_path' => 'icons/fire.svg',         'tier' => 'pro', 'category' => 'general',       'sort_order' => 26],
            ['slug' => 'snowflake',    'name' => 'Snowflake',    'emoji' => "\u{2744}",  'svg_path' => 'icons/snowflake.svg',    'tier' => 'pro', 'category' => 'nature',        'sort_order' => 27],
            ['slug' => 'sun',          'name' => 'Sun',          'emoji' => "\u{2600}",  'svg_path' => 'icons/sun.svg',          'tier' => 'pro', 'category' => 'nature',        'sort_order' => 28],
            ['slug' => 'moon',         'name' => 'Moon',         'emoji' => "\u{1F319}", 'svg_path' => 'icons/moon.svg',         'tier' => 'pro', 'category' => 'nature',        'sort_order' => 29],
            ['slug' => 'tent',         'name' => 'Tent',         'emoji' => "\u{1F3AA}", 'svg_path' => 'icons/tent.svg',         'tier' => 'pro', 'category' => 'general',       'sort_order' => 30],
        ];

        foreach ($icons as $iconData) {
            Icon::updateOrCreate(
                ['slug' => $iconData['slug']],
                array_merge($iconData, ['is_active' => true]),
            );
        }

        $this->command->info('Seeded ' . count($icons) . ' icons (10 free, 20 pro).');
    }
}

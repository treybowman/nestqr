<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    /**
     * Seed the icons table with the full icon library.
     */
    public function run(): void
    {
        $icons = [
            // ============================================================
            // FREE TIER - Real Estate (15)
            // ============================================================
            ['slug' => 'house',          'name' => 'House',          'emoji' => "\u{1F3E0}", 'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 1],
            ['slug' => 'cottage',        'name' => 'Cottage',        'emoji' => "\u{1F3E1}", 'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 2],
            ['slug' => 'building',       'name' => 'Building',       'emoji' => "\u{1F3E2}", 'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 3],
            ['slug' => 'key',            'name' => 'Key',            'emoji' => "\u{1F511}", 'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 4],
            ['slug' => 'door',           'name' => 'Door',           'emoji' => "\u{1F6AA}", 'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 5],
            ['slug' => 'pin',            'name' => 'Location',       'emoji' => "\u{1F4CD}", 'tier' => 'free', 'category' => 'real-estate', 'sort_order' => 6],
            ['slug' => 'star',           'name' => 'Star',           'emoji' => "\u{2B50}",  'tier' => 'free', 'category' => 'general',     'sort_order' => 7],
            ['slug' => 'diamond',        'name' => 'Diamond',        'emoji' => "\u{1F48E}", 'tier' => 'free', 'category' => 'general',     'sort_order' => 8],
            ['slug' => 'tree',           'name' => 'Tree',           'emoji' => "\u{1F333}", 'tier' => 'free', 'category' => 'nature',      'sort_order' => 9],
            ['slug' => 'sunset',         'name' => 'Sunset',         'emoji' => "\u{1F305}", 'tier' => 'free', 'category' => 'nature',      'sort_order' => 10],
            ['slug' => 'heart',          'name' => 'Heart',          'emoji' => "\u{2764}",  'tier' => 'free', 'category' => 'general',     'sort_order' => 11],
            ['slug' => 'check',          'name' => 'Checkmark',      'emoji' => "\u{2705}",  'tier' => 'free', 'category' => 'general',     'sort_order' => 12],
            ['slug' => 'sun',            'name' => 'Sun',            'emoji' => "\u{2600}",  'tier' => 'free', 'category' => 'nature',      'sort_order' => 13],
            ['slug' => 'handshake',      'name' => 'Handshake',      'emoji' => "\u{1F91D}", 'tier' => 'free', 'category' => 'general',     'sort_order' => 14],
            ['slug' => 'clipboard',      'name' => 'Clipboard',      'emoji' => "\u{1F4CB}", 'tier' => 'free', 'category' => 'general',     'sort_order' => 15],

            // ============================================================
            // PRO TIER - Real Estate & Property Types (25)
            // ============================================================
            ['slug' => 'castle',         'name' => 'Castle',         'emoji' => "\u{1F3F0}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 100],
            ['slug' => 'warehouse',      'name' => 'Warehouse',      'emoji' => "\u{1F3ED}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 101],
            ['slug' => 'construction',   'name' => 'Construction',   'emoji' => "\u{1F3D7}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 102],
            ['slug' => 'cityscape',      'name' => 'City View',      'emoji' => "\u{1F303}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 103],
            ['slug' => 'skyline',        'name' => 'Skyline',        'emoji' => "\u{1F3D9}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 104],
            ['slug' => 'office',         'name' => 'Office',         'emoji' => "\u{1F3E2}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 105],
            ['slug' => 'hotel',          'name' => 'Hotel',          'emoji' => "\u{1F3E8}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 106],
            ['slug' => 'store',          'name' => 'Store',          'emoji' => "\u{1F3EA}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 107],
            ['slug' => 'school',         'name' => 'School',         'emoji' => "\u{1F3EB}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 108],
            ['slug' => 'hospital',       'name' => 'Hospital',       'emoji' => "\u{1F3E5}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 109],
            ['slug' => 'church',         'name' => 'Church',         'emoji' => "\u{26EA}",  'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 110],
            ['slug' => 'bank',           'name' => 'Bank',           'emoji' => "\u{1F3E6}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 111],
            ['slug' => 'post-office',    'name' => 'Post Office',    'emoji' => "\u{1F3E4}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 112],
            ['slug' => 'stadium',        'name' => 'Stadium',        'emoji' => "\u{1F3DF}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 113],
            ['slug' => 'window',         'name' => 'Window',         'emoji' => "\u{1FA9F}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 114],
            ['slug' => 'brick',          'name' => 'Brick',          'emoji' => "\u{1F9F1}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 115],
            ['slug' => 'fence',          'name' => 'Fence',          'emoji' => "\u{1F3DA}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 116],
            ['slug' => 'old-key',        'name' => 'Old Key',        'emoji' => "\u{1F5DD}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 117],
            ['slug' => 'mailbox',        'name' => 'Mailbox',        'emoji' => "\u{1F4EC}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 118],
            ['slug' => 'hammer',         'name' => 'Hammer',         'emoji' => "\u{1F528}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 119],
            ['slug' => 'wrench',         'name' => 'Wrench',         'emoji' => "\u{1F527}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 120],
            ['slug' => 'lock',           'name' => 'Lock',           'emoji' => "\u{1F512}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 121],
            ['slug' => 'unlock',         'name' => 'Unlock',         'emoji' => "\u{1F513}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 122],
            ['slug' => 'house-garden',   'name' => 'House & Garden', 'emoji' => "\u{1F3E1}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 123],
            ['slug' => 'classical',      'name' => 'Classical',      'emoji' => "\u{1F3DB}", 'tier' => 'pro', 'category' => 'real-estate',  'sort_order' => 124],

            // ============================================================
            // PRO TIER - Interior & Amenities (20)
            // ============================================================
            ['slug' => 'furniture',      'name' => 'Couch',          'emoji' => "\u{1F6CB}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 200],
            ['slug' => 'bathtub',        'name' => 'Bathtub',        'emoji' => "\u{1F6C1}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 201],
            ['slug' => 'bed',            'name' => 'Bed',            'emoji' => "\u{1F6CF}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 202],
            ['slug' => 'lamp',           'name' => 'Lamp',           'emoji' => "\u{1F4A1}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 203],
            ['slug' => 'pool',           'name' => 'Pool',           'emoji' => "\u{1F3CA}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 204],
            ['slug' => 'kitchen',        'name' => 'Kitchen',        'emoji' => "\u{1F373}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 205],
            ['slug' => 'wine',           'name' => 'Wine Cellar',    'emoji' => "\u{1F377}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 206],
            ['slug' => 'coffee',         'name' => 'Coffee',         'emoji' => "\u{2615}",  'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 207],
            ['slug' => 'tv',             'name' => 'TV',             'emoji' => "\u{1F4FA}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 208],
            ['slug' => 'shower',         'name' => 'Shower',         'emoji' => "\u{1F6BF}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 209],
            ['slug' => 'parking',        'name' => 'Parking',        'emoji' => "\u{1F17F}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 210],
            ['slug' => 'car',            'name' => 'Garage',         'emoji' => "\u{1F697}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 211],
            ['slug' => 'elevator',       'name' => 'Elevator',       'emoji' => "\u{1F6D7}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 212],
            ['slug' => 'fire-place',     'name' => 'Fireplace',      'emoji' => "\u{1F525}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 213],
            ['slug' => 'ac',             'name' => 'Air Conditioning','emoji' => "\u{2744}",  'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 214],
            ['slug' => 'gym',            'name' => 'Gym',            'emoji' => "\u{1F3CB}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 215],
            ['slug' => 'tennis',         'name' => 'Tennis',         'emoji' => "\u{1F3BE}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 216],
            ['slug' => 'golf',           'name' => 'Golf',           'emoji' => "\u{26F3}",  'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 217],
            ['slug' => 'dog',            'name' => 'Pet Friendly',   'emoji' => "\u{1F415}", 'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 218],
            ['slug' => 'wheelchair',     'name' => 'Accessible',     'emoji' => "\u{267F}",  'tier' => 'pro', 'category' => 'amenities',    'sort_order' => 219],

            // ============================================================
            // PRO TIER - Nature & Environment (20)
            // ============================================================
            ['slug' => 'beach',          'name' => 'Beach',          'emoji' => "\u{1F3D6}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 300],
            ['slug' => 'mountain',       'name' => 'Mountain',       'emoji' => "\u{1F3D4}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 301],
            ['slug' => 'palm',           'name' => 'Palm Tree',      'emoji' => "\u{1F334}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 302],
            ['slug' => 'pine',           'name' => 'Pine Tree',      'emoji' => "\u{1F332}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 303],
            ['slug' => 'flower',         'name' => 'Flower',         'emoji' => "\u{1F33A}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 304],
            ['slug' => 'tulip',          'name' => 'Tulip',          'emoji' => "\u{1F337}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 305],
            ['slug' => 'cherry-blossom', 'name' => 'Cherry Blossom', 'emoji' => "\u{1F338}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 306],
            ['slug' => 'sunflower',      'name' => 'Sunflower',      'emoji' => "\u{1F33B}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 307],
            ['slug' => 'rose',           'name' => 'Rose',           'emoji' => "\u{1F339}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 308],
            ['slug' => 'cactus',         'name' => 'Cactus',         'emoji' => "\u{1F335}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 309],
            ['slug' => 'leaf',           'name' => 'Leaf',           'emoji' => "\u{1F343}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 310],
            ['slug' => 'maple-leaf',     'name' => 'Maple Leaf',     'emoji' => "\u{1F341}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 311],
            ['slug' => 'herb',           'name' => 'Herb',           'emoji' => "\u{1F33F}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 312],
            ['slug' => 'rainbow',        'name' => 'Rainbow',        'emoji' => "\u{1F308}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 313],
            ['slug' => 'water',          'name' => 'Water',          'emoji' => "\u{1F4A7}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 314],
            ['slug' => 'wave',           'name' => 'Wave',           'emoji' => "\u{1F30A}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 315],
            ['slug' => 'moon',           'name' => 'Moon',           'emoji' => "\u{1F319}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 316],
            ['slug' => 'snowflake',      'name' => 'Snowflake',      'emoji' => "\u{2744}",  'tier' => 'pro', 'category' => 'nature',       'sort_order' => 317],
            ['slug' => 'volcano',        'name' => 'Volcano',        'emoji' => "\u{1F30B}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 318],
            ['slug' => 'desert',         'name' => 'Desert',         'emoji' => "\u{1F3DC}", 'tier' => 'pro', 'category' => 'nature',       'sort_order' => 319],

            // ============================================================
            // PRO TIER - Lifestyle & Community (20)
            // ============================================================
            ['slug' => 'family',         'name' => 'Family',         'emoji' => "\u{1F46A}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 400],
            ['slug' => 'baby',           'name' => 'Baby',           'emoji' => "\u{1F476}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 401],
            ['slug' => 'graduation',     'name' => 'School Area',    'emoji' => "\u{1F393}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 402],
            ['slug' => 'shopping',       'name' => 'Shopping',       'emoji' => "\u{1F6CD}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 403],
            ['slug' => 'restaurant',     'name' => 'Dining',         'emoji' => "\u{1F37D}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 404],
            ['slug' => 'airplane',       'name' => 'Airport Near',   'emoji' => "\u{2708}",  'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 405],
            ['slug' => 'train',          'name' => 'Transit',        'emoji' => "\u{1F686}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 406],
            ['slug' => 'bike',           'name' => 'Bike Friendly',  'emoji' => "\u{1F6B2}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 407],
            ['slug' => 'walking',        'name' => 'Walkable',       'emoji' => "\u{1F6B6}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 408],
            ['slug' => 'playground',     'name' => 'Playground',     'emoji' => "\u{1F3A0}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 409],
            ['slug' => 'park',           'name' => 'Park Nearby',    'emoji' => "\u{1F3DE}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 410],
            ['slug' => 'fishing',        'name' => 'Fishing',        'emoji' => "\u{1F3A3}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 411],
            ['slug' => 'camping',        'name' => 'Camping',        'emoji' => "\u{1F3D5}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 412],
            ['slug' => 'ski',            'name' => 'Ski',            'emoji' => "\u{26F7}",  'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 413],
            ['slug' => 'boat',           'name' => 'Waterfront',     'emoji' => "\u{26F5}",  'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 414],
            ['slug' => 'church-2',       'name' => 'Worship',        'emoji' => "\u{1F54C}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 415],
            ['slug' => 'hospital-2',     'name' => 'Medical',        'emoji' => "\u{1F6A8}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 416],
            ['slug' => 'shield',         'name' => 'Safe Area',      'emoji' => "\u{1F6E1}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 417],
            ['slug' => 'globe',          'name' => 'International',  'emoji' => "\u{1F30E}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 418],
            ['slug' => 'night-view',     'name' => 'Night View',     'emoji' => "\u{1F306}", 'tier' => 'pro', 'category' => 'lifestyle',    'sort_order' => 419],

            // ============================================================
            // PRO TIER - Marketing & Business (20)
            // ============================================================
            ['slug' => 'trophy',         'name' => 'Award Winning',  'emoji' => "\u{1F3C6}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 500],
            ['slug' => 'medal',          'name' => 'Top Rated',      'emoji' => "\u{1F3C5}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 501],
            ['slug' => 'ribbon',         'name' => 'Featured',       'emoji' => "\u{1F397}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 502],
            ['slug' => 'crown',          'name' => 'Premium',        'emoji' => "\u{1F451}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 503],
            ['slug' => 'sparkles',       'name' => 'New Listing',    'emoji' => "\u{2728}",  'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 504],
            ['slug' => 'fire-2',         'name' => 'Hot Deal',       'emoji' => "\u{1F525}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 505],
            ['slug' => 'lightning',      'name' => 'Quick Sale',     'emoji' => "\u{26A1}",  'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 506],
            ['slug' => 'rocket',         'name' => 'Just Listed',    'emoji' => "\u{1F680}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 507],
            ['slug' => 'megaphone',      'name' => 'Open House',     'emoji' => "\u{1F4E3}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 508],
            ['slug' => 'target',         'name' => 'Target Area',    'emoji' => "\u{1F3AF}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 509],
            ['slug' => 'bell',           'name' => 'New Price',      'emoji' => "\u{1F514}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 510],
            ['slug' => 'money',          'name' => 'Great Value',    'emoji' => "\u{1F4B0}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 511],
            ['slug' => 'chart',          'name' => 'Trending',       'emoji' => "\u{1F4C8}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 512],
            ['slug' => 'calendar',       'name' => 'Open House',     'emoji' => "\u{1F4C5}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 513],
            ['slug' => 'phone',          'name' => 'Call Agent',     'emoji' => "\u{1F4DE}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 514],
            ['slug' => 'email',          'name' => 'Contact',        'emoji' => "\u{2709}",  'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 515],
            ['slug' => 'camera',         'name' => 'Virtual Tour',   'emoji' => "\u{1F4F7}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 516],
            ['slug' => 'video',          'name' => 'Video Tour',     'emoji' => "\u{1F3AC}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 517],
            ['slug' => 'paintbrush',     'name' => 'Renovated',      'emoji' => "\u{1F3A8}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 518],
            ['slug' => 'gift',           'name' => 'Move-in Ready',  'emoji' => "\u{1F381}", 'tier' => 'pro', 'category' => 'marketing',    'sort_order' => 519],

            // ============================================================
            // PRO TIER - Seasonal & Special (10)
            // ============================================================
            ['slug' => 'christmas',      'name' => 'Holiday',        'emoji' => "\u{1F384}", 'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 600],
            ['slug' => 'fireworks',      'name' => 'Celebration',    'emoji' => "\u{1F386}", 'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 601],
            ['slug' => 'jack-o-lantern', 'name' => 'Fall Special',   'emoji' => "\u{1F383}", 'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 602],
            ['slug' => 'four-leaf',      'name' => 'Lucky Find',     'emoji' => "\u{1F340}", 'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 603],
            ['slug' => 'butterfly',      'name' => 'Spring Listing', 'emoji' => "\u{1F98B}", 'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 604],
            ['slug' => 'snowman',        'name' => 'Winter Deal',    'emoji' => "\u{26C4}",  'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 605],
            ['slug' => 'umbrella',       'name' => 'Rainy Season',   'emoji' => "\u{2602}",  'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 606],
            ['slug' => 'compass',        'name' => 'Explorer',       'emoji' => "\u{1F9ED}", 'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 607],
            ['slug' => 'anchor',         'name' => 'Waterfront',     'emoji' => "\u{2693}",  'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 608],
            ['slug' => 'flag',           'name' => 'Exclusive',      'emoji' => "\u{1F3F4}", 'tier' => 'pro', 'category' => 'seasonal',     'sort_order' => 609],
        ];

        $freeCount = 0;
        $proCount = 0;

        foreach ($icons as $iconData) {
            Icon::updateOrCreate(
                ['slug' => $iconData['slug']],
                array_merge($iconData, ['svg_path' => '', 'is_active' => true]),
            );

            if ($iconData['tier'] === 'free') {
                $freeCount++;
            } else {
                $proCount++;
            }
        }

        $total = $freeCount + $proCount;
        $this->command->info("Seeded {$total} icons ({$freeCount} free, {$proCount} pro).");
    }
}

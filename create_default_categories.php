<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;
use App\Database\Models\Type;

try {
    echo "Creating default parent categories...\n";
    
    // Get the first type (or create one if none exists)
    $type = Type::first();
    if (!$type) {
        echo "❌ No types found. Please create types first.\n";
        exit(1);
    }
    
    echo "Using type: {$type->name} (ID: {$type->id})\n\n";
    
    // Default parent categories for a furniture store
    $defaultCategories = [
        [
            'name' => 'Living Room',
            'slug' => 'living-room',
            'details' => 'Comfortable and stylish furniture for your living room',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Living Room Furniture - Harvin Chairs',
            'meta_description' => 'Discover beautiful living room furniture including sofas, chairs, tables and more.',
            'meta_keywords' => 'living room, furniture, sofa, chairs, tables'
        ],
        [
            'name' => 'Bedroom',
            'slug' => 'bedroom',
            'details' => 'Elegant bedroom furniture for a peaceful sleep',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Bedroom Furniture - Harvin Chairs',
            'meta_description' => 'Complete your bedroom with our collection of beds, wardrobes, and bedside tables.',
            'meta_keywords' => 'bedroom, furniture, beds, wardrobes, bedside tables'
        ],
        [
            'name' => 'Dining Room',
            'slug' => 'dining-room',
            'details' => 'Dining furniture for memorable family meals',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Dining Room Furniture - Harvin Chairs',
            'meta_description' => 'Dining tables, chairs, and sets for your perfect dining experience.',
            'meta_keywords' => 'dining room, furniture, dining table, chairs, dining set'
        ],
        [
            'name' => 'Office',
            'slug' => 'office',
            'details' => 'Professional office furniture for productivity',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Office Furniture - Harvin Chairs',
            'meta_description' => 'Office chairs, desks, and furniture for your workspace.',
            'meta_keywords' => 'office, furniture, office chairs, desks, workspace'
        ],
        [
            'name' => 'Outdoor',
            'slug' => 'outdoor',
            'details' => 'Durable outdoor furniture for your garden and patio',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Outdoor Furniture - Harvin Chairs',
            'meta_description' => 'Garden furniture, patio sets, and outdoor seating solutions.',
            'meta_keywords' => 'outdoor, furniture, garden, patio, outdoor seating'
        ],
        [
            'name' => 'Kids & Youth',
            'slug' => 'kids-youth',
            'details' => 'Fun and safe furniture for children and teenagers',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Kids & Youth Furniture - Harvin Chairs',
            'meta_description' => 'Children\'s furniture, study tables, and youth bedroom sets.',
            'meta_keywords' => 'kids, youth, furniture, children, study tables'
        ]
    ];
    
    $createdCount = 0;
    
    foreach ($defaultCategories as $categoryData) {
        // Check if category already exists
        $existingCategory = Category::where('slug', $categoryData['slug'])->first();
        
        if ($existingCategory) {
            echo "⚠️  Category '{$categoryData['name']}' already exists (ID: {$existingCategory->id})\n";
            continue;
        }
        
        // Create the category
        $category = Category::create($categoryData);
        echo "✅ Created category: {$category->name} (ID: {$category->id})\n";
        $createdCount++;
    }
    
    echo "\n📊 Summary:\n";
    echo "- Total categories processed: " . count($defaultCategories) . "\n";
    echo "- New categories created: {$createdCount}\n";
    echo "- Existing categories skipped: " . (count($defaultCategories) - $createdCount) . "\n";
    
    if ($createdCount > 0) {
        echo "\n🎉 Default parent categories have been successfully created!\n";
        echo "You can now create sub-categories under these parent categories.\n";
    } else {
        echo "\nℹ️  All default categories already exist in the database.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

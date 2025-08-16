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
    
    // Default parent categories for a furniture store with specific furniture types
    $defaultCategories = [
        [
            'name' => 'Beds',
            'slug' => 'beds',
            'details' => 'Comfortable beds for all room sizes and styles',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Beds - Harvin Chairs',
            'meta_description' => 'Single beds, double beds, queen beds, king beds, and bunk beds for every bedroom.',
            'meta_keywords' => 'beds, single bed, double bed, queen bed, king bed, bunk bed, bedroom furniture'
        ],
        [
            'name' => 'Sofas & Couches',
            'slug' => 'sofas-couches',
            'details' => 'Stylish sofas and couches for your living room',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Sofas & Couches - Harvin Chairs',
            'meta_description' => '2-seater, 3-seater, L-shaped sofas, and comfortable couches for your living space.',
            'meta_keywords' => 'sofas, couches, 2-seater, 3-seater, L-shaped sofa, living room furniture'
        ],
        [
            'name' => 'Dining Tables',
            'slug' => 'dining-tables',
            'details' => 'Elegant dining tables for family meals and gatherings',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Dining Tables - Harvin Chairs',
            'meta_description' => '4-seater, 6-seater, 8-seater dining tables in various styles and materials.',
            'meta_keywords' => 'dining tables, 4-seater, 6-seater, 8-seater, dining room furniture'
        ],
        [
            'name' => 'Chairs',
            'slug' => 'chairs',
            'details' => 'Comfortable chairs for dining, living room, and office',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Chairs - Harvin Chairs',
            'meta_description' => 'Dining chairs, accent chairs, office chairs, and comfortable seating solutions.',
            'meta_keywords' => 'chairs, dining chairs, accent chairs, office chairs, seating'
        ],
        [
            'name' => 'Wardrobes',
            'slug' => 'wardrobes',
            'details' => 'Spacious wardrobes for organized storage',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Wardrobes - Harvin Chairs',
            'meta_description' => 'Sliding wardrobes, hinged wardrobes, and walk-in closet solutions.',
            'meta_keywords' => 'wardrobes, sliding wardrobes, hinged wardrobes, closet, storage'
        ],
        [
            'name' => 'Tables',
            'slug' => 'tables',
            'details' => 'Coffee tables, side tables, and study tables',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Tables - Harvin Chairs',
            'meta_description' => 'Coffee tables, side tables, study tables, and console tables for every room.',
            'meta_keywords' => 'tables, coffee tables, side tables, study tables, console tables'
        ],
        [
            'name' => 'Office Furniture',
            'slug' => 'office-furniture',
            'details' => 'Professional office desks, chairs, and storage solutions',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Office Furniture - Harvin Chairs',
            'meta_description' => 'Office desks, ergonomic chairs, filing cabinets, and workspace solutions.',
            'meta_keywords' => 'office furniture, office desks, ergonomic chairs, filing cabinets, workspace'
        ],
        [
            'name' => 'Outdoor Furniture',
            'slug' => 'outdoor-furniture',
            'details' => 'Durable outdoor furniture for garden and patio',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Outdoor Furniture - Harvin Chairs',
            'meta_description' => 'Garden chairs, patio tables, outdoor sofas, and weather-resistant furniture.',
            'meta_keywords' => 'outdoor furniture, garden chairs, patio tables, outdoor sofas, weather-resistant'
        ],
        [
            'name' => 'Kids Furniture',
            'slug' => 'kids-furniture',
            'details' => 'Safe and fun furniture for children',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Kids Furniture - Harvin Chairs',
            'meta_description' => 'Children\'s beds, study tables, chairs, and storage solutions for kids.',
            'meta_keywords' => 'kids furniture, children\'s beds, study tables, kids chairs, children\'s storage'
        ],
        [
            'name' => 'Storage Solutions',
            'slug' => 'storage-solutions',
            'details' => 'Chests of drawers, cabinets, and storage units',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Storage Solutions - Harvin Chairs',
            'meta_description' => 'Chests of drawers, cabinets, bookshelves, and storage units for organized living.',
            'meta_keywords' => 'storage solutions, chests of drawers, cabinets, bookshelves, storage units'
        ],
        [
            'name' => 'Mattresses',
            'slug' => 'mattresses',
            'details' => 'Comfortable mattresses for all bed sizes',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Mattresses - Harvin Chairs',
            'meta_description' => 'Single, double, queen, and king size mattresses in various comfort levels.',
            'meta_keywords' => 'mattresses, single mattress, double mattress, queen mattress, king mattress'
        ],
        [
            'name' => 'Accessories',
            'slug' => 'accessories',
            'details' => 'Cushions, throws, and furniture accessories',
            'type_id' => $type->id,
            'parent' => null,
            'language' => 'en',
            'enabled' => 1,
            'is_home' => 1,
            'is_showcase' => 1,
            'cgst_rate' => 12,
            'sgst_rate' => 12,
            'meta_title' => 'Furniture Accessories - Harvin Chairs',
            'meta_description' => 'Cushions, throws, table lamps, and decorative accessories for your furniture.',
            'meta_keywords' => 'furniture accessories, cushions, throws, table lamps, decorative items'
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

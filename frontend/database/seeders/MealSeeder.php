<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meals = [
            // ================== SARAPAN ==================
            [
                'name' => 'High-Protein Oatmeal Bowl',
                'description' => 'Oatmeal dengan whey protein dan beri, sangat baik untuk energi sebelum/sesudah latihan.',
                'calories' => 350,
                'protein_g' => 30,
                'carbs_g' => 45,
                'fat_g' => 5,
                'meal_type' => 'Sarapan',
                'image_url' => 'https://images.unsplash.com/photo-1517673400267-0251440c45dc?w=600&fit=crop',
                'dietary_tags' => ['Vegetarian', 'Normal', 'Halal'],
                'medical_tags' => ['Low Sodium', 'Kolesterol', 'Alergi Kacang', 'Alergi Seafood'],
                'target_workout' => ['strength']
            ],
            [
                'name' => 'Omelet Putih Telur Sayuran',
                'description' => 'Sarapan rendah lemak jenuh dan bebas kolesterol, sangat padat protein.',
                'calories' => 220,
                'protein_g' => 25,
                'carbs_g' => 10,
                'fat_g' => 5,
                'meal_type' => 'Sarapan',
                'image_url' => 'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=600&fit=crop',
                'dietary_tags' => ['Normal', 'Vegetarian', 'Halal', 'Keto'],
                'medical_tags' => ['Kolesterol', 'Diabetes', 'Hipertensi', 'Alergi Kacang', 'Alergi Seafood', 'Alergi Susu (Laktosa)', 'Alergi Gluten', 'Asam Urat', 'Maag/GERD'],
                'target_workout' => ['strength']
            ],
            [
                'name' => 'Smoothie Bowl Vegan',
                'description' => 'Mangkuk smoothie segar dari pisang, bayam, dan susu almond. Kaya vitamin.',
                'calories' => 310,
                'protein_g' => 10,
                'carbs_g' => 55,
                'fat_g' => 8,
                'meal_type' => 'Sarapan',
                'image_url' => 'https://images.unsplash.com/photo-1494597564530-871f2b93ac55?w=600&fit=crop',
                'dietary_tags' => ['Vegetarian', 'Vegan', 'Normal', 'Halal'],
                'medical_tags' => ['Hipertensi', 'Penyakit Jantung', 'Kolesterol', 'Alergi Telur', 'Alergi Susu (Laktosa)', 'Alergi Seafood'],
                'target_workout' => ['cardio', 'yoga']
            ],
            [
                'name' => 'Roti Gandum Alpukat (Avocado Toast)',
                'description' => 'Karbohidrat kompleks dengan lemak sehat omega-3 dari alpukat.',
                'calories' => 280,
                'protein_g' => 8,
                'carbs_g' => 35,
                'fat_g' => 15,
                'meal_type' => 'Sarapan',
                'image_url' => 'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?w=600&fit=crop',
                'dietary_tags' => ['Vegetarian', 'Vegan', 'Normal', 'Halal'],
                'medical_tags' => ['Diabetes', 'Kolesterol', 'Alergi Susu (Laktosa)', 'Alergi Telur', 'Alergi Seafood'],
                'target_workout' => ['cardio', 'strength']
            ],
            [
                'name' => 'Pancake Bebas Gluten',
                'description' => 'Pancake berbahan tepung almond dan pisang, bebas perekat gluten.',
                'calories' => 350,
                'protein_g' => 12,
                'carbs_g' => 40,
                'fat_g' => 16,
                'meal_type' => 'Sarapan',
                'image_url' => 'https://images.unsplash.com/photo-1528207776546-384cb1119b27?w=600&fit=crop',
                'dietary_tags' => ['Vegetarian', 'Normal', 'Halal', 'Gluten-Free'],
                'medical_tags' => ['Alergi Gluten', 'Alergi Seafood', 'Maag/GERD'],
                'target_workout' => ['strength', 'hiit']
            ],
            [
                'name' => 'Bubur Ayam Oat (Savory Oatmeal)',
                'description' => 'Bubur dari rolled oat dengan suwiran ayam rebus, nyaman di perut.',
                'calories' => 320,
                'protein_g' => 22,
                'carbs_g' => 45,
                'fat_g' => 6,
                'meal_type' => 'Sarapan',
                'image_url' => 'https://images.unsplash.com/photo-1611143660505-648b2cc16315?w=600&fit=crop',
                'dietary_tags' => ['Normal', 'Halal'],
                'medical_tags' => ['Maag/GERD', 'Diabetes', 'Kolesterol', 'Low Sodium', 'Alergi Seafood', 'Alergi Susu (Laktosa)', 'Alergi Kacang'],
                'target_workout' => ['cardio']
            ],

            // ================== MAKAN SIANG ==================
            [
                'name' => 'Dada Ayam Panggang & Nasi Merah',
                'description' => 'Kombinasi klasik penambah massa otot. Tinggi protein, rendah lemak.',
                'calories' => 450,
                'protein_g' => 45,
                'carbs_g' => 50,
                'fat_g' => 8,
                'meal_type' => 'Makan Siang',
                'image_url' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?w=600&fit=crop',
                'dietary_tags' => ['Normal', 'Halal', 'Gluten-Free'],
                'medical_tags' => ['Diabetes', 'Penyakit Jantung', 'Kolesterol', 'Alergi Seafood', 'Alergi Susu (Laktosa)', 'Alergi Kacang'],
                'target_workout' => ['strength', 'hiit']
            ],
            [
                'name' => 'Steak Tempe & Quinoa',
                'description' => 'Alternatif protein nabati yang lezat, disajikan bersama quinoa organik.',
                'calories' => 420,
                'protein_g' => 24,
                'carbs_g' => 55,
                'fat_g' => 14,
                'meal_type' => 'Makan Siang',
                'image_url' => 'https://images.unsplash.com/photo-1582515073490-39981397c445?w=600&fit=crop',
                'dietary_tags' => ['Vegetarian', 'Vegan', 'Normal', 'Halal'],
                'medical_tags' => ['Hipertensi', 'Kolesterol', 'Penyakit Jantung', 'Alergi Seafood', 'Alergi Susu (Laktosa)', 'Alergi Telur'],
                'target_workout' => ['yoga', 'cardio']
            ],
            [
                'name' => 'Salmon Teriyaki Gluten-Free',
                'description' => 'Salmon panggang kaya omega-3 dengan saus teriyaki bebas gluten.',
                'calories' => 520,
                'protein_g' => 38,
                'carbs_g' => 25,
                'fat_g' => 28,
                'meal_type' => 'Makan Siang',
                'image_url' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=600&fit=crop',
                'dietary_tags' => ['Halal', 'Normal', 'Pescatarian', 'Gluten-Free'],
                'medical_tags' => ['Alergi Gluten', 'Hipertensi', 'Kolesterol', 'Alergi Kacang', 'Alergi Telur', 'Alergi Susu (Laktosa)'],
                'target_workout' => ['strength', 'rest']
            ],
            [
                'name' => 'Kari Ayam Tanpa Santan (Keto)',
                'description' => 'Kari ayam kaya rempah menggunakan susu almond/fiber creme, ramah Keto.',
                'calories' => 480,
                'protein_g' => 40,
                'carbs_g' => 8,
                'fat_g' => 32,
                'meal_type' => 'Makan Siang',
                'image_url' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=600&fit=crop',
                'dietary_tags' => ['Keto', 'Halal', 'Normal', 'Gluten-Free'],
                'medical_tags' => ['Diabetes', 'Alergi Seafood', 'Alergi Susu (Laktosa)', 'Alergi Telur'],
                'target_workout' => ['strength']
            ],
            [
                'name' => 'Salad Tahu Goreng Air Fryer',
                'description' => 'Tahu renyah tanpa minyak dipadukan dengan sayuran segar dan saus kacang.',
                'calories' => 310,
                'protein_g' => 18,
                'carbs_g' => 20,
                'fat_g' => 16,
                'meal_type' => 'Makan Siang',
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&fit=crop',
                'dietary_tags' => ['Vegan', 'Vegetarian', 'Normal', 'Halal'],
                'medical_tags' => ['Kolesterol', 'Hipertensi', 'Penyakit Jantung', 'Diabetes', 'Alergi Seafood', 'Alergi Telur'],
                'target_workout' => ['cardio']
            ],
            [
                'name' => 'Sop Ikan Kakap Bening',
                'description' => 'Sop kaldu ikan segar dengan tomat hijau, hangat dan aman untuk lambung.',
                'calories' => 290,
                'protein_g' => 32,
                'carbs_g' => 15,
                'fat_g' => 8,
                'meal_type' => 'Makan Siang',
                'image_url' => 'https://images.unsplash.com/photo-1512838327318-62ff7faef0fb?w=600&fit=crop',
                'dietary_tags' => ['Halal', 'Normal', 'Pescatarian'],
                'medical_tags' => ['Maag/GERD', 'Diabetes', 'Penyakit Jantung', 'Low Sodium', 'Alergi Kacang', 'Alergi Telur', 'Alergi Susu (Laktosa)'],
                'target_workout' => ['cardio', 'rest']
            ],
            [
                'name' => 'Mie Shirataki Goreng Daging Sapi',
                'description' => 'Mie nol kalori dimasak dengan irisan daging sapi tanpa lemak, cocok untuk penderita diabetes.',
                'calories' => 320,
                'protein_g' => 28,
                'carbs_g' => 12,
                'fat_g' => 16,
                'meal_type' => 'Makan Siang',
                'image_url' => 'https://images.unsplash.com/photo-1552611052-33e04de081de?w=600&fit=crop',
                'dietary_tags' => ['Normal', 'Halal', 'Keto'],
                'medical_tags' => ['Diabetes', 'Alergi Gluten', 'Alergi Seafood', 'Alergi Kacang', 'Asam Urat'],
                'target_workout' => ['hiit', 'strength']
            ],

            // ================== MAKAN MALAM ==================
            [
                'name' => 'Ayam Rebus Sayur Kukus',
                'description' => 'Menu paling bersih (clean eat) tanpa minyak tambahan, mudah dicerna sebelum tidur.',
                'calories' => 250,
                'protein_g' => 35,
                'carbs_g' => 15,
                'fat_g' => 4,
                'meal_type' => 'Makan Malam',
                'image_url' => 'https://images.unsplash.com/photo-1598514982205-f36b96d1e8d4?w=600&fit=crop',
                'dietary_tags' => ['Normal', 'Halal', 'Gluten-Free'],
                'medical_tags' => ['Maag/GERD', 'Kolesterol', 'Diabetes', 'Penyakit Jantung', 'Hipertensi', 'Alergi Kacang', 'Alergi Seafood', 'Alergi Susu (Laktosa)'],
                'target_workout' => ['rest']
            ],
            [
                'name' => 'Tumis Udang Bawang Putih & Asparagus',
                'description' => 'Udang tinggi protein dimasak cepat dengan olive oil dan asparagus renyah.',
                'calories' => 320,
                'protein_g' => 28,
                'carbs_g' => 10,
                'fat_g' => 16,
                'meal_type' => 'Makan Malam',
                'image_url' => 'https://images.unsplash.com/photo-1534080564583-6be75777b70a?w=600&fit=crop',
                'dietary_tags' => ['Normal', 'Halal', 'Pescatarian', 'Keto'],
                'medical_tags' => ['Diabetes', 'Low Sodium', 'Alergi Kacang', 'Alergi Telur', 'Alergi Gluten', 'Alergi Susu (Laktosa)'],
                'target_workout' => ['strength']
            ],
            [
                'name' => 'Pasta Zucchini (Zoodles) Bolognese Vegan',
                'description' => 'Pasta dari serutan timun jepang dengan saus tomat dan tempe cincang.',
                'calories' => 210,
                'protein_g' => 12,
                'carbs_g' => 25,
                'fat_g' => 8,
                'meal_type' => 'Makan Malam',
                'image_url' => 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?w=600&fit=crop',
                'dietary_tags' => ['Vegan', 'Vegetarian', 'Normal', 'Halal', 'Keto', 'Gluten-Free'],
                'medical_tags' => ['Diabetes', 'Hipertensi', 'Alergi Gluten', 'Alergi Seafood', 'Alergi Susu (Laktosa)', 'Alergi Telur'],
                'target_workout' => ['cardio', 'yoga']
            ],
            [
                'name' => 'Telur Rebus Balado Tomat (Low Oil)',
                'description' => 'Telur rebus utuh dengan bumbu tomat pedas manis menggunakan kaldu alami.',
                'calories' => 280,
                'protein_g' => 18,
                'carbs_g' => 12,
                'fat_g' => 15,
                'meal_type' => 'Makan Malam',
                'image_url' => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=600&fit=crop',
                'dietary_tags' => ['Vegetarian', 'Normal', 'Halal'],
                'medical_tags' => ['Kolesterol', 'Alergi Seafood', 'Alergi Kacang', 'Alergi Gluten', 'Alergi Susu (Laktosa)'],
                'target_workout' => ['rest']
            ],
            [
                'name' => 'Tumis Tahu Tauge Bebas Purin',
                'description' => 'Menu aman asam urat karena menghindari kacang tanah dan daging merah.',
                'calories' => 220,
                'protein_g' => 15,
                'carbs_g' => 18,
                'fat_g' => 10,
                'meal_type' => 'Makan Malam',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&fit=crop',
                'dietary_tags' => ['Vegan', 'Vegetarian', 'Normal', 'Halal'],
                'medical_tags' => ['Asam Urat', 'Kolesterol', 'Penyakit Jantung', 'Alergi Seafood', 'Alergi Telur', 'Alergi Susu (Laktosa)'],
                'target_workout' => ['rest']
            ],

            // ================== SNACK ==================
            [
                'name' => 'Salad Buah Segar',
                'description' => 'Potongan buah beri, apel, dan pepaya segar dengan perasan jeruk nipis.',
                'calories' => 120,
                'protein_g' => 1,
                'carbs_g' => 30,
                'fat_g' => 0,
                'meal_type' => 'Snack',
                'image_url' => 'https://images.unsplash.com/photo-1490474418585-ba9bad8fd0ea?w=600&fit=crop',
                'dietary_tags' => ['Vegan', 'Vegetarian', 'Normal', 'Halal', 'Gluten-Free'],
                'medical_tags' => ['Hipertensi', 'Kolesterol', 'Asam Urat', 'Penyakit Jantung', 'Alergi Seafood', 'Alergi Kacang', 'Alergi Telur', 'Alergi Susu (Laktosa)', 'Alergi Gluten'],
                'target_workout' => ['cardio', 'rest']
            ],
            [
                'name' => 'Kacang Almond Panggang',
                'description' => 'Cemilan kaya lemak tak jenuh dan protein, penunda lapar sehat.',
                'calories' => 160,
                'protein_g' => 6,
                'carbs_g' => 6,
                'fat_g' => 14,
                'meal_type' => 'Snack',
                'image_url' => 'https://images.unsplash.com/photo-1505253758473-96b7015fcd40?w=600&fit=crop',
                'dietary_tags' => ['Vegan', 'Vegetarian', 'Normal', 'Halal', 'Keto'],
                'medical_tags' => ['Diabetes', 'Penyakit Jantung', 'Alergi Seafood', 'Alergi Telur', 'Alergi Susu (Laktosa)', 'Alergi Gluten'],
                'target_workout' => ['strength']
            ],
            [
                'name' => 'Yogurt Yunani (Greek Yogurt) & Madu',
                'description' => 'Cemilan probiotik tinggi protein, ramah pencernaan.',
                'calories' => 180,
                'protein_g' => 15,
                'carbs_g' => 15,
                'fat_g' => 5,
                'meal_type' => 'Snack',
                'image_url' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=600&fit=crop',
                'dietary_tags' => ['Vegetarian', 'Normal', 'Halal'],
                'medical_tags' => ['Maag/GERD', 'Hipertensi', 'Alergi Kacang', 'Alergi Seafood', 'Alergi Telur', 'Alergi Gluten'],
                'target_workout' => ['strength']
            ],
            [
                'name' => 'Edamame Rebus',
                'description' => 'Kacang kedelai muda rebus tinggi protein nabati dan serat.',
                'calories' => 110,
                'protein_g' => 10,
                'carbs_g' => 9,
                'fat_g' => 4,
                'meal_type' => 'Snack',
                'image_url' => 'https://images.unsplash.com/photo-1587843477112-68a8ed599723?w=600&fit=crop',
                'dietary_tags' => ['Vegan', 'Vegetarian', 'Normal', 'Halal'],
                'medical_tags' => ['Diabetes', 'Kolesterol', 'Alergi Seafood', 'Alergi Telur', 'Alergi Susu (Laktosa)'],
                'target_workout' => ['cardio']
            ],
            [
                'name' => 'Omelet Putih Telur & Roti Gandum',
                'description' => 'Sarapan rendah kolesterol namun kaya protein.',
                'calories' => 320,
                'protein_g' => 25,
                'carbs_g' => 35,
                'fat_g' => 8,
                'meal_type' => 'Sarapan',
                'image_url' => 'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?w=600&fit=crop',
                'dietary_tags' => ['Normal', 'Vegetarian'],
                'medical_tags' => ['Kolesterol', 'Diabetes'],
                'target_workout' => ['strength']
            ]
        ];

        foreach ($meals as $meal) {
            \App\Models\Meal::updateOrCreate(
                ['name' => $meal['name']],
                $meal
            );
        }
    }
}

<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\Category;

class CategoryTableSeeder extends Seeder
{

    public function run()
    {
        Category::truncate();
        if (($handle = fopen(__DIR__ . '/categories.csv', 'r')) !== false) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                $row++;
                if ($row === 1) {
                    continue;
                } else {
                    Category::create([
                        'name' => $data[0],
                        'short_name' => $data[1],
                        'img' => (isset($data[2]) && !empty($data[2])) ? $data[2] : null,
                    ]);
                }
            }
            fclose($handle);
        }
    }

}
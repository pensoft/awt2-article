<?php

use App\Enums\ArticleSectionTypes;
use App\Models\ArticleSections;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateAllowSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $original = [
            'allow' => [
                'all' => false,
                'values' => []
            ],
            'deny' => [
                'all' => false,
                'values' => []
            ]
        ];

        ArticleSections::whereType(ArticleSectionTypes::COMPLEX)->each(function($item) use ($original){
            $c = $item->compatibility;
            $s = $item->sections->pluck('id')->toArray();

            if($c){
                $original = $this->array_merge_recursive_ex($original, $c);
            }
            $original = $this->array_merge_recursive_ex($original, [
                'allow' => [
                    'all' => false,
                    'values' => $s
                ]
            ]);
            $item->compatibility = $original;
            $item->save();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    private function array_merge_recursive_ex(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => & $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->array_merge_recursive_ex($merged[$key], $value);
            } else if (is_numeric($key)) {
                if (!in_array($value, $merged)) {
                    $merged[] = $value;
                }
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}

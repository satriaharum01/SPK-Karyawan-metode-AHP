<?php

namespace App\Models;

use App\Http\helpers\Formula;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Criteria extends Model
{
    use HasFactory;

    public function subkriterias(): HasMany
    {
        return $this->hasMany(Subcriteria::class, 'criteria_id', 'id');
    }

    /**
     * Get all of the comparison for the Criteria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comparisons(): HasMany
    {
        return $this->hasMany(CriteriaComparison::class, 'criteria_id')->orderBy('column_idx');
    }

    /**
     * Get all of the assessments for the Criteria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'criteria_id');
    }

    public static function getMaxLamda()
    {
        return self::sum('eigen');
    }

    public static function getCI()
    {
        $CI = (self::getMaxLamda() - self::count()) / (self::count() - 1);
        if($CI > 1/100){
            return number_format($CI, 2);
        }else{
            return $CI;
        }
    }
    public static function getCR()
    {
        $ir = Formula::$nilai_index_random[self::count()];
        $ir == 0 ? 0 : $CR = (self::getCI() / $ir);
        if($CR > 1/100){
            return number_format($CR, 2);
        }else{
            return $CR;
        }
    }

    public static function isCRValid()
    {
        return self::getCR() <= 0.1;
    }

    public static function isMatrixValid()
    {
        $matrixZeroValueExists = CriteriaComparison::where('value', 0)->exists();
        return !$matrixZeroValueExists;
    }

    public static function updateCriteriaOrder()
    {
        $criterias = Criteria::all();
        $criteriaCount = $criterias->count();
        $criteriaOrderInvalid = Criteria::where('order', '>', $criteriaCount)->first();
        if ($criteriaOrderInvalid == null) {
            return;
        }

        DB::transaction(function () use ($criterias) {
            $order = 1;
            foreach ($criterias as $criteria) {
                $criteria->order = $order;
                $criteria->save();

                $criteria->load(['comparisons' => function ($query) {
                    $query->orderBy('column_idx', 'asc');
                }]);

                $column = 1;
                foreach ($criteria->comparisons as $comparison) {
                    $comparison->row_idx = $order;
                    $comparison->column_idx = $column;
                    $comparison->save();
                    $column++;
                }
                $order++;
            }
        }, 3);
    }
}

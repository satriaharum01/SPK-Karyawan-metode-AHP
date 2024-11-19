<?php

namespace App\Http\Controllers;

use App\Http\helpers\Formula;
use App\Models\Criteria;
use App\Models\CriteriaComparison;
use App\Models\CriteriaQuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CriteriaComparisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criterias      = Criteria::with('comparisons')->get();
        $count_criteria = $criterias->count();
        $is_valid       = Criteria::isCRValid();
        $IR             = Formula::$nilai_index_random[$count_criteria];
        $CI             = Criteria::getCI();
        $max_lamda      = Criteria::getMaxLamda();
        $CR             = Criteria::getCR();
        $matrix_valid    = Criteria::isMatrixValid();

        return view('pages.criteria.matrix', compact(
            'criterias',
            'is_valid',
            'IR',
            'CI',
            'CR',
            'max_lamda',
            'count_criteria',
            'matrix_valid'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function geodata()
    {
        $criteria = Criteria::orderBy('order')->get();
        $data = array();
        $geomean = array();
        $i = 1;
        $gep = array();
        foreach ($criteria as $low) {
            $j = 1;
            foreach ($criteria as $row) {
                if ($row->id == $low->id) {
                    $geomean[$i][$j] = 1;
                } elseif ($row->order >= $low->order) {
                    $geodata = array();
                    $kuesioner = CriteriaQuesioner::select('pilihan')->where('id_criteria', $low->id)->where('id_comparison_criteria', $row->id)->get();
                    foreach ($kuesioner as $kues) {
                        $geodata[] = Formula::$pilihan_responden[$kues->pilihan];
                    }

                    $geomean[$i][$j] = $this->calculateGeomean($geodata);
                    $gep[] =  $this->calculateGeomean($geodata);
                } else {
                    $geomean[$i][$j] = 1 / $geomean[$j][$i];
                }
                $j++;
            }
            $i++;
        }
        $sum = array();
        for ($i = 1; $i <= $criteria->count();$i++) {
            $sum[$i] = array_sum(array_column($geomean, $i));
            Criteria::where('order', $i)->update(['comparison_column_sum' => $sum[$i]]);
        }

        //Normalisasi Matriks
        $this->normalizationMatrix($geomean, $sum);

        CriteriaComparison::calculateMatrix();

        return redirect()->route('criteria.matrix');
    }
    public function store_lama(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Simpan criteria comparison
            $criteriaComparisons = CriteriaComparison::all();
            foreach ($criteriaComparisons as $criteriaComparison) {
                $criteriaComparison->value = $request->post(strval($criteriaComparison->id));
                $criteriaComparison->save();
            }
            CriteriaComparison::calculateMatrix();
        }, env("DB_RETRY", 3));

        $redirect = route('criteria.matrix');
        return response()->json(['redirect' => $redirect]);
    }
    public function hasil()
    {
        $criterias = Criteria::with('comparisons')->get();
        $count_criteria = $criterias->count();
        $is_valid  = Criteria::isCRValid();
        $IR = Formula::$nilai_index_random[$count_criteria];
        $CI = Criteria::getCI();
        $max_lamda = Criteria::getMaxLamda();

        return view('pages.criteria.matrix', compact(
            'criterias',
            'is_valid',
            'IR',
            'CI',
            'max_lamda',
            'count_criteria'
        ));
    }

    public function calculateGeomean(array $numbers)
    {
        $product = 1; // Inisialisasi hasil perkalian
        $count = count($numbers); // Jumlah elemen dalam array

        if ($count === 0) {
            return null; // Return null jika array kosong
        }

        foreach ($numbers as $number) {
            if ($number <= 0) {
                return null; // Geomean tidak valid untuk angka nol atau negatif
            }
            $product *= $number;
        }

        // Menghitung akar pangkat ke-n dari hasil perkalian
        return pow($product, 1 / $count);
    }

    public function normalizationMatrix($data, $sum)
    {
        $rowSum = array();
        $temp = array();
        foreach ($data as $key => $val) {
            foreach ($val as $idx => $idxVal) {
                $normalizationVal = $idxVal / $sum[$key];
                $temp[] = $normalizationVal;
                CriteriaComparison::where('row_idx', $key)->where('column_idx', $idx)->update(['value' => $idxVal]);
                CriteriaComparison::where('row_idx', $key)->where('column_idx', $idx)->update(['normalization_value' => $normalizationVal]);
            }
            Criteria::where('order', $key)->update(['normalization_row_sum' => array_sum($temp)]);
        }

    }
}

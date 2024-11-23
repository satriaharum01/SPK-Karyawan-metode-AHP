<?php

namespace App\Http\Controllers;

use App\Http\helpers\Formula;
use App\Models\Subcriteria;
use App\Models\SubcriteriaComparison;
use App\Models\SubcriteriaQuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubcriteriaComparisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($criteria_id)
    {
        $subcriterias      = Subcriteria::where('criteria_id', $criteria_id)->with('comparisons')->get();
        $count_subcriteria = $subcriterias->count();
        $is_valid          = Subcriteria::isCRValid($criteria_id);
        $IR                = Formula::$nilai_index_random[$count_subcriteria];
        $CI                = Subcriteria::getCI($criteria_id);
        $max_lamda         = Subcriteria::getMaxLamda($criteria_id);
        $CR                = Subcriteria::getCR($criteria_id);
        $matrix_valid      = Subcriteria::isMatrixValid($criteria_id);

        return view('pages.subcriteria.matrix', compact(
            'criteria_id',
            'subcriterias',
            'is_valid',
            'IR',
            'CI',
            'CR',
            'max_lamda',
            'count_subcriteria',
            'matrix_valid'
        ));
    }

    public function geodata($criteria_id)
    {
        $subcriteria = Subcriteria::where('criteria_id',$criteria_id)->orderBy('order')->get();
        $data = array();
        $geomean = array();
        $i = 1;
        $gep = array();
        foreach ($subcriteria as $low) {
            $j = 1;
            foreach ($subcriteria as $row) {
                if ($row->id == $low->id) {
                    $geomean[$i][$j] = 1;
                } elseif ($row->order >= $low->order) {
                    $geodata = array();
                    $kuesioner = SubcriteriaQuesioner::select('pilihan')->where('id_subcriteria', $low->id)->where('id_comparison_subcriteria', $row->id)->get();
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
        for ($i = 1; $i <= $subcriteria->count();$i++) {
            $sum[$i] = array_sum(array_column($geomean, $i));
            subcriteria::where('criteria_id',$criteria_id)->where('order', $i)->update(['comparison_column_sum' => $sum[$i]]);
        }

        //Normalisasi Matriks
        $this->normalizationMatrix($geomean, $sum, $criteria_id);

        SubcriteriaComparison::calculateMatrix($criteria_id);

        return redirect()->route('subcriteria.matrix', ['criteriaId' => $criteria_id]);
    }

    public function store(Request $request, $criteria_id)
    {
        // dd($request);
        DB::transaction(function () use ($request, $criteria_id) {
            // Simpan subcriteria comparison
            $subcriteriaComparisons = SubcriteriaComparison::whereHas('subcriteria', function ($query) use ($criteria_id) {
                $query->where('criteria_id', $criteria_id);
            })->get();

            foreach ($subcriteriaComparisons as $criteriaComparison) {
                $criteriaComparison->value = $request->post(strval($criteriaComparison->id));
                $criteriaComparison->save();
            }

            SubcriteriaComparison::calculateMatrix($criteria_id);
        });

        $redirect = route('subcriteria.matrix', ['criteriaId' => $criteria_id]);
        return response()->json(['redirect' => $redirect]);
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

    public function normalizationMatrix($data, $sum, $criteria_id)
    {
        $rowSum = array();
        $temp = array();
        foreach ($data as $key => $val) {
            foreach ($val as $idx => $idxVal) {
                $normalizationVal = $idxVal / $sum[$key];
                $temp[] = $normalizationVal;
                SubcriteriaComparison::whereHas('subcriteria', function ($query) use ($criteria_id) {
                    $query->where('criteria_id', $criteria_id);
                })->where('row_idx', $key)->where('column_idx', $idx)->update(['value' => $idxVal]);
                SubcriteriaComparison::whereHas('subcriteria', function ($query) use ($criteria_id) {
                    $query->where('criteria_id', $criteria_id);
                })->where('row_idx', $key)->where('column_idx', $idx)->update(['normalization_value' => $normalizationVal]);
            }
            Subcriteria::where('criteria_id',$criteria_id)->where('order', $key)->update(['normalization_row_sum' => array_sum($temp)]);
        }

    }
    // public function alternatif()
    // {
    //     $kriterias = Criteria::get();
    //     $alternatifs = Employee::get();
    //     $nilai_prioritas_subkriterias = SubcriteriaPriority::get();
    //     $perhitungans_all = Calculation::get();
    //     $is_valid = ValidCriteria::first();
    //     $is_subkriteria_valid = ValidSubcriteria::where('is_valid', false)->first();
    //     $error_alternatif = false;
    //     $error_sub = false;
    //     $sub_valid = ValidSubcriteria::get();
    //     if (count($sub_valid) != count($kriterias)) {
    //         $error_sub = true;
    //     }

    //     foreach ($alternatifs as $alt) {
    //         $count_alt_det = EmployeeDetail::where('employee_id', $alt->id)->get();
    //         if (count($count_alt_det) != count($kriterias)) {
    //             $error_alternatif = true;
    //             break;
    //         }
    //     }

    //     return view('pages.perhitungan_subkriteria.alternatif', [
    //         'subcriterias' => $kriterias,
    //         'alternatifs' => $alternatifs,
    //         'nilai_prioritas_subkriterias' => $nilai_prioritas_subkriterias,
    //         'perhitungans_all' => $perhitungans_all,
    //         'is_valid' => $is_valid,
    //         'is_subkriteria_valid' => $is_subkriteria_valid ? false : true,
    //         'error_sub' => $error_sub,
    //         'error_alternatif' => $error_alternatif,
    //     ]);
    // }
}

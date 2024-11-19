<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use App\Models\Criteria;
use App\Models\CriteriaQuesioner;
use App\Http\helpers\Formula;

class KuesionerController extends Controller
{
    public function index()
    {
        $originalData = CriteriaQuesioner::all()
        ->groupBy('responden')
        ->map(function ($items, $responden) {
            return [
                'responden' => $responden,
                'id_criteria_array' => $items->pluck('id_criteria')->toArray(),
            ];
        });

        // Konversi ke Collection dan paginate secara manual
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10;
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $originalData->forPage($currentPage, $perPage)->values(),
            $originalData->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $criteria = Criteria::orderBy('order')->get();
        $pilihan = Formula::$pilihan_responden;
        return view('pages.criteria.quesioner')
            ->with("data", $originalData)
            ->with("paginatedData", $paginatedData)
            ->with("pilihan", $pilihan)
            ->with("criteria", $criteria);
    }

    public function store(Request $request)
    {
        if (CriteriaQuesioner::where('responden', $request->name)->count() > 0) {
            toast('Nama responden sudah ada', 'error');
            return redirect()->route('criteria.quesioner');
        }

        $data = $request->responden;
        foreach ($data as $row) {
            CriteriaQuesioner::create([
              'id_criteria' => $row['id_criteria'],
              'id_comparison_criteria' => $row['id_comparison_criteria'],
              'responden' => $request->name,
              'pilihan' => $row['pilihan']
            ]);

        }

        toast('Berhasil menambahkan responden', 'success');
        return redirect()->route('criteria.quesioner');
    }

    public function edit($name)
    {
        $row =  CriteriaQuesioner::where('responden', $name)->orderby('id')->get();
        return response($row);
    }
    
    public function update(Request $request, $name)
    {
        
        $rows = CriteriaQuesioner::where('responden', $name);
        $rows->delete();
        
        $data = $request->responden;
        foreach ($data as $row) {
            CriteriaQuesioner::create([
              'id_criteria' => $row['id_criteria'],
              'id_comparison_criteria' => $row['id_comparison_criteria'],
              'responden' => $request->name,
              'pilihan' => $row['pilihan']
            ]);

        }

        toast('Berhasil edit responden', 'success');
        return redirect()->route('criteria.quesioner');
    }

    public function destroy($name)
    {
        $rows = CriteriaQuesioner::where('responden', $name);
        $rows->delete();

        toast('Berhasil menghapus responden', 'success');
        return redirect()->route('criteria.quesioner');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use App\Models\Subcriteria;
use App\Models\SubcriteriaQuesioner;
use App\Http\helpers\Formula;

class SubKuesionerController extends Controller
{
    public function index($id)
    {
        $originalData = SubcriteriaQuesioner::whereIn('id_subcriteria', $this->getSubcriteriaInCriteriaId($id))
        ->get()
        ->groupBy('responden')
        ->map(function ($items, $responden) {
            return [
                'responden' => $responden,
                'id_subcriteria_array' => $items->pluck('id_subcriteria')->toArray(),
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

        $subcriteria = Subcriteria::where('criteria_id', $id)->orderBy('order')->get();
        $pilihan = Formula::$pilihan_responden;
        return view('pages.subcriteria.quesioner')
            ->with("data", $originalData)
            ->with("paginatedData", $paginatedData)
            ->with("pilihan", $pilihan)
            ->with("subcriteria", $subcriteria)
            ->with("criteriaId", $id);
    }

    public function store(Request $request)
    {
        if (SubcriteriaQuesioner::where('responden', $request->name)->whereIn('id_subcriteria', $this->getSubcriteriaInCriteriaId($request->id_criteria))->count() > 0) {
            toast('Nama responden sudah ada', 'error');
            return redirect()->route('criteria.quesioner');
        }

        $data = $request->responden;
        foreach ($data as $row) {
            SubcriteriaQuesioner::create([
              'id_subcriteria' => $row['id_subcriteria'],
              'id_comparison_subcriteria' => $row['id_comparison_subcriteria'],
              'responden' => $request->name,
              'pilihan' => $row['pilihan']
            ]);

        }

        toast('Berhasil menambahkan responden', 'success');
        return redirect()->route('subcriteria.quesioner', ['criteriaId' => $request->id_criteria])->with('success', 'Berhasil menambahkan kuesioner');
    }

    public function edit($Criteriaid, $name)
    {
        $row =  SubcriteriaQuesioner::where('responden', $name)->whereIn('id_subcriteria', $this->getSubcriteriaInCriteriaId($Criteriaid))->orderby('id')->get();
        return response($row);
    }

    public function update(Request $request, $name)
    {

        $rows = SubcriteriaQuesioner::where('responden', $name)->whereIn('id_subcriteria', $this->getSubcriteriaInCriteriaId($request->id_criteria));
        $rows->delete();

        $data = $request->responden;
        foreach ($data as $row) {
            SubcriteriaQuesioner::create([
              'id_subcriteria' => $row['id_subcriteria'],
              'id_comparison_subcriteria' => $row['id_comparison_subcriteria'],
              'responden' => $request->name,
              'pilihan' => $row['pilihan']
            ]);

        }

        toast('Berhasil edit responden', 'success');
        return redirect()->route('subcriteria.quesioner', ['criteriaId' => $request->id_criteria])->with('success', 'Berhasil update kuesioner');
    }

    public function destroy($Criteriaid, $name)
    {
        $rows = SubcriteriaQuesioner::where('responden', $name)->whereIn('id_subcriteria', $this->getSubcriteriaInCriteriaId($Criteriaid));
        $rows->delete();

        toast('Berhasil menghapus responden', 'success');

        return redirect()->route('subcriteria.quesioner', ['criteriaId' => $Criteriaid])->with('success', 'Berhasil menghapus kuesioner');
    }

    public function getSubcriteriaInCriteriaId($Criteriaid)
    {
        $criteria = Subcriteria::select('id')->where('criteria_id', $Criteriaid)->orderBy('order')->get()->toArray();

        return $criteria;
    }
}

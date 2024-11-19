<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaQuesioner extends Model
{
    use HasFactory;

    protected $table = 'criteria_quesioner';
    protected $primaryKey = 'id';
    protected $fillable = ['id_criteria','id_comparison_criteria','responden','pilihan'];

    public function cari_criteria()
    {
        return $this->belongsTo('App\Models\Criteria', 'id_criteria', 'id')->withDefault([
            'name' => 'Invalid'
        ]);
    }
}

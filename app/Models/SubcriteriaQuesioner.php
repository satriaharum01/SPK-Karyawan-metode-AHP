<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcriteriaQuesioner extends Model
{
    use HasFactory;

    protected $table = 'subcriteria_quesioner';
    protected $primaryKey = 'id';
    protected $fillable = ['id_subcriteria','id_comparison_subcriteria','responden','pilihan'];

    public function cari_criteria()
    {
        return $this->belongsTo('App\Models\Subcriteria', 'id_subcriteria', 'id')->withDefault([
            'name' => 'Invalid'
        ]);
    }
}

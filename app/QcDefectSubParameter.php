<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QcDefectSubParameter extends Model
{
    public function qm_sheet_sub_parameter()
    {
        return $this->belongsTo('App\QmSheetSubParameter','sub_parameter_id');
    }
}

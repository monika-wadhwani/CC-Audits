<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\RawDumpsActiveScope;
class Audit extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new RawDumpsActiveScope);
    }
    public function raw_data()
    {
        return $this->belongsTo('App\RawData');
    }
    public function client()
     {
         return $this->belongsTo('App\Client');
     }
    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }
    public function audit_parameter_result()
    {
        return $this->hasMany('App\AuditParameterResult');
    }
    public function audit_results()
    {
        return $this->hasMany('App\AuditResult','audit_id');
    }
    public function qa_qtl_detail()
    {
        return $this->belongsTo('App\User','audited_by_id','id');
    }
    public function audit_rebuttal()
    {
        return $this->hasMany('App\Rebuttal','audit_id','id');
    }
    public function audit_rebuttal_accepted()
    {
        return $this->hasMany('App\Rebuttal','audit_id')->where('status',1);
    }
    public function audit_rebuttal_not_validate_action()
    {
        return $this->hasMany('App\Rebuttal','audit_id')->where('valid_invalid',0);
    }
    public function audit_rebuttal_rejected()
    {
        return $this->hasMany('App\Rebuttal','audit_id')->where('status',2);
    }   
    public function auditor()
    {
        return $this->belongsTo('App\User','audited_by_id');
    }
    public function qc_defect_sub_parameter()
    {
        return $this->hasMany('App\QcDefectSubParameter','audit_id');
    }

    public function process()
    {
        return $this->belongsTo('App\Process','process_id');
    }
    
}

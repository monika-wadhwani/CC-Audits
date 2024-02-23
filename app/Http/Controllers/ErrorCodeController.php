<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\Client;
use App\ClientsQtl;
use App\FailedRawDumpRow;
use App\FailedRawDumpSlot;
use App\Imports\ErrorDumpImport;
use App\Imports\QaTargetImport;
use App\Imports\QaTargetImportMonth;
use App\Imports\QaTargetImportDaily;
use App\Partner;
use App\PartnerLocation;
use App\PartnersProcess;
use App\Process;
use App\QmSheet;
use App\RawData;
use App\RoleUser;
use App\DataPurge;
use App\User;
use App\ErrorCode;
use Auth;
use Crypt;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\FailedMonthTargetRow;
use App\FailedMonthTarget;
use App\MonthTarget;
use App\Imports\MonthlyTargetImport;


class ErrorCodeController extends Controller
{
    public function error_code_uploader(){
       
        return view('porter_design.error_code.error_code_dump_upload');
    }
    public function upload_error_code_dump(Request $request){
        $data = Excel::import(new ErrorDumpImport([
            'upload_by_user_id'=>Auth::user()->id,       
         ]),$request->error_dump_file);
        
         return redirect('error_code/dump_uploader')->with('success', 'Error codes uploaded successfully');   
         
    }
    public function codes_list(){
        $error_codes = ErrorCode::get();
        return view('porter_design.error_code.error_code_list',compact('error_codes'));
    }
}
<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');
use App\Client;
use App\ClientsQtl;
use App\FailedRawDumpRow;
use App\FailedRawDumpSlot;
use App\Imports\RawDataImport;
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
use Auth;
use Crypt;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\FailedMonthTargetRow;
use App\FailedMonthTarget;
use App\MonthTarget;
use App\Imports\MonthlyTargetImport;


class AllocationController extends Controller
{
  public function qtl_qa()
  {
    $all_qtl = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qtl');
    })->pluck('name', 'id');
    $all_company_qa_list = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->get();
    return view('porter_design.allocation.qtl_qa', compact('all_qtl','all_company_qa_list'));
  }
  // public function qtl_qa()
  // {
  //   $all_qtl = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
  //     $query->where('name', '=', 'qtl');
  //   })->pluck('name', 'id');
   
  //   return view('allocation.qtl_qa', compact('all_qtl'));
  // }
  public function get_company_qtl_list()
  {
    $all_qtl = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qtl');
    })->pluck('name', 'id');

    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_qtl], 200);
  }
  public function get_qtl_team_list($qtl_id)
  {
    $data = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->where('reporting_user_id', $qtl_id)->get();

    $html = "";
    if (count($data) > 0) {
        foreach ($data as $key=>$u) {
            $html .= '
                <tr>
                    <td>' . ($key+1) . '</td>
                    <td>' . $u->name . '</td>
                    <td>' . $u->email . '</td>
                    <td><a style="color:blue;" href="' . url('remove_qa_from_qtl_team/' . $u->id) . '">' . '<span class="bi bi-trash"></span>' . '</a></td>
                </tr>
            ';
        }
    }
    return $html;
    // return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);
  }
  public function get_company_qa_list()
  {
    $all_qtl = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->get();

    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_qtl], 200);
  }
  public function store_qtl_qa(Request $request)
  {
    foreach ($request->all_ids as $key => $value) {

      DB::table('users')
        ->where('id', $value)
        ->update(['reporting_user_id' => $request->selected_qtl]);
    }
    return redirect('allocation/qtl_qa')->with('success', 'Team updated successfully.');
  }
  public function remove_qa_from_qtl_team($qa_id)
  {
    DB::table('users')
      ->where('id', $qa_id)
      ->update(['reporting_user_id' => null]);
    return redirect('allocation/qtl_qa')->with('success', 'Team updated successfully.');
    // return response()->json(['status' => 200, 'message' => "Team updated successfully."], 200);
  }
  public function qa_sheet()
  {
    if (Auth::user()->hasRole('client'))
    $all_client = Client::where('id', Auth::user()->client_detail->client_id)->pluck('name', 'id');
  else
    $all_client = Client::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
    return view('porter_design.allocation.qa_sheet',compact('all_client'));
  }
  public function get_company_client_list()
  {
    if (Auth::user()->hasRole('client'))
      $all_client = Client::where('id', Auth::user()->client_detail->client_id)->pluck('name', 'id');
    else
      $all_client = Client::where('company_id', Auth::user()->company_id)->pluck('name', 'id');


    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_client], 200);
  }
  public function get_qtls_by_client($client_id)
  {

    $temp = ClientsQtl::where('client_id', $client_id)->with('qtl_user')->get();
    $data = [];
    foreach ($temp as $key => $value) {
      $data[$value->qtl_user_id] = $value->qtl_user->name;
    }
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);
  }
  /* 
  public function get_sheets_by_client($client_id)
  {
    $all_sheet_list = QmSheet::where('client_id',$client_id)->with('process')->get();
    return response()->json(['status'=>200,'message'=>"Success",'data'=>$all_sheet_list], 200);
  } */

  public function upload_qa_target_month()
  {
    Excel::import(new QaTargetImportMonth, request()->file('file'));
    return redirect('qa_target/list')->with('success', 'QA Target updated successfully');
  }

  public function upload_qa_target_daily(Request $request)
  {
    Excel::import(new QaTargetImportDaily, request()->file('file'));
    return redirect('qa_daily_target/list')->with('success', 'QA Target updated successfully');
  }
  public function get_sheets_by_client($client_id)
  {
    $maxVersion = QmSheet::where('client_id', $client_id)->max('version');

    //$all_latest_sheet_list = QmSheet::where('client_id',$client_id)->where('version', $maxVersion)->pluck('id')->toArray();
    $all_latest_sheet_list = QmSheet::where('client_id', $client_id)->pluck('id')->toArray();

    $all_sheet_list = QmSheet::where('client_id', $client_id)->whereIn('id', $all_latest_sheet_list)->with('process')->get();
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_sheet_list], 200);
  }

  public function get_qtl_team($qtl_id)
  {
    $data = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->where('reporting_user_id', $qtl_id)->get();

    $html = "";
    if (count($data) > 0) {
        foreach ($data as $key=>$u) {
            $html .= '
                <tr>
                    <td><input type="checkbox" name="all_ids[]" value="'.$u->id.'" class="selectCheckbox"></td>
                    <td>' . $u->name . '</td>
                    <td>' . $u->email . '</td>
                </tr>
            ';
        }
    }
    return $html;
  }
  /* public function store_qa_sheet(Request $request)
  {
     foreach ($request->my_team as $key => $value) {
     DB::table('users')
           ->where('id', $value['id'])
           ->update(['assigned_sheet_id' => $request->sheet_id]);
       }
       return response()->json(['status'=>200,'message'=>"QM Sheet alloted successfully."], 200); 
  } */

  public function store_qa_sheet(Request $request)
  {
    $process_ids = array_map(function ($prod) {
      return substr($prod, strpos($prod, "-") + 1);
    }, $request->sheet_id);

    $json_pro_ids = json_encode($process_ids);

    $array_size = sizeof($process_ids);
    $last_process_id = $process_ids[$array_size - 1];

    $sheet_details = QmSheet::where('process_id', $last_process_id)->orderby('id', 'desc')->first();

    $sheet_ids = array_map(function ($prod) {
      return substr($prod, 0, strpos($prod, '-'));
    }, $request->sheet_id);


    foreach ($request->my_team as $key => $value) {
      /*DB::table('users')
            ->where('id', $value['id'])
            ->update(['assigned_sheet_id' => $request->sheet_id]);
        }*/

      $user_update = DB::table('users')
        ->where('id', $value['id'])
        ->update(['assigned_process_ids' => $json_pro_ids]);

      $user_update_sheet = DB::table('users')
        ->where('id', $value['id'])
        ->update(['assigned_sheet_id' => $sheet_details->id]);

    }
    return redirect('allocation/qa_sheet')->with('success', 'QM Sheet alloted successfully.');
    // return response()->json(['status' => 200, 'message' => "QM Sheet alloted successfully."], 200);
  }

  public function get_qm_sheet_associated_qa($sheet_id)
  {
    $associated_qa = User::where('company_id', Auth::user()->company_id)->whereHas('roles', function ($query) {
      $query->where('name', '=', 'qa');
    })->where('assigned_sheet_id', $sheet_id)->get();
dd($associated_qa);
    $html = "";
    if (count($associated_qa) > 0) {
        foreach ($associated_qa as $key=>$u) {
            $html .= '
                <tr>
                    <td>' . $u->name . '</td>
                    <td>' . $u->email . '</td>
                </tr>
            ';
        }
    }
    return $html;
    // return response()->json(['status' => 200, 'message' => "Success", 'data' => $associated_qa], 200);
  }

  public function pendingList($batch_id)
  {
    $data = RawData::where(['batch_id' => Crypt::decrypt($batch_id), 'status' => 0, 'visiblity' => 1])->get();
    $allQa = RoleUser::with('getUser')->where('role_id', 5)->get();
    //dd($allQA); die;
    $batch_id = Crypt::decrypt($batch_id);
    return view('porter_design.allocation.pending_list', compact('data', 'allQa', 'batch_id'));
  }

  public function reassign(Request $request)
  {
    //echo "<pre>"; print_r($request->all()); die;
    $r = RawData::find($request->raw_data_id);
    $r->qa_id = $request->qa_id;
    $r->save();
    return redirect('allocation/pendingList/' . Crypt::encrypt($request->batch_id))->with('success', 'QA Re-assigned successfully');
  }

  public function dump_uploader()
  {
    if(Auth::user()->id == 333){
    $all_client = Client::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
    return view('allocation.dump_uploader',compact('all_client')); 
    }
    else{
    // if (Auth::user()->hasRole('client'))
    // $all_client = Client::where('id', Auth::user()->client_detail->client_id)->pluck('name', 'id');
    $all_client = Client::where('id', 14)->pluck('name', 'id');
    return view('porter_design.allocation.dump_uploader',compact('all_client')); 
  }
}
  public function month_target_uploader()
  {
    return view('allocation.month_target_uploader');
  }

  public function upload_month_target(Request $request)
  {


    $batch = new FailedMonthTarget;

    // $batch->date = $request->dump_date;
    $batch->company_id = $request->company_id;
    $batch->client_id = $request->client_id;
    $batch->partner_id = $request->partner_id;
    $batch->process_id = $request->process_id;
    $batch->uploaded_by = Auth::user()->id;
    $batch->month_of_target = $request->dump_date;
    $request->raw_data_file->store("month_target_file");

    $batch->file_name = $request->raw_data_file->hashName();

    $batch->save();
    if ($batch->id) {

      $data = Excel::import(new MonthlyTargetImport([
        'dump_date' => $request->dump_date,
        'company_id' => $request->company_id,
        'client_id' => $request->client_id,
        'partner_id' => $request->partner_id,
        'process_id' => $request->process_id,
        'batch_id' => $batch->id,
        'lob' => $request->lob
      ]), $request->raw_data_file);
    }

    // return redirect('allocation/upload_report/'.$batch->id)->with('success', 'Raw data uploaded successfully, please falied record status.');
    //return redirect('allocation/list_month_target/')->with('success', 'Target data uploaded successfully.');    
    return redirect('allocation/upload_target_report/' . Crypt::encrypt($batch->id))->with('success', 'Target data uploaded successfully, Following list could not be uploaded.');

  }

  public function upload_target_report($batch_id)
  {
    $data = FailedMonthTargetRow::where('slot_batch_id', Crypt::decrypt($batch_id))->get();
    return view('allocation.upload_target_report', compact('data'));
  }

  public function partner_target_full($batch_id)
  {
    $data = MonthTarget::where('slot_batch_id', Crypt::decrypt($batch_id))->get();
    return view('porter_design.allocation.partner_target_full', compact('data'));
  }

  public function monthly_partner_targets()
  {
    $data = FailedMonthTarget::with('client', 'process', 'partner', 'uploader', 'audit_cycle')->orderBy('id', 'desc')->limit(50)->get();

    return view('porter_design.allocation.monthly_partner_targets', compact('data'));
  }

  public function list_month_target()
  {

    $data = MonthTarget::select('partners.name', 'auditcycles.name as cycle_name', 'month_targets.*')
      ->join('partners', 'month_targets.partner_id', '=', 'partners.id')
      ->join('auditcycles', 'month_targets.month_of_target', '=', 'auditcycles.id')
      ->get();
    /*  echo "<pre>";
    print_r($data);
    dd(); */
    return view('allocation.month_target_list', compact('data'));
  }

  public function get_client_partner($client_id)
  {
      $data = Partner::where('client_id', $client_id)->pluck('name', 'id');
      return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);

  }
  public function get_partners_process($partner_id)
  {
    $temp = PartnersProcess::where('partner_id', $partner_id)->with('process')->get();
    $data = [];
    foreach ($temp as $key => $value) {
      $data[$value->process_id] = $value->process->name;
    }
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);
  }
  public function upload_raw_data_dump(Request $request)
  {
    $batch = new FailedRawDumpSlot;
    //$batch->date = (!empty($request->dump_date))? date("Y-m-d", strtotime($request->dump_date)):date("Y-m-d");

    $batch->date = date("Y-m-d", strtotime($request->dump_date));
    $batch->company_id = $request->company_id;
    $batch->client_id = $request->client_id;
    $batch->partner_id = $request->partner_id;
    $batch->process_id = $request->process_id;
    $batch->uploader_id = Auth::user()->id;
    $request->raw_data_file->store("raw_data_dump_file");
    $batch->file_name = $request->raw_data_file->hashName();

    $batch->save();
    if ($batch->id) {
      $data = Excel::import(new RawDataImport([
        'dump_date' => date("Y-m-d", strtotime($request->dump_date)),
        'company_id' => $request->company_id,
        'client_id' => $request->client_id,
        'partner_id' => $request->partner_id,
        'process_id' => $request->process_id,
        'batch_id' => $batch->id
      ]), $request->raw_data_file);
    }

    return redirect('allocation/upload_report/' . $batch->id)->with('success', 'Raw data uploaded successfully, please falied record status.');
  }
  public function qa_agent()
  {
    return view('allocation.qa_agent');
  }
  public function get_available_agents_to_allocate(Request $request)
  {
    $data = RawData::where('status', 0)->where('dump_date', date("Y-m-d", strtotime($request->dump_date)))->where('client_id', $request->client_id)->where('partner_id', $request->partner_id)->where('qa_id', null)->groupBy('agent_name')->get();
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data->toArray()], 200);
  }
  public function update_qa_agent(Request $request)
  {
    //return $request;
    foreach ($request->selected_agent_list as $key => $value) {

      DB::table('raw_data')
        ->where('dump_date', date("Y-m-d", strtotime($request->dump_date)))
        ->where('client_id', $request->client_id)
        ->where('partner_id', $request->partner_id)
        ->where('agent_name', $value['agent_name'])
        ->update(['qtl_id' => $request->selected_qtl, 'qa_id' => $request->selected_qa]);

    }
    return response()->json(['status' => 200, 'message' => "Agent alloted successfully."], 200);
  }
  public function get_alloted_partners_agent_by_qa(Request $request)
  {
    $data = RawData::where('status', 0)->where('client_id', $request->client_id)->where('partner_id', $request->partner_id)->where('qa_id', $request->qa_id)->groupBy('agent_name')->get();
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $data->toArray()], 200);
  }

  public function remove_agent_from_qa_team(Request $request)
  {

    DB::table('raw_data')
      ->where('dump_date', date("Y-m-d", strtotime($request->dump_date)))
      ->where('client_id', $request->client_id)
      ->where('partner_id', $request->partner_id)
      ->where('qa_id', $request->qa_id)
      ->where('agent_name', $request->agent_name)
      ->update(['qtl_id' => null, 'qa_id' => null]);
    return response()->json(['status' => 200, 'message' => "Agent detached successfully."], 200);
  }
  public function get_partners_location($partner_id)
  {
    $all_partner_locations = [];
    $all_partner_locations_temp = PartnerLocation::where('partner_id', $partner_id)->with('location_detail')->get();
    foreach ($all_partner_locations_temp as $key => $value) {
      $all_partner_locations[$value->location_id] = $value->location_detail->name;
    }
    return response()->json(['status' => 200, 'message' => "Success", 'data' => $all_partner_locations], 200);
  }
  public function upload_report($batch_id)
  {
    $data = FailedRawDumpRow::where('failed_raw_dump_slot_id', $batch_id)->get();
    if(Auth::user()->id == 333){
      return view('allocation.upload_report', compact('data'));
    }
    else{
      return view('porter_design.allocation.upload_report', compact('data'));
    }
  }

  public function qa_target()
  {
    return view('porter_design.allocation.qa_target');
  }

  public function upload_qa_target()
  {
    Excel::import(new QaTargetImport, request()->file('file'));
    return redirect('allocation/qa_target')->with('success', 'Target updated successfully');
  }

  public function batch_data()
  {
    $data = FailedRawDumpSlot::with(['client', 'process', 'partner', 'uploader'])->orderby('id', 'desc')->limit(100)->get();

    return view('porter_design.allocation.raw_data_batch', compact('data'));
  }

  public function change_visiblity_status($batch_id, $status)
  {
    $raw_records = RawData::where('batch_id', Crypt::decrypt($batch_id))->get();

    foreach ($raw_records as $key => $value) {
      $value->visiblity = $status;
      $value->save();
    }


    $batch = FailedRawDumpSlot::find(Crypt::decrypt($batch_id));
    $batch->visiblity = $status;
    $batch->save();

    return redirect('allocation/raw_data_batch')->with('success', 'Batch status updated successfully');
  }

  public function destroy($id)
  {
    FailedRawDumpSlot::find(Crypt::decrypt($id))->delete();
    RawData::where('batch_id', Crypt::decrypt($id))->where('status', 0)->delete();
    return redirect('allocation/raw_data_batch')->with('success', 'Raw Data Batch deleted successfully.');
  }

  public function data_purge()
  {
    $data = DataPurge::all();
    return view('porter_design.allocation.data_purge', compact('data'));
  }

  public function reassign_multiple(Request $request)
  {
    /* echo $_POST['raw_data'][0]; */
    $allocate_to = $request->qa_id;
    $batch_id = $request->batch_id;
    /* echo $allocate_to;
    dd(); */
    foreach ($_POST['raw_data'] as $key => $value) {
      /* echo $key;
      echo "Hiii";
      echo $value; */
      if ($value == 1 and $key != 0 and $allocate_to != 0) {
        $raw_data = RawData::find($key);
        if ($raw_data->status == 0) {
          $raw_data->qa_id = $allocate_to;
          $raw_data->save();
        }
      }

    }

    /* echo $batch_id;
    dd(); */
    $data = RawData::where(['batch_id' => $batch_id, 'status' => 0, 'visiblity' => 1])->get();
    $allQa = RoleUser::with('getUser')->where('role_id', 5)->get();
    //dd($allQA); die;
    return view('porter_design.allocation.pending_list', compact('data', 'allQa', 'batch_id'));
  }

  public function reassign_search(Request $request)
  {
    /* echo $_POST['raw_data'][0]; */
    $search = $request->search;
    $search = "%" . $search . "%";
    $batch_id = $request->batch_id;

    /* echo $batch_id;
    dd(); */

    $data = RawData::select('raw_data.*')->join('users', 'raw_data.qa_id', '=', 'users.id')
      ->where(['batch_id' => $batch_id, 'raw_data.status' => 0, 'raw_data.visiblity' => 1])
      ->where('users.name', 'like', $search)
      ->get();
    $allQa = RoleUser::with('getUser')->where('role_id', 5)->get();
    //dd($allQA); die;
    return view('porter_design.allocation.pending_list', compact('data', 'allQa', 'batch_id'));
  }

}
<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\Auditcycle;
use App\Client;
use App\Process;
use App\QmSheet;

use Illuminate\Http\Request;


use Validator;
use Auth;
use Crypt;

class AuditcycleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Auditcycle::with(['client','process','qmsheet'])->get();;
        return view('porter_design.audit_cycle/list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $all_clients = Client::all()->pluck('name','id');
        $all_process = Process::all()->pluck('name','id');
        $all_qmsheet = QmSheet::all()->pluck('name','id');
        return view('porter_design/audit_cycle/create',compact('all_clients','all_process','all_qmsheet'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'process_id' => 'required',
            'qmsheet_id' => 'required',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect('audit_cycle/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $aucyc = new Auditcycle;
            $aucyc->start_date = date("Y-m-d", strtotime($request->start_date));
            $aucyc->end_date = date("Y-m-d", strtotime($request->end_date));
            $aucyc->client_id = $request->client_id;
            $aucyc->process_id = $request->process_id;
            $aucyc->qmsheet_id = $request->qmsheet_id;
            $aucyc->name = $request->name;

            $aucyc->save();
            return redirect('audit_cycle/')->with('success', 'Audit cycle created successfully'); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Auditcycle  $auditcycle
     * @return \Illuminate\Http\Response
     */
    public function show(Auditcycle $auditcycle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Auditcycle  $auditcycle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Auditcycle::find(Crypt::decrypt($id));
        $all_clients = Client::all()->pluck('name','id');
        $all_process = Process::all()->pluck('name','id');
        $all_qmsheet = QmSheet::all()->pluck('name','id');
        return view('porter_design.audit_cycle.edit',compact('data','all_clients','all_process','all_qmsheet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Auditcycle  $auditcycle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'process_id' => 'required',
            'qmsheet_id' => 'required',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect('audit_cycle/create')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            $aucyc =  Auditcycle::find(Crypt::decrypt($id));
            $aucyc->start_date = date("Y-m-d", strtotime($request->start_date));
            $aucyc->end_date = date("Y-m-d", strtotime($request->end_date));
            $aucyc->client_id = $request->client_id;
            $aucyc->process_id = $request->process_id;
            $aucyc->qmsheet_id = $request->qmsheet_id;
            $aucyc->name = $request->name;
            $aucyc->save();
            return redirect('audit_cycle/')->with('success', 'Audit cycle updated successfully'); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Auditcycle  $auditcycle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auditcycle::find(Crypt::decrypt($id))->delete();
        return redirect('audit_cycle/')->with('success', 'Audit cycle deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');

use App\User;
use App\ScenerioTree;
use Auth;
use Crypt;
use App\Imports\ScanerioQuestionsImport;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\ScenarioCodeImport;

class ScenerioTreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scenario = ScenerioTree::get();
            return view('porter_design.scenario_code.list',compact('scenario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('porter_design.scenario_code.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Excel::import(new ScenarioCodeImport([
            'uploaded_by'=>Auth::user()->id,       
        ]),$request->import_file);
        
        return redirect('/scenerio_tree')->with('success', 'Data Uploaded Successfully');   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function questions_upload()
   {
  //  PATGD/ST/M01
     $scanerio_code = ScenerioTree::where('scenerio_code', 'PATGD/ST/M01')->first();
     //print_r($scanerio_code); die;
    $response = array();
               
       
      //  $response[$id] = $row[2];
    //$response = '{"2143":"Yes"}';
    $id = 2145;
    //echo $scanerio_code->questions_answers; die;
      
        
    
     return view('scenario_code.questions');
   }

   public function upload_questions(Request $request){

    $data = Excel::import(new ScanerioQuestionsImport([
      
     ]), $request->questions_file);

     return redirect('questions_upload')->with('success', 'Scanerio based question and answers uploaded successfully');  
   }
}

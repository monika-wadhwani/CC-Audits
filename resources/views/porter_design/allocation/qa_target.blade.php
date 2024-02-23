@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Upload QA Target</h3>
        </div>
        <a href="/allocation/qa_target"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    {!! Form::open(
                  array(
                    'route' => 'upload_qa_target',
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}
    <div class="tblM w-100 cardBox boxShaow px-3">
        <div class="titleBtm p-2">
            <span for="" style="color:blue;">QA Target xlsx*</span>
            <input type="file" class="form-control" name="file" required />
        </div><br>
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-secondary">Cancel</button>
    </div>
    </form>
    <span class="form-text text-muted">Max file size:- 50MB. File format:- .xlsx only</span>
    <a href="/qa_target_sample.xlsx" download="download">Sample Error Dump Uploader Sheet.</a>
</div>

@endsection

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('.datatables').DataTable();
    });
</script>
@endsection

@section('css')

@endsection
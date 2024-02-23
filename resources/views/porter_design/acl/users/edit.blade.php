@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
    <div class="container-fluid">
        <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="fw-bold mb-1 boxTittle">Edit</h3>
            </div>
            <a href="/user"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                    Back</button></a>
        </div>
        <div class="cardBox my-2 px-3">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::model($data, [
                        'method' => 'PATCH',
                        'url' => 'user/' . Crypt::encrypt($data->id),
                        'class' => 'kt-form',
                        'data-toggle' => 'validator',
                    ]) !!}
                    <div class=" mainTbl pe-1">
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput" class="form-label">Name*</label>
                                <input type="text" name="name" class="form-control" required
                                    value="{{ $data->name }}" id="exampleFormControlInput4" placeholder="Name">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput" class="form-label">Primary Email (as username)*</label>
                                <input type="text" name="email" class="form-control" required
                                    value="{{ $data->email }}" id="exampleFormControlInput4" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput" class="form-label">Mobile No.*</label>
                                <input type="text" name="mobile" class="form-control" required
                                    value="{{ $data->mobile }}" id="exampleFormControlInput4" placeholder="Email">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput" class="form-label">Roles*</label>
                                {{ Form::select('role_id[]', $roles, $rdata, ['class' => 'form-select customSelect m-select2', 'id' => 'kt_select2_1', 'multiple' => 'multiple', 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <h6>Masters Details</h6>
                                <label for="exampleFormControlInput4" class="form-label">Process</label>
                                {{ Form::select('selected_process[]', $process_data, $my_selected_processes, ['class' => 'form-control customSelect m-select2', 'id' => 'kt_select2_2', 'multiple' => 'multiple']) }}
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput4" class="form-label">Region</label>
                                {{ Form::select('selected_region[]', $region_data, $my_selected_regions, ['class' => 'form-control customSelect m-select2', 'id' => 'kt_select2_3', 'multiple' => 'multiple']) }}
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-50">
                                <label for="exampleFormControlInput4" class="form-label">Language</label>
                                {{ Form::select('selected_language[]', $language_data, $my_selected_languages, ['class' => 'form-control customSelect m-select2', 'id' => 'kt_select2_4', 'multiple' => 'multiple']) }}
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <h6>Cluster Hierarchy</h6>
                                <label for="exampleFormControlInput4" class="form-label">Select Parent Client</label>
                                <select class="form-control customSelect" multiple>
                                @foreach ($client_list as $value)
                                    <option value="{{ $value->id }}" <?php if ($allocated_client->parent_client == $value->id) {
                                        echo 'selected';
                                    } ?>>{{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput4" class="form-label">Select Partner</label>
                                {{ Form::select('partner[]', $partner_data, $my_selected_partners, ['class' => 'form-control customSelect m-select2', 'id' => 'kt_select2_5', 'multiple' => 'multiple']) }}
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput4" class="form-label">Select Location</label>
                                {{ Form::select('location[]', $location_data, $my_selected_location, ['class' => 'form-control customSelect m-select2', 'id' => 'kt_select2_10', 'multiple' => 'multiple']) }}
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput4" class="form-label">Select Process</label>
                                {{ Form::select('process[]', $process_data, $my_selected_process, ['class' => 'form-control customSelect m-select2', 'id' => 'kt_select2_7', 'multiple' => 'multiple']) }}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    @endsection

    @section('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        <script>
            $(".customSelect").select2({});
        </script>
        <script>
            $(document).ready(function() {
                $('.datatables').DataTable();
            });
        </script>
    @endsection

    @section('css')
    @endsection

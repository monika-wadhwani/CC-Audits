@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<style>
    .LblHeading {
        font-weight: 600;
        margin-bottom: 10px;
    }
</style>
@section('main')
<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Search/Filter</h3>
        </div>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(
                array(
                'route' => 'qa_allocation',
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                            <h6 class="LblHeading">Select Sample for allocation*</h6>
                            <select class="form-control" name="sample_id" required>
                                <option value="0">Select Sample</option>
                                @foreach($all_samples as $value)
                                <option value="{{$value->id}}">TLC IBC {{$value->created_at}}
                                    sample({{count($value->final_sample)}})</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <!-- <div class="form-contentMet">
                        <h6 class="LblHeading">Select Option For Calls Allocation To Auditors* </h6>
                        <div class="mb-3 w-50">
                            <div class="d-flex align-items-center">
                                <label for="equally" class="rdoLbl">
                                    <input type="radio" id="equally" name="materialExampleRadios" value="equal"
                                        onclick="hide()">
                                    <div class="pl-4">Equally Distribution</div>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3 w-50">
                            <div class="d-flex align-items-center">
                                <label for="uneually" class="rdoLbl">
                                    <input type="radio" id="uneually" name="materialExampleRadios" value="unqual"
                                        onclick="show()">
                                    <div class="pl-4">Unequal Distribution</div>
                                </label>
                            </div>
                        </div>

                    </div> -->
                    <div class="form-contentMet">
                        <h6 class="LblHeading">Select Option For Calls Allocation To Auditors* </h6>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="materialExampleRadios" id="equally"
                            value="equal" onclick="hide()">
                            <label class="form-check-label rdoLbl" for="equally">Equally Distribution</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="materialExampleRadios" id="uneually"
                                value="unqual" onclick="show()">
                            <label class="form-check-label rdoLbl" for="uneually">Unequal Distribution</label>
                        </div>

                    </div>
                    <div class="table-responsive w-100 mainTbl">
                        <table class="table mb-0 datatables">
                            <thead style="text-align: center">

                                <tr style="background-color:#d0d6df;">
                                    <th title="Field #1">#</th>

                                    <th title="Field #2">
                                        Auditor's Name
                                    </th>
                                    <th>Allocation</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($qa as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>

                                    <td>


                                        {{$row->name}}
                                    </td>

                                    <td>
                                        <input type="hidden" name="qa_id[]" value="{{$row->id}}">
                                        <input type="number" class="form-control allocated" name="allocated[]" min="0"
                                            disabled="disabled">
                                    </td>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div><br>
                <button type="submit" class="btn btn-primary btn-outline-brand">Submit</button>
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
    <script type="text/javascript">
        var start_date = '';
        var end_date = '';
        $(function () {
            $("#datepicker123").daterangepicker({
                opens: 'right'
            }, function (start, end, label) {
                start_date = start.format('YYYY-MM-DD');
                end_date = end.format('YYYY-MM-DD');
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
    </script>
    @endsection
    <script>
        function show() {


            var uneually = document.getElementById("uneually");
            var items = document.getElementsByClassName("allocated");

            //var items = document.getElementsByClassName(className);
            for (var i = 0; i < items.length; i++) {
                items[i].disabled = false;
            }
            // allocated.disabled = uneually.checked ? false : true;

        }

        function hide() {


            var uneually = document.getElementById("uneually");
            var items = document.getElementsByClassName("allocated");

            //var items = document.getElementsByClassName(className);
            for (var i = 0; i < items.length; i++) {
                items[i].disabled = true;
            }
            // allocated.disabled = uneually.checked ? false : true;

        }

    </script>
    @include('shared.form_js')

    @section('css')
    @endsection
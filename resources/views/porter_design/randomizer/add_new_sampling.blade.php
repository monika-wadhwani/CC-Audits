@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Sampling</h3>
        </div>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(
                array(
                'route' => 'save_sampling',
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
                <input type="hidden" name="sampling_id" value="{{$sampling_list->id}}">
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Name*</label>
                            <input type="text" name="name" class="form-control" value="{{$sampling_list->name}}"
                                required>
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Process*</label>
                            <input type="text" class="form-control" name="process" value="{{$all_process[0]->name}}"
                                readonly="readonly">
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Enter FTR %*</label>
                            <input type="number" class="form-control" name="ftr" value="{{$sampling_list->ftr}}"
                                min="0">
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Enter NFTR %*</label>
                            <input type="number" class="form-control" name="nftr" value="{{$sampling_list->nftr}}"
                                min="0">
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Top Volume Disposition*</label>
                            <input type="text" class="form-control" name="top_volume" value="{{$top_volume}}"
                                readonly="readonly">
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Enter Top Volume Disposition in
                                %*</label>
                            <input type="number" class="form-control" name="top_volume"
                                value="{{$sampling_list->top_volume}}" min="0">
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Enter VOC Range</label>
                            <select class="form-control" name="voc_type[]" multiple>
                                <option value="low" <?php if (gettype(array_search("low", $selected_voc)) != "boolean") {
                                    echo "selected";
                                } ?>>0-2</option>
                                <option value="medium" <?php if (gettype(array_search("medium", $selected_voc)) != "boolean") {
                                    echo "selected";
                                } ?>>3</option>
                                <option value="high" <?php if (gettype(array_search("high", $selected_voc)) != "boolean") {
                                    echo "selected";
                                } ?>>4-5</option>
                            </select>
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Enter Random Samples %</label>
                            <input type="number" class="form-control" name="random_samples" value = "{{$sampling_list->random_samples}}" min="0">
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                            <label for="exampleFormControlInput" class="form-label">Enter Low AHT In Secs*</label>
							<input type="number" class="form-control" name="low_aht" value = "{{$sampling_list->low_aht}}" min="0">
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Enter High AHT In Secs</label>
                            <input type="number" class="form-control" name="high_aht" value = "{{$sampling_list->high_aht}}" min="0">
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
    <script type="application/javascript">
        (function () {
            var base_url = window.location.origin;
            $.ajax({
                type: "GET",
                url: base_url + "/get_call_type",
                success: function (Data) {
                    var html = '';
                    for (var i = 0; i < Data.data1.length; i++) {
                        html += '<option value="' + Data.data1[i] + '">' + Data.data1[i] + '</option>';
                    }
                    // for(var i=0; i<Data.data2.length; i++){
                    // 	html2+='<option value="'+ Data.data2[i] +'">'+Data.data2[i] +'</option>';
                    // }
                    // for(var i=0; i<Data.data3.length; i++){
                    // 	html3+='<option value="'+ Data.data3[i] +'">'+Data.data3[i] +'</option>';
                    // }
                    $("#agents").html(html);
                    console.log(html);
                }
            });
        })();
        var $range = $(".js-range-slider"),
            $from = $(".from"),
            $to = $(".to"),
            range,
            min = $range.data('min'),
            max = $range.data('max'),
            from,
            to;

        var updateValues = function () {
            $from.prop("value", from);
            $to.prop("value", to);
        };

        $range.ionRangeSlider({
            onChange: function (data) {
                from = data.from;
                to = data.to;
                updateValues();
            }
        });

        range = $range.data("ionRangeSlider");
        var updateRange = function () {
            range.update({
                from: from,
                to: to
            });
        };

        $from.on("input", function () {
            from = +$(this).prop("value");
            if (from < min) {
                from = min;
            }
            if (from > to) {
                from = to;
            }
            updateValues();
            updateRange();
        });

        $to.on("input", function () {
            to = +$(this).prop("value");
            if (to > max) {
                to = max;
            }
            if (to < from) {
                to = from;
            }
            updateValues();
            updateRange();
        });
    </script>
    @include('shared.form_js')
    @endsection

    @section('css')
    @endsection
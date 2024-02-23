@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Upload Report</h4>
        <!-- <a href="{{url('skill/create')}}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a> -->
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">with call id</h5>
            <div class="d-flex mainSechBox">

                <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                    <img src="/assets/design/img/filter-icon.png" width="100%">
                </a>
            </div>
        </div>
        
        <div class="table-responsive w-100 mainTbl">
            <table class="table mb-0 datatables">
                <thead>
                    <tr>
						<th >#</th>
						<th >Zone</th>
                        <th >Location</th>
                        <th >Brand</th>
                        <th >Circle</th>
                        <th >week_1_target</th>
                        <th >week_2_target</th>
                        <th >week_3_target</th>
                        <th >week_4_target</th>
                        <th >MTD</th>
				</tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$row->zone}}</td>
                        <td>{{$row->location}}</td>
                        <td>{{$row->brand_name}}</td>
                        <td>{{$row->circle}}</td>
                        <td>{{$row->eq_audit_target_w1}}</td>
                        <td>{{$row->eq_audit_target_w2}}</td>
                        <td>{{$row->eq_audit_target_w3}}</td>
                        <td>{{$row->eq_audit_target_w4}}</td>
                        <td>{{$row->eq_audit_target_mtd}}</td>
                    </tr>
                @endforeach
        </tbody>
        </table>
    </div>
</div>

</div>

@endsection

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $('.datatables').DataTable();
    });
</script>
@endsection

@section('css')

<script>


"use strict";
var KTDatatablesExtensionButtons = function() {

	var initTable1 = function() {

		// begin first table
		var table = $('#kt_table_1').DataTable({
			responsive: true,
			scrollY: true,
			scrollX: true,
			scrollCollapse: true,
			// Pagination settings
			dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

			buttons: [
				{
					extend: 'excelHtml5',
					title: 'Targets'
				},
				{
					extend: 'pdfHtml5',
					title: 'Targets'
				},
				{
					extend: 'print',
					title: 'Targets'
				},
				{
					extend: 'copyHtml5',
					title: 'Targets'
				},
				{
					extend: 'pdfHtml5',
					title: 'Targets'
				}
			]

			
			
		});

	};

	var initTable2 = function() {

		// begin first table
		var table = $('#kt_table_2').DataTable({
			responsive: true,
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			buttons: [
				{
					extend: 'excelHtml5',
					title: 'Targets'
				},
				{
					extend: 'pdfHtml5',
					title: 'Targets'
				},
				{
					extend: 'print',
					title: 'Targets'
				},
				{
					extend: 'copyHtml5',
					title: 'Targets'
				},
				{
					extend: 'pdfHtml5',
					title: 'Targets'
				}
			],
			"order": [],
			"pageLength":100,
		});

		$('#export_print').on('click', function(e) {
			e.preventDefault();
			table.button(0).trigger();
		});

		$('#export_copy').on('click', function(e) {
			e.preventDefault();
			table.button(1).trigger();
		});

		$('#export_excel').on('click', function(e) {
			e.preventDefault();
			table.button(2).trigger();
		});

		$('#export_csv').on('click', function(e) {
			e.preventDefault();
			table.button(3).trigger();
		});

		$('#export_pdf').on('click', function(e) {
			e.preventDefault();
			table.button(4).trigger();
		});

	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
			initTable2();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesExtensionButtons.init();
});

</script>
@endsection
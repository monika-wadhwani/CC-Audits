<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->
<head>
	<meta charset="utf-8" />
	<title>QM Tool</title>
	<meta name="description" content="QuoteNow">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!--begin::Fonts -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script> -->

	<!--end::Fonts -->
	<style type="text/css">
		table tr td
		{
			text-align: justify;
		}
		
		
	</style>


	<!--begin::Page Vendors Styles(used by this page) -->
	
	{!! Html::style('assets/vendors/base/vendors.bundle.css') !!}

	<!--RTL version:{!! Html::style('assets/vendors/base/vendors.bundle.rtl.css') !!}-->
	{!! Html::style('assets/demo/default/base/style.bundle.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/base/style.bundle.rtl.css') !!}-->

	<!--end::Global Theme Styles -->

	<!--begin::Layout Skins(used by all pages) -->
	{!! Html::style('assets/demo/default/skins/header/base/light.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/skins/header/base/light.rtl.css') !!}-->
	{!! Html::style('assets/demo/default/skins/header/menu/light.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/skins/header/menu/light.rtl.css') !!}-->
	{!! Html::style('assets/demo/default/skins/brand/dark.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/skins/brand/dark.rtl.css') !!}-->
	{!! Html::style('assets/demo/default/skins/aside/dark.css') !!}

	<!--RTL version:{!! Html::style('assets/demo/default/skins/aside/dark.rtl.css') !!}-->

	<!--end::Layout Skins -->
	<link rel="shortcut icon" href="{{url('assets/media/logos/logo_default_dark.png')}}" />
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
		<div id="app">

		<div class="row">
			<div class="col-md-4">
				@if($data->client->logo)
				<img src="{{Storage::url('company/_'.$data->company_id.'/client/'.$data->client->logo)}}" width="80">
				@endif
			</div>
			<?php if(count($sheet_data) > 0){?>
			<div class="col-md-6">
				<?php 
				$selected_sheetid = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1);
				//dd($selected_sheetid);?>
				<select class="kt-font-brand kt-font-bolder" style="text-align: center; line-height: 60px;height: 33px;width: 50%;"  onchange="redirect(this.value);">
					<option value=''>Select Process</option>
				    @foreach ($sheet_data as $sheet)
				        <option value="{{ Crypt::encrypt($sheet->id) }}" @if (Crypt::decrypt($selected_sheetid) == $sheet->id)
					        selected="selected"
					    @endif  >{{ $sheet->name.'-'.$sheet->process['name'] }}</option>
				    @endforeach
				</select>
			</div>
			<?php } else {?>
			<div class="col-md-8">
				<h5 class="kt-font-brand kt-font-bolder" style="text-align: center; line-height: 60px;">{{$data->name}}</h5>
			</div>
			<?php } ?>
			<div class="col-md-2" style="padding-top: 17px;">
				<img src="/img/logo.png" width="100" align="right">
			</div>
		</div>
		<br/>
		<a href="/qa_dashboard/qa_dashboard_new" class="btn btn-primary"><i style="font-size: 12px;" class="fa fa-arrow-left"></i>Back</a>
		<form id="logout-form" action="{{ route('logout') }}" style="float: right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" method="POST" style="display: none;">
			<button href="javascript:void(0);" type="submit" class="btn btn-danger">Logout</button>
			{{ csrf_field() }}
		</form>
		<br/>
		<br/>
		@if(Auth::user()->assigned_sheet_id == 137)
		<porter-audit qm-sheet-id="{{$qm_sheet_id}}"
		       today-date="{{date('d-m-Y')}}"
		       auditor-name="{{Auth::user()->name}}"
		       auditor-id="{{Crypt::encrypt(Auth::user()->id)}}"></porter-audit>
		
		@else
		<audit qm-sheet-id="{{$qm_sheet_id}}"
		       today-date="{{date('d-m-Y')}}"
		       auditor-name="{{Auth::user()->name}}"
		       auditor-id="{{Crypt::encrypt(Auth::user()->id)}}"></audit>
		@endif

		</div>

	</div>
	<script>
		var KTAppOptions = {
			"colors": {
				"state": {
					"brand": "#5d78ff",
					"dark": "#282a3c",
					"light": "#ffffff",
					"primary": "#5867dd",
					"success": "#34bfa3",
					"info": "#36a3f7",
					"warning": "#ffb822",
					"danger": "#fd3995"
				},
				"base": {
					"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
					"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
				}
			}
		};
	</script>

	<script type="text/javascript" src="/js/app.js"></script>
	<!--begin::Global Theme Bundle(used by all pages) -->
	{!! Html::script('assets/vendors/base/vendors.bundle.js')!!}
	{!! Html::script('assets/demo/default/base/scripts.bundle.js')!!}
	{!! Html::script('assets/app/custom/general/dashboard.js')!!}
	<!--end::Page Scripts -->
	{!! Html::script('assets/app/bundle/app.bundle.js')!!}
	
	{!! Html::script('assets/app/custom/general/crud/forms/widgets/bootstrap-datetimepicker.js')!!}
	
	<script type="text/javascript">
	function redirect(value){
		
		  var sheet_id = value.trim();
	      var url = '/audit_sheet/'+sheet_id;
	      console.log(sheet_id);
	      if(value != ''){
	      window.location = url;
	      }
	}
	   
	</script>
	
</body>

<!-- end::Body -->
</html>
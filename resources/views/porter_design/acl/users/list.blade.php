@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">ACL</h4>

    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">Users</h5>
            <div class="d-flex mainSechBox">

                <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                    <img src="/assets/design/img/filter-icon.png" width="100%">
                </a>
            </div>
        </div>
        <hr>
        <form class="kt-form kt-form--fit kt-margin-b-20">
            <div class="row">
                <div class="col-lg-3">
                    <label>Role:</label>
                    <select class="form-control kt-input" data-col-index="2">
                        <option value="">Role</option>
                        @foreach($roles as $kk=>$vv)
                        <option value="{{$vv}}">{{$vv}}</option>
                        @endforeach
                    </select>
                </div>
            </div><br>
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary btn-brand--icon">
                        <span>
                            <i class="la la-search"></i>
                            <span>Search</span>
                        </span>
                    </button>
                    &nbsp;&nbsp;
                    <button class="btn btn-secondary btn-secondary--icon">
                        <span>
                            <i class="la la-close"></i>
                            <span>Reset</span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <hr><br>
        <div class="table-responsive w-100 mainTbl">
            <table class="table mb-0 datatables">
                <thead>
                    <tr>
                        <th title="Field #1">#</th>
                        <th title="Field #2">
                            Name
                        </th>
                        <th title="Field #3">
                            Role
                        </th>
                        <th title="Field #4">
                            Email
                        </th>
                        <th title="Field #5">
                            Phone
                        </th>
                        <th title="Field #6">
                            Status
                        </th>
                        <th title="Field #7">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            {{$row->name}}
                        </td>
                        <td>
                            @foreach($row->roles as $rrs)
                            {{$rrs->display_name.","}}
                            @endforeach
                        </td>
                        <td>{{$row->email}}</td>
                        <td>{{$row->mobile}}</td>
                        <td>
                            @if($row->status == 1)
                            <a href="{{url('user/status/'.Crypt::encrypt($row->id).'/99')}}">
                                <span class="kt-badge kt-badge--danger kt-badge--inline">Disable Now</span>
                            </a>
                            @else
                            <a href="{{url('user/status/'.Crypt::encrypt($row->id).'/1')}}">
                                <span class="kt-badge kt-badge--success kt-badge--inline">Enable Now</span>
                            </a>
                            @endif
                        </td>
                        <td nowrap>
                            <!-- <div style="display: flex;">
                            {{Form::open([ 'method'  => 'delete', 'route' => [ 'user.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                            <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                                <i class="la la-trash"></i>
                            </button>
                        </form> -->
                            <a href="{{url('user/'.Crypt::encrypt($row->id).'/edit')}}"
                                class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

        </div>

        </td>
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
<script>
    $(document).ready(function () {
        $('.datatables').DataTable();
    });
</script>
@endsection

@section('css')

@endsection
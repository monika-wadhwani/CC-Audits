<?php 
  $userDetails = \App\User::where("id", Auth::user()->id)->with('roles')->first();
        if (!$userDetails) {
            return redirect('error')->with('warning','Please set client details');
        }
        $agentNotify = $userDetails->unreadnotifications;
        $notify['agent_details'] = $userDetails;

?>
<div class="container-fluid">
<div class="titlTops cardBox my-3 d-flex justify-content-between align-items-center">
    <h3 class="fw-bold mb-1 boxTittle">Welcome Back <span class="text-primary">{{$userDetails->name}}</span></h3>
    <div class="d-flex profilePills align-items-center">
        <a href="#" class="border-start" data-bs-toggle="dropdown"><img src="{{asset('/assets/design/img/notification.png')}}"
                class="mx-3" alt="notification" width="26px" height="26px">
        </a>
        <div class="dropdown-menu noutificationmn">
            <ul class="p-0">
                <h6 class="headingToday">Today</h6>
                @foreach($agentNotify as $value)
                <li class="unread"><a  class="dropdown-item markAsReadLink" target="_blank" href="{{$value['data']['url']}}">
                    <input type="hidden" name="notification_id" class="notification_id" value={{$value->id}}>
                        <div class="d-flex justify-content-between">
                            <h6>{{$value['data']['upper_text'] ?? ''}}</h6>
                            <span class="text-end">{{$value['data']['audit_id'] ?? ''}}</span>
                        </div>
                        <p>{{$value['data']['description'] ?? ''}}</p>
                    </a>
                </li> 
                @endforeach
            </ul>
        </div>
        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="border-start"><img src="{{asset('/assets/design/img/on.png')}}" class="mx-3" alt="switch" width="26px"
                height="26px"></a>
            @if(Auth::user()->avatar)
                <img src="{{asset('/assets/design/img/'.Auth::user()->avatar)}}" width="40px" class="profileImg" height="40px" alt="img">
            @else
                <img src="{{asset('/assets/design/img/user.png')}}" width="40px"  height="40px" alt="img">
        @endif
        <a href="#" class="ms-2 userUs">
            <h6 class="mb-0 text-black fw-bold fs-13">{{$userDetails->name}}</h6>
            <p class="text-black-50">{{$userDetails->email}}</p>
        </a>
    </div>
</div>
</div>
<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $(".markAsReadLink").click(function (e) {
            e.preventDefault();
           var notification_id = $('.notification_id').val();
            $.ajax({
                url: '<?php echo URL::to('notification/markAsRead'); ?>/',
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    val: notification_id,
                },
                success: function(res) {
                    console.log("Notification marked as read.");
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
         
        });
    });
</script>

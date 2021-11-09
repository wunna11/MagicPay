@extends('frontend.layouts.app')

@section('title', 'Notification')

@section('content')
<div class="notification">
    <div class="scrolling-pagination">
        @foreach ($notifications as $notification)
        <a href="{{ route('noti.show', $notification->id) }}" style="text-decoration: none">
        
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6><b><i class="far fa-envelope @if(is_null($notification->read_at)) text-danger @endif"></b></i>
                            {{ $notification->data['title'] }}</h6>
                    </div>
               
                    <p>
                      {{ Illuminate\Support\Str::limit($notification->data['message'], 30) }}
                    </p>
                    <small class="text-muted">
                        {{ date('d-m-y / H:i:s', strtotime($notification->created_at)) }}
                    </small>
                </div>
            </div>
        </a>
        
        @endforeach

        {{ $notifications->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
        $('ul.pagination').hide();
        $(function() {
            $('.scrolling-pagination').jscroll({
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });

        // $('.date').daterangepicker({
        //     "singleDatePicker": true,
        //     "autoApply": true,
        //     "locale": {
        //         "format": "YYYY-MM-DD",
        //     },
        // });

        // $('.date').on('apply.daterangepicker', function(ev, picker) {
        //     var date = $('.date').val();
        //     var type = $('.type').val();
        //     history.pushState(null, '', `?date=${date}&type=${type}`);
        //     window.location.reload();
        // });

        // $('.type').change(function () {
        //     var date = $('.date').val();
        //     var type = $('.type').val();
        //     history.pushState(null, '', `?date=${date}&type=${type}`);
        //     window.location.reload();
        // })  
</script>
@endsection
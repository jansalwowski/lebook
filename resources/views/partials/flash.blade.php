{{--{{ dd(Session::get('flash.error')) }}--}}

<alert
        :show.sync="alerts.success.show"
        :duration="4000"
        type="success"
        width="400px"
        placement="top-right"
        dismissable
        v-cloak
>
    <span class="icon-ok-circled alert-icon-float-left"></span>
    <strong>Success!</strong>
    <p>@{{{ alerts.success.message }}}</p>
</alert>

<alert
        :show.sync="alerts.danger.show"
        :duration="5000"
        type="danger"
        width="400px"
        placement="top-right"
        dismissable
        v-cloak
>
    <span class="icon-danger-circled alert-icon-float-left"></span>
    <strong>Error!</strong>
    <p>@{{{ alerts.danger.message }}}</p>
</alert>

<alert
        :show.sync="alerts.info.show"
        :duration="5000"
        type="info"
        width="400px"
        placement="top-right"
        dismissable
        v-cloak
>
    <span class="icon-info-circled alert-icon-float-left"></span>
    <strong>Heads up!</strong>
    <p>@{{{ alerts.info.message }}}</p>
</alert>

<alert
        :show.sync="alerts.warning.show"
        :duration="5000"
        type="info"
        width="400px"
        placement="top-right"
        dismissable
        v-cloak
>
    <span class="icon-warning-circled alert-icon-float-left"></span>
    <strong>Heads up!</strong>
    <p>@{{{ alerts.warning.message }}}</p>
</alert>


@if(Session::has('flash.success'))
    @foreach(Session::get('flash.success') as $message)
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! $message !!}
        </div>
    @endforeach
    <?php Session::forget('flash.success') ?>
@endif

@if(Session::has('flash.error'))
    @foreach(Session::get('flash.error') as $index => $message)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! $message !!}
        </div>
    @endforeach
    <?php Session::forget('flash.error') ?>
@endif

@if(Session::has('flash.warning'))
    @foreach(Session::get('flash.warning') as $message)
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! $message !!}
        </div>
    @endforeach
    <?php Session::forget('flash.warning') ?>
@endif

@if(Session::has('flash.info'))
    @foreach(Session::get('flash.info') as $message)
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! $message !!}
        </div>
    @endforeach
    <?php Session::forget('flash.info') ?>
@endif

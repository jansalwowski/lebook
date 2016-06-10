<div class="panel panel-default">
    <div class="panel-heading">
        <h4>Write a post!</h4>
    </div>

    <div class="panel-body">

        {!! Form::open(['method'=>'post', 'action' => 'PostsController@store', 'class' => 'post-form']) !!}

        <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
            {!! Form::textarea('body', null, ['class' => 'form-control', 'placeholder' => 'Write here what you are thinking about now...', 'required', 'rows' => 3]) !!}
            {!! $errors->first('body', '<p class="help-block error-msg">:message</p>') !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Submit', ['class' => 'btn btn-sm btn-primary pull-right']) !!}
        </div>

        {!! Form::close() !!}

    </div>

</div>

@foreach($wall as $post)
    @include('posts.post')
@endforeach
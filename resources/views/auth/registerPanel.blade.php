<div class="panel panel-default">
    <div class="panel-heading">Register</div>
    <div class="panel-body">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>{{ trans('messages.warning') }}</strong><br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('reg_username') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Username</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" name="reg_username" value="{{ old('reg_username') }}">

                    @if ($errors->has('reg_username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('reg_username') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('reg_name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Name</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" name="reg_name" value="{{ old('reg_name') }}">

                    @if ($errors->has('reg_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('reg_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('reg_email') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">E-Mail Address</label>

                <div class="col-md-6">
                    <input type="email" class="form-control" name="reg_email" value="{{ old('reg_email') }}">

                    @if ($errors->has('reg_email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('reg_email') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('reg_password') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Password</label>

                <div class="col-md-6">
                    <input type="password" class="form-control" name="reg_password">

                    @if ($errors->has('reg_password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('reg_password') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('reg_password_confirmation') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Confirm Password</label>

                <div class="col-md-6">
                    <input type="password" class="form-control" name="reg_password_confirmation">

                    @if ($errors->has('reg_password_confirmation'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('reg_password_confirmation') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-btn fa-user"></i>Register
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
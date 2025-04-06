@extends('welcome')

@section('content')

<div class="jumbotron jumbotron-blue">
<div class="container">
<h1 class="text-center"><i class="fa fa-user"></i> @lang('auth.resetPassword')</h1>
<div class="text-center">@lang('auth.resetPasswordText')</div><!-- /.text-center -->
</div><!-- /.container -->
</div><!-- /.jumbotron -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">@lang('auth.email')</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang( 'auth.resetPassword' )
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br/><br/><br/><br/>
@endsection

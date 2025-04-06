@extends('account')

@section('seo_title')
    @lang('dashboard.accountSettings') -
@endsection

@section('account_section')
    <div>
        <form method="POST" action="{{ route('saveAccountSettings') }}">
            @csrf
            <div class="shadow-sm card add-padding">

                <br />
                <h2 class="ml-2"><i class="fa fa-cog mr-2"></i>@lang('dashboard.accountSettings')</h2>
                @lang('profile.profileSettingsText')
                <hr>

                <div class="row">
                    <div class="col-sm-8 col-12">
                        <label><strong>@lang('dashboard.yourName')</strong></label><br>
                        <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-8 col-12">
                        <label><strong>@lang('profile.email')</strong></label><br>
                        <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}"
                            required>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-8 col-12">
                        <label><strong>New Password</strong> <small>@lang('profile.leaveEmpty')</small></label><br>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-8 col-12">
                        <label><strong>Confirm New Password</strong> <small>@lang('profile.leaveEmpty')</small></label><br>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

            </div><!-- /.white-bg -->
            <br>

            <div class="text-center">
                <input type="submit" name="sbStoreProfile" class="btn btn-lg btn-primary" value="@lang('profile.saveAccountSettings')">
            </div><!-- /.white-bg add-padding -->

        </form>
        <br /><br />
    </div><!-- /.white-smoke-bg -->
@endsection

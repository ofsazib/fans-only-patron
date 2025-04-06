@extends('welcome')

@section('seo_title') @lang('v14.report-content') - @endsection

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 offset-0 offset-sm-0 offset-md-2">
            <div class="card shadow p-3">
            <h3>@lang('v14.report-content')</h3>
            <hr>
            <div class="alert alert-secondary">@lang('v14.report-content-description').</div>

            <form method="POST" action="{{ route('storeReport') }}" name="report-content-form">
                @csrf

                <div class="d-none">
                    <input type="text" name="the_field" />
                </div>

                <strong><label>@lang('v14.your-name')</label></strong>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6">
                        <input type="text" name="reporter_name" placeholder="@lang('v14.anonymous')" class="form-control" value="{{old('reporter_name')}}"/>
                    </div>
                </div>
                <br>

                <strong><label>@lang('v14.your-email')</label></strong>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6">
                        <input type="text" name="reporter_email" placeholder="@lang('v14.your-email')" class="form-control" value="{{old('reporter_email')}}"/>
                    </div>
                </div>
                <br>

                <strong><label>@lang('v14.reported_url')</label></strong>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <input type="text" name="reported_url" placeholder="{{ route('home') }}/...." class="form-control" value="{{old('reported_url')}}"/>
                    </div>
                </div>
                <br>

                <strong><label>@lang('v14.your_message')</label></strong>
                <div class="row">
                    <div class="col-12">
                        <textarea name="report_message" class="form-control" rows="6"/>{{old('report_message')}}</textarea>
                    </div>
                </div>

                <br>

                <strong><label>@lang('v14.math_question') {{ $no1 . '+' . $no2 }}?</label></strong>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-2">
                        <input type="number" name="reported_math" class="form-control" value="{{old('reported_math')}}" />
                    </div>
                </div>

                <br>

                <input type="submit" name="reportSbtn" value="@lang('v14.submit-report')" class="btn btn-danger">
                    

            </form>

            </div>
        </div>
    </div>
</div>
@endsection
@extends( 'welcome' )

@section( 'content' )
<div class="white-smoke-bg">
<br/>

<div class="container">
<div class="row">

<div class="col-md-4 d-block d-sm-none mb-3">
<a class="btn btn-dark mt-4" data-toggle="collapse" href="#mobileAccountNavi" role="button" aria-expanded="false" aria-controls="collapseExample">
    <i class="fas fa-list mr-1"></i> @lang('navigation.accountNavigation')
  </a>
<div class="collapse mt-2" id="mobileAccountNavi">
    @include( 'partials/dashboardnavi' )
</div>
</div><!-- /.col-md-3 -->

<div class="col-md-8">
@yield( 'account_section' )
</div><!-- /.col-md-8 -->

<div class="col-md-4 d-none d-sm-block">
@include( 'partials/dashboardnavi' )
</div><!-- /.col-md-3 -->

</div><!-- ./row ( main ) -->
</div><!-- /.container -->
</div>

@endsection
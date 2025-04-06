@extends('admin.base')


@section('extra_top')
<div class="row">
<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3>{{ $totalVendors }}</h3>
      <p>Creators</p>
    </div>
    <div class="icon">
      <i class="fa fa-users"></i>
    </div>
  </div>
</div>
<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-red">
    <div class="inner">
      <h3>{{ $payingFans }}</h3>
      <p>Subscribers</p>
    </div>
    <div class="icon">
      <i class="fa fa-money"></i>
    </div>
  </div>
</div>

<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-gray">
    <div class="inner">
      <h3>{{ $totalTips }}</h3>
      <p>Total Tips</p>
    </div>
    <div class="icon">
      <i class="fa fa-money"></i>
    </div>
  </div>
</div>

<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-aqua">
    <div class="inner">
      <h3>{{ $totalUsers }}</h3>
      <p>Total Users</p>
    </div>
    <div class="icon">
      <i class="fa fa-shopping-cart"></i>
    </div>
  </div>
</div>
<div class="col-lg-2 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-green">
    <div class="inner">
      <h3>{{ opt('payment-settings.currency_symbol') . number_format($monthEarnings,2) }}</h3>
      <p>Month Income</p>
    </div>
    <div class="icon">
      <i class="fa fa-money"></i>
    </div>
  </div>
</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header with-border"><strong>Past 30 Days</strong></div>
			<div class="box-body">
				<!-- LINE CHART -->
				<div class="chart-responsive">
          <div class="chart" id="past-30-days"></div>
          {{-- attention, this is dynamically appended as it contains data from database --}}
				  <script>
				  new Morris.Line({
					  // ID of the element in which to draw the chart.
					  element: 'past-30-days',
					  // Chart data records -- each entry in this array corresponds to a point on
					  // the chart.
					  data: [
					  	@if( isset($earnings) AND count($earnings) )
                @foreach( $earnings as $d )
								{ 
                  date: '{{ $d['date'] }}', 
                  earnings: '{{  $d['total'] }}', 
                  platform: '{{  $d['platform'] }}', 
                  creators: '{{  $d['creators'] }}', 
                  fans: {{ $d['fansCount'] }},
                  tips: {{ $d['tipsCount'] }},
                  unlocks: {{ $d['unlocksCount'] }}
                },
						    @endforeach
					    @else
					    	{ date: '{{ date( 'jS F Y' ) }}', earnings: 0, fans: 0, platform: 0, creators: 0, tips: 0, unlocks: 0 }
					    @endif
					  ],
					  // The name of the data record attribute that contains x-values.
					  xkey: 'date',
					  // A list of names of data record attributes that contain y-values.
            ykeys: ['earnings', 'platform', 'creators', 'fans', 'tips', 'unlocks'],
					  // Labels for the ykeys -- will be displayed when you hover over the
					  // chart.
					  labels: ['Income', 'Platform Earnings', 'Creators Earnings', 'Subscriptions', 'Tips', 'Message Unlocks']
				  });
				  </script>

				</div><!-- subscription earnings -->
		</div>
	</div>
</div>
@endsection

@section('section_title')
	<strong>All Users</strong>
@endsection

@section( 'section_body' )

<a href="/admin/users" class="btn btn-primary">View Users</a>

@endsection
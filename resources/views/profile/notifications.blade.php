@extends( 'account' )

@section('seo_title') @lang('navigation.myNotifications') - @endsection

@section( 'section_title' )
<i class="fa fa-code"></i> @lang( 'navigation.myNotifications' )
@endsection

@section( 'account_section' )

@yield( 'account_section' )

@livewire('notifications-page')

@endsection
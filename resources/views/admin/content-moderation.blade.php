@extends('admin.base')


@section('extra_top')
<div class="col-xs-12">
<div class="box">
    <div class="box-header with-border"><strong>Report form entries</strong></div>
    <div class="box-body">

    <table class="table dataTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>URL <small>only click if it's your own domain</small></th>
            <th>Message</th>
            <th>IP</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reports as $r)
        <tr>
            <td>{{ $r->reporter_name }}</td>
            <td><a href="mailto:{{ $r->reporter_email }}">{{ $r->reporter_email }}</a></td>
            <td>
                <a href="{{ $r->reported_url }}" target="_blank">
                    {{ $r->reported_url }}
                </a>
            </td>
            <td>{{ empty($r->report_message) ? '--' : $r->report_message }}</td>
            <td>{{ $r->reporter_ip }}</td>
            <td>
                <a href="/admin/content-moderation/delete-report/{{ $r->id }}" class="text-danger" onclick="return confirm('Delete report?')">
                    Delete
                </a>
            </td>
        </tr>
        @empty

        @endforelse
    </tbody>
    </table>

    </div>
</div>
</div>
@endsection


@section('section_title')
	<strong>Content Moderation</strong>
@endsection


@section('section_body')

    - note : you can login as any of these <a href="/admin/users">admin &raquo; users</a> to edit their content - <br>
    <br>

    
    <ul class="nav nav-tabs" role="tablist">
        <li @if($content_type == 'Image') class="active" @endif>
            <a href="/admin/moderation/Image">Images ({{$counts['image']}})</a>
        </li>
        <li @if($content_type == 'Video') class="active" @endif>
            <a href="/admin/moderation/Video">Videos ({{$counts['video']}})</a>
        </li>
        <li @if($content_type == 'Audio') class="active" @endif>
            <a href="/admin/moderation/Audio">Audios ({{$counts['audio']}})</a>
        </li>
        <li @if($content_type == 'ZIP') class="active" @endif>
            <a href="/admin/moderation/ZIP">ZIP Files ({{$counts['zip']}})</a>
        </li>
        <li @if($content_type == 'None') class="active" @endif>
            <a href="/admin/moderation/None">Text Posts ({{$counts['text']}})</a>
        </li>
    </ul>

    <div class="table-responsive">
    <table class="table table-responsive">
    <tr>
        <td>ID</td>
        <td>User</td>
        <td>Lock</td>
        <td>Date</td>
        <td>Text</td>
        <td>Media</td>
        <td>Delete</td>
    </tr>
    @forelse($contents as $p) 
    <tr>
        <td>{{ $p->id }}</td>
        <td>
            <a href="{{ route('profile.show', ['username' => $p->profile->username]) }}" target="_blank">
                {{ $p->profile->handle }}
            </a>
        </td>
        <td>
            @if($p->lock_type == 'Paid')
                <span class="text text-danger">Locked</span>
            @else
                <span class="text text-success">Free</span>
            @endif
        </td>
        <td>
            {{ $p->created_at->format('jS F Y')}}<br>
            {{ $p->created_at->format('H:i a')}}
        </td>
        <td>
            <article style="max-height: 200px; overflow: scroll;">
                {!! clean($p->text_content) !!}
            </article>
        </td>
        <td>
            @if($p->media_type == 'Image')
                
                @if( $p->disk == 'backblaze' )
                    <a href="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content}}">
                        <img src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content}}" alt="" class="img-responsive" width="200"/>
                    </a>
                @else
                    <a href="{{ \Storage::disk($p->disk)->url($p->media_content) }}" data-toggle="lightbox">
                        <img src="{{ \Storage::disk($p->disk)->url($p->media_content) }}" alt="" class="img-responsive" width="200"/>
                    </a>
                @endif

            @elseif($p->media_type == 'Video') 

                @if( $p->disk == 'backblaze' )
                    <video width="420" height="340" controls controlsList="nodownload" disablePictureInPicture>
                        <source src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content}}" type="video/mp4" />
                    </video>
                @else
                    <video width="420" height="340" controls controlsList="nodownload" disablePictureInPicture>
                        <source src="{{ \Storage::disk($p->disk)->url($p->video_url) }}" type="video/mp4" />
                    </video>
                @endif

            @elseif($p->media_type == 'Audio') 

                @if( $p->disk == 'backblaze' )
                    <audio class="w-100 mb-4" controls controlsList="nodownload">
                        <source src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content}}" type="audio/mp3" />
                    </audio>
                @else
                    <audio class="w-100 mb-4" controls controlsList="nodownload">
                        <source src="{{ \Storage::disk($p->disk)->url($p->audio_url) }}" type="audio/mp3">
                    </audio>
                @endif

            @elseif($p->media_type == 'ZIP')

                @if( $p->disk == 'backblaze' )
                    <a href="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $p->media_content}}">
                        Download ZIP
                    </a>
                @else
                    <a href="{{ \Storage::disk($p->disk)->url($p->media_content) }}" data-toggle="lightbox">
                        Download ZIP
                    </a>
                @endif
                
            @else
                No media
            @endif
        </td>
        <td>
            <a href="/admin/moderation/{{ $content_type }}?delete={{ $p->id }}" class="text-danger" onclick="return confirm('Confirm delete?')">
                Delete
            </a>
        </td>
    </tr>
    @empty
        There are no contents of this type in database.
    @endforelse
    </div>
    </table>

    {{$contents->links()}}

@endsection
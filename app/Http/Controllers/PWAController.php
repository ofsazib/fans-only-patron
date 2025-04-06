<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PWAController extends Controller
{
    // return manifest.json
    public function manifest()
    {
        return response()->json($this->_generate());
    }

    // offline route
    public function offline(){
        return view('pwa/offline');
    }

    // generate manifest from config
    public function _generate()
    {

        // general pwa info
        $basicManifest =  [
            'name' => config('pwa.manifest.name'),
            'short_name' => opt('laravel_short_pwa', 'FansApp'),
            'start_url' => asset(config('pwa.manifest.start_url')),
            'display' => config('pwa.manifest.display'),
            'theme_color' => config('pwa.manifest.theme_color'),
            'background_color' => config('pwa.manifest.background_color'),
            'orientation' =>  config('pwa.manifest.orientation'),
            'status_bar' =>  config('pwa.manifest.status_bar'),
            'splash' =>  config('pwa.manifest.splash')
        ];

        foreach (config('pwa.manifest.icons') as $size => $file) {

            // set cache name for each icon
            $cacheName = 'pwa_' . $size;

            // if we have it into cache, get it from there
            if(Cache::has($cacheName)) {

                $filePath = Cache::get($cacheName);

            }elseif(opt($cacheName)){

                // if we have it into database, get it from there
                $filePath = opt($cacheName);

                // cache it for 31 days - cache will be reset if new icon will be uploaded via admin panel
                Cache::put($cacheName, $filePath, 24*3600*31);

            }else{

                // otherwise use the default one
                $filePath = $file['path'];
            }

            $fileInfo = pathinfo($filePath);

            $basicManifest['icons'][] = [
                'src' => $filePath,
                'type' => 'image/' . $fileInfo['extension'],
                'sizes' => $size,
                'purpose' => $file['purpose']
            ];
        }

        if (config('pwa.manifest.shortcuts')) {
            foreach (config('pwa.manifest.shortcuts') as $shortcut) {

                if (array_key_exists("icons", $shortcut)) {
                    $fileInfo = pathinfo($shortcut['icons']['src']);
                    $icon = [
                        'src' => $shortcut['icons']['src'],
                        'type' => 'image/' . $fileInfo['extension'],
                        'purpose' => $shortcut['icons']['purpose']
                    ];
                } else {
                    $icon = [];
                }

                $basicManifest['shortcuts'][] = [
                    'name' => trans($shortcut['name']),
                    'description' => trans($shortcut['description']),
                    'url' => $shortcut['url'],
                    'icons' => [
                        $icon
                    ]
                ];
            }
        }

        foreach (config('pwa.manifest.custom') as $tag => $value) {
             $basicManifest[$tag] = $value;
        }
        return $basicManifest;
    }
}

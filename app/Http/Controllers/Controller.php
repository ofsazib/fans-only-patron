<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function lchecker($l, $domain)
    {
		return 'LICENSE_VALID_AUTOUPDATE_ENABLED'; 
        // call url for licensing
        $url = 'http://crivion.com/envato-licensing/index.php';

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'license_code=' . $l . '&blogURL=' . $domain . '&product=PHP+FansOnly');
        curl_setopt($ch, CURLOPT_USERAGENT, 'crivion/envato-license-checker-v1.0');

        //execute post
        $result = curl_exec($ch);

        if($err = curl_error($ch) AND !empty($ch))
            return 'LICENSE_VALID_AUTOUPDATE_ENABLED';

        //close connection
        curl_close($ch);

        return $result;
    }

    public function activate_product()
    {
        $r = request();

        $this->validate($r, ['license' => 'required', 'domain' => 'required|url']);

        $result = self::lchecker($r->license, $r->domain);

        //if LICENSE_VALID_AUTOUPDATE_ENABLED
        if ($result == 'LICENSE_VALID_AUTOUPDATE_ENABLED') {

            // add license key to db
            setopt('lk', $r->license);
            alert()->success('Successfully validated. You can now continue using the product!');

            return redirect(route('home'));
        } else {

            alert()->warning($result);
            return back();
        }
    }
}


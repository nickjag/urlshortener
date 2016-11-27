<?php

namespace App\Http\Controllers;

use App\Short_url;
use App\Target_url;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index(Request $request, $code) {

    	// Check if Short URL exists

        if (empty($errors)) {

        	$short = Short_url::where([
				    ['code', '=', $code]
				])->first();

        	if ($short === null) {
        		
        		array_push($errors, array('code'=>'That shortened URL was not found.'));
        	}
        }

        // Get Device

        if (empty($errors)) {

        	$agent = new Agent();

        	if ($agent->isMobile()) {
        		
        		$target_id = $short->url_mobile;
        	}
        	else if ($agent->isTablet()) {
        		
        		$target_id = $short->url_tablet;
        	}
        	else {
        		
        		$target_id = $short->url_desktop;
        	}
        }

        // Get Target URL

        if (empty($errors)) {

			$target = Target_url::where('id', '=', $target_id)->first();

			if ($target === null) {
				
				array_push($errors, array('target'=>'Error getting target URL.'));
			}
        }

        // Return Success or Errors

    	if (empty($errors)) {
    		
    		return redirect($target->url);
    	}
    	else {
    		
    		return response()->json([
		        'success' => false,
		        'message' => $errors
		    ], 422);
    	}
    }
}

<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use App\Short_url;
use App\Target_url;
use Illuminate\Http\Request;

class InterfaceController extends Controller
{
	// Display all URLs

    public function index() {

    	$records = get_records();

    	$output = array();

    	foreach ($records as $record) {
    		
    		array_push($output, format_output_record($record));
    	}

    	return response()->json($output, 200);
    }

    // Display URLs by User Id

    public function show($id) {

		$records = get_records($id);

    	$output = array();

    	foreach ($records as $record) {
    		
    		array_push($output, format_output_record($record));
    	}

    	return response()->json($output, 200);
    }

    // Save/create a short url from target url and user id

    public function store(Request $request) {

    	$errors = array();

    	// Validate Data

    	$validation = Validator::make($request->all(), [
            'user' => 'required|integer',
            'target' => 'required|url'
        ]);

        if (!($validation->passes())) {
	    	
	    	$errors =  json_decode($validation->errors());
        }

		// Check for and Save Target URL

        if (empty($errors)) {

			$target = Target_url::where('url', '=', $request->target)->first();

			if ($target === null) {
				
				// Create a New Target
				
				$target = new Target_url;
				$target->url = $request->target;
				$target->redirects = 1;
				
				if (!($target->save())) {
					
					array_push($errors, array('target'=>'Error saving target URL.'));
				}
			}
			else {

				// Increment Existing Target

				$target->increment('redirects');
			}
        }

        // Save Short URL

    	if (empty($errors)) {

    		$short = new Short_url;

	    	$short->code = create_short_code();
	    	$short_url = create_short_url($short->code);

	        $short->url_mobile = $target->id;
	        $short->url_tablet = $target->id;
	        $short->url_desktop = $target->id;

	        $short->user = $request->user;
	        
	        if (!($short->save())) {
	        	
	        	array_push($errors, array('short'=>'Error saving short URL.'));
	        }
    	}

    	// Return Success or Errors

    	if (empty($errors)) {
    		
    		return response()->json([
                'success' => true,
                'short' => $short_url
            ], 200);
    	}
    	else {
    		
    		return response()->json([
		        'success' => false,
		        'message' => $errors
		    ], 422);
    	}
    }

    // Update a target url based on short url, user id and device type

    public function update(Request $request) {

    	$errors = array();

    	// Validate Data

    	$validation = Validator::make($request->all(), [
            'user' => 'required|integer',
            'device' => 'required|string',
            'short' => 'required|url',
            'target' => 'required|url'
        ]);

        if (!($validation->passes())) {
	    	
	    	$errors = json_decode($validation->errors());
        }

        // Check device type string

        if (empty($errors)) {

        	if (!(in_array($request->device, array('mobile','tablet','desktop')))) {
        		
        		array_push($errors, array('device'=>'Provide a valid device: mobile, tablet, or desktop.'));
        	}
        }

        // Check if Short Code exists

        if (empty($errors)) {

        	if (!($short_code = get_code($request->short))) {
        		
        		array_push($errors, array('code'=>'Invalid shortened URL'));
        	}
        }

        // Check if Short URL exists

        if (empty($errors)) {

        	$short = Short_url::where([
				    ['code', '=', $short_code],
				    ['user', '=', $request->user],
				])->first();

        	if ($short === null) {
        		
        		array_push($errors, array('code'=>'That shortened URL was not found.'));
        	}
        }

        // Check for and Save Target URL

        if (empty($errors)) {

			$target = Target_url::where('url', '=', $request->target)->first();

			if ($target === null) {
				
				// Create a New Target
				
				$target = new Target_url;
				$target->url = $request->target;
				$target->redirects = 1;
				
				if (!($target->save())) {
					
					array_push($errors, array('target'=>'Error saving target URL.'));
				}
			}
			else {

				// Increment Existing Target

				$target->increment('redirects');
			}
        }

        // Save Short URL

        if (empty($errors)) {

        	$device_target = array();

        	switch ($request->device) {
        		
        		case 'mobile':
        			$device_column = 'url_mobile';
        			break;
        		case 'tablet':
        			$device_column = 'url_tablet';
        			break;
        		case 'desktop':
        			$device_column = 'url_desktop';
        			break;
        	}
	        
	        if (!($short->update([$device_column => $target->id]))) {
	        	
	        	array_push($errors, array('short'=>'Error updating target URL'));
	        }
    	}

    	// Return Success or Errors

    	if (empty($errors)) {
    		
    		return response()->json([
                'success' => true
            ], 200);
    	}
    	else {
    		
    		return response()->json([
		        'success' => false,
		        'message' => $errors
		    ], 422);
    	}

    }
}

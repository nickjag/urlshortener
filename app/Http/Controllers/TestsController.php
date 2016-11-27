<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestsController extends Controller
{

	// Test Store Request (e.g. /tests/store?user=&target=)

    public function store(Request $request) {

    	$data = array(
    		"user" => $request->user,
    		"target" => $request->target
    		);

    	$data_string = json_encode($data);
		$ch = curl_init('homestead.app/api/urls');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))                                                                       
		);

		echo $result = curl_exec($ch);

    }

    // Test Update Request (e.g. /tests/update?user=&short=&device=&target=)

    public function update(Request $request) {

    	$data = array(
    		"user" => $request->user,
    		"short" => $request->short,
    		"device" => $request->device,
    		"target" => $request->target,
    		);

    	$data_string = json_encode($data);
		$ch = curl_init('homestead.app/api/urls');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))                                                                       
		);

		echo $result = curl_exec($ch);

    }
}

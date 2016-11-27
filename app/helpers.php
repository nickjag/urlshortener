<?php

// Create random short code for shortened URL

function create_short_code() {
	return str_random(12);
}

// Add short code to current URL to get full shortened URL

function create_short_url($code) {
	return url('/') . '/u/' . $code;
}

// Extract short code from a shortened URL

function get_code($url) {
	$path = parse_url($url, PHP_URL_PATH);
	$path = basename($path);
	return ($path != '' ? $path : false);
}

// Format the output records from the database results

function format_output_record($record) {
	
	$seconds_ago = time() - strtotime($record->created_at);

	$formatted = array(
		'short' => create_short_url($record->code),
		'second_ago' => $seconds_ago,
		'target_urls' => array(
			'mobile' => array(
				'url' => $record->target_mobile,
				'redirects' => $record->redirects_mobile
				),
			'tablet' => array(
				'url' => $record->target_tablet,
				'redirects' => $record->redirects_tablet
				),
			'desktop' => array(
				'url' => $record->target_desktop,
				'redirects' => $record->redirects_desktop
				)
			)
		);

	return $formatted;
}

// Get the database results for listing all or by specific user

function get_records($user_id = false) {

	if ($user_id) {
		$where_col = 'short_urls.user';
		$operator = '=';
		$against = $user_id;
	}
	else {
		$where_col = 'short_urls.id';
		$operator = '>';
		$against = 0;
	}

	$records = DB::table('short_urls')
        ->join('target_urls as t1', 'short_urls.url_mobile', '=', 't1.id')
        ->join('target_urls as t2', 'short_urls.url_tablet', '=', 't2.id')
        ->join('target_urls as t3', 'short_urls.url_desktop', '=', 't3.id')
        ->select(
        		'short_urls.*',
        		't1.url as target_mobile',
        		't2.url as target_tablet',
        		't3.url as target_desktop',
        		't1.redirects as redirects_mobile',
        		't2.redirects as redirects_tablet',
        		't3.redirects as redirects_desktop'
        		)
        ->where($where_col, $operator, $against)
        ->get();

    return $records;

}
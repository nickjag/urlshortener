<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FunctionalTest extends TestCase
{
    use DatabaseTransactions;

    protected $target_url = 'testing';
    protected $user;
    protected $device;
    
    public function __construct() {
        $this->target_url = 'http://www.testing-' . str_random(5) . '.com';
        $this->user = rand(0,1000);
        $devices = array('mobile','tablet','desktop');
        $this->device = $devices[rand(0,2)];
    }
    
    /**
     * Test all JSON API Functions
     *
     * @return void
     */
    public function testUrlFunctions()
    {   

        // Create URL via JSON POST request

        $body = ['user' => $this->user, 'target' => $this->target_url];
        $res = $this->json('POST','api/urls', $body)
            ->seeJson([
                'success' => true,
            ]);

        echo "\n";
        echo '- Created shortened URL successfully.'."\n";
        echo '    POST: api/urls'."\n";
        echo '    Params: '. json_encode($body) ."\n";
        
        $res_arr = json_decode($res->response->getContent(), true);
        $short_url = $res_arr['short'];
        $short_code = get_code($short_url);

        // Get Target URL in Results

        $res = $this->get('api/urls')
            ->seeJson([
                'url' => $this->target_url
            ]);

        echo '- Found target URL in results'."\n";
        echo '    GET: api/urls'."\n";

        // Get Shortened URL in Results

        $res = $this->get('api/urls')
            ->seeJson([
                'short' => $short_url
            ]);

        echo '- Found short URL in results'."\n";
        echo '    GET: api/urls'."\n";

        // Modify URL via JSON PUT request

        $mobile_url = str_replace('http://www.','http://mobile.',$this->target_url);

        $body = ['user' => $this->user, 'device' => 'mobile', 'target' => $mobile_url, 'short' => $short_url];
        $res = $this->json('PUT','api/urls',$body)
            ->seeJson([
                'success' => true,
            ]);

        echo '- Modified shortened URL successfully'."\n";
        echo '    PUT: api/urls'."\n";
        echo '    Params: '. json_encode($body) ."\n";

        // Get Mobile URL in Results

        $res = $this->get('api/urls')
            ->seeJson([
                'url' => $mobile_url
            ]);

        echo '- Found mobile URL in results'."\n";
        echo '    GET: api/urls'."\n";

        // Get Target URL in User-Specific Results

        $res = $this->get('api/urls/'.$this->user)
            ->seeJson([
                'url' => $this->target_url
            ]);

        echo '- Found target URL in user-specific results'."\n";
        echo '    GET: api/urls/'.$this->user."\n";

        // Missing Target URL in Different User-Specific Results

        $diff_user = intval($this->user) + 1;
        $res = $this->get('api/urls/'.$diff_user)
            ->dontSeeJson([
                'url' => $this->target_url
            ]);

        echo '- Missing target URL in different user-specific results'."\n";
        echo '    GET: api/urls/'.$diff_user."\n";

        // Test Redirect of Short URL

        $res = $this->get($short_url);

        $this->assertEquals($this->target_url, $res->response->headers->get('location'));

        echo '- Redirect from short URL to target URL successfully'."\n";
        echo '    GET: '.$short_url."\n";
        echo '    Assert: '.$this->target_url."\n";
        echo '    Redirect: '.$res->response->headers->get('location')."\n";
    }
}

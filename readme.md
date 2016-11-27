
## About URL Shortener

This URL Shortener project is built with Laravel 5.3 and uses Composer. 

Requirements to test locally:

- PHP >= 5.6.4
- Composer
- Vagrant
- VirtualBox

## Getting Started

1. Clone the project

2. `composer install`

3. `php vendor/bin/homestead make`

4. `vagrant up`

5. Navigate to homestead.app

If the above URL is not loading, in your system's etc/hosts file, add the following: 

`192.168.10.10  homestead.app`

## Quick Testing

An automatic functional test will test all API calls and data, just type: 

`vendor/bin/phpunit`

Request the following in your browser to get a list of all current shortened URLs and corresponding data:

`http://homestead.app/api/urls`

## Return Data

When listing all shortened URLs, either all or by user, the following data is available:

- `short` : The shortened URL
- `seconds_ago` : Time since creation, in seconds
- `target_urls` : A JSON string containing the target URLs, based on device and the total number of redirects for that URL.

The number of redirects is based on unique shortened URLs, not devices.

## API Endpoints

**Shorten a target URL:**

POST
`/api/urls`

JSON Body
```json
{
	"user":1,
	"target":"http://www.google.com"
}
```

**Get all shortened URLs:**

GET
`/api/urls`

Sample Response
```json
[{
"short":"http:\/\/homestead.app\/u\/axk6rXcPapGZ",
"second_ago":2,
"target_urls":{
	"mobile":{
		"url":"http:\/\/www.testing.com",
		"redirects":"1"
		},
	"tablet":{
		"url":"http:\/\/www.testing.com",
		"redirects":"1"
		},
	"desktop":{
		"url":"http:\/\/www.testing.com",
		"redirects":"1"
		}
	}
}]
```

**Get shortened URLs by user (id):**

GET
`/api/urls/{user-id}`

**Modify a target URL by device type:**

PUT
`/api/urls`

**JSON Body**
```json
{
	"user":1,
	"target":"http://www.google.com",
	"device":"desktop",
	"short":"http://homestead.app/u/R6el7whunEYE"
}
```

## URL Convenience Testing

Here are two GET endpoints for conveniently testing the POST and PUT methods. 
Make sure to url-encode any parameters passed. 

**Shorten a target URL**

GET
`/tests/store`

GET Parameters
```
?user=1
&target=http%3A%2F%2Fwww.google.com
```

**Modify a target URL by device type:**

GET
`/tests/update`

GET Parameters
```
?user=1
&target=http%3A%2F%2Fwww.google.com
&device=desktop
&short=http%3A%2F%2Fhomestead.app%2Fu%2FR6el7whunEYE
```

## Database Reset

To clear the database:

`php artisan migrate:refresh`

Stop vagrant:

`vagrant destroy --force`

## Future Considerations

- Check during target update/modifiction if the target URL being replaced still exists within one of the devices of the shortened URL being updated. If not, decrease the amount of redirects by 1 for that target URL. 

- Check if a new short code already exists. Although unlikely (62^12 = 3.2E+21 Permutations), it's best to be thorough. 

- Create an index map and test each query for performance. 

## License

The URL Shortener is licensed under the [MIT license](http://opensource.org/licenses/MIT).


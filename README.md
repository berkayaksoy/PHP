LeoPlatform/PHP
===================

LEO PHP SDK

A php interface to interact with the LEO Platform

Documentation: https://docs.leoplatform.io

How to install the LEO SDK
===================================

Pre-Requisites
--------------
1. Install the aws-cli toolkit - Instructions for this are found at http://docs.aws.amazon.com/cli/latest/userguide/installing.html
2. Configure the aws-cli tools - Instructions are found at http://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-started.html


Install SDK
-----------
1. Two ways to install.  
 
Directly from composer:  (https://getcomposer.org/doc/01-basic-usage.md)

```
curl -sS https://getcomposer.org/installer | php
php composer.phar require leoplatform/php
```

Or using the GitHub Repository:


Create or add to your composer.json

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/LeoPlatform/PHP.git"
        }
    ],
    "require": {
        "leoplatform/php": "dev-master"
    }
}
```

Then run the install command:  

```
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

Or if you already have composer installed:

```
$ composer install
```

Example Usage
-------------

Create a leo_config.php. Example: 
```php
<?php

if (!defined('IN_LEO')) {
	// only call this from within the leo app
	exit;
}

$env_name = 'PHP_ENV';
$env = getenv($env_name);

switch ($env) {
	case 'dev':
	default:
		$config = [
			'leoaws' => [
				'profile'   => 'leotest',
				'region'    => 'us-west-2',
			],
			'leosdk' => [
				'region' => 'us-west-2',
				'resources' => [
					'LeoStream'         => 'LeoTestBus-LeoStream-10FA3RB1DTZDR',
					'LeoCron'           => 'LeoTestBus-LeoCron-7M9FPAWMLT8R',
					'LeoEvent'          => 'LeoTestBus-LeoEvent-Y1USS8D22B25',
					'LeoSettings'       => 'LeoTestBus-LeoSettings-UYVBZUNIWVNC',
					'LeoSystem'         => 'LeoTestBus-LeoSystem-U9IAZZQITSL1',
					'LeoS3'             => 'leotestbus-leos3-si2vssrd13ya',
					'LeoKinesisStream'  => 'LeoTestBus-LeoKinesisStream-1AYT0L5T0OPBM',
					'LeoFirehoseStream' => 'LeoTestBus-LeoFirehoseStream-1KR1P9Y50OT8R',
					'Region'            => 'us-west-2',
				],
				'kinesis'   => 'LeoTestBus-LeoKinesisStream-1AYT0L5T0OPBM',
				's3'        => 'leotestbus-leos3-si2vssrd13ya',
				'firehose'  => 'LeoTestBus-LeoFirehoseStream-1KR1P9Y50OT8R',
			],
			'leoauth' => [
				'region' => 'us-west-2',
				'resources' => [
					'Region'            => 'us-west-2',
					'LeoAuth'           => 'LeoTestAuth-LeoAuth-7W9Y5BV1DV6Q',
					'LeoAuthUser'       => 'LeoTestAuth-LeoAuthUser-10TEEBU3R6G9B',
					'LeoAuthIdentity'   => 'LeoTestAuth-LeoAuthIdentity-YN1KPFYLMET5',
					'LeoAuthPolicy'     => 'LeoTestAuth-LeoAuthPolicy-9SYIP05HBKTF',
				],
			],
			'enableLogging' => false,
			'debug'         => false,
		];
	break;

	case 'staging':
		$config = [
			'leoaws' => [
				'profile'   => 'leo',
				'region'    => 'us-west-2',
			],
			'leosdk' => [
				'region' => 'us-west-2',
				'resources' => [
					'LeoStream'         => 'nestedTest1-Bus-1719AK03AAD1-LeoStream-1PY4VIHESLXJ9',
					'LeoCron'           => 'nestedTest1-Bus-1719AK03AAD1-LeoCron-E6Z5B776RE2H',
					'LeoEvent'          => 'nestedTest1-Bus-1719AK03AAD1-LeoEvent-1X2ZR5Y4S9XSK',
					'LeoSettings'       => 'nestedTest1-Bus-1719AK03AAD1-LeoSettings-199TCN54F27AQ',
					'LeoSystem'         => 'nestedTest1-Bus-1719AK03AAD1-LeoSystem-1OA77QUNZAH0U',
					'LeoS3'             => 'nestedtest1-bus-1719ak03aad1-leos3-pgljot8blqgw',
					'LeoKinesisStream'  => 'nestedTest1-Bus-1719AK03AAD1-LeoKinesisStream-7LTMBRYPBAI5',
					'LeoFirehoseStream' => 'nestedTest1-Bus-1719AK03AAD1-LeoFirehoseStream-Y34YJGWNRO3U',
					'Region'            => 'us-west-2',
				],
				'kinesis'   => 'nestedTest1-Bus-1719AK03AAD1-LeoKinesisStream-7LTMBRYPBAI5',
				's3'        => 'nestedtest1-bus-1719ak03aad1-leos3-pgljot8blqgw',
				'firehose'  => 'nestedTest1-Bus-1719AK03AAD1-LeoFirehoseStream-Y34YJGWNRO3U',
			],
			'leoauth' => [
				'region' => 'us-west-2',
				'resources' => [
					'Region'            => 'us-west-2',
					'LeoAuth'           => 'nestedTest1-Auth-FNCUKVEJGXP4-LeoAuth-44CCD6TCUF0N',
					'LeoAuthUser'       => 'nestedTest1-Auth-FNCUKVEJGXP4-LeoAuthUser-1FNK1G58XA8QM',
					'LeoAuthIdentity'   => 'nestedTest1-Auth-FNCUKVEJGXP4-LeoAuthIdentity-1E8F4X1TFDJ9L',
					'LeoAuthPolicy'     => 'nestedTest1-Auth-FNCUKVEJGXP4-LeoAuthPolicy-8PDU1RYSKZ2U',
				],
			],
			'enableLogging' => false,
			'debug'         => false,
		];
	break;

	case 'production':
	break;
}
```

For all files that interact with Leo, Set the leo_config, autoload, and IN constant:
```php
define('IN_LEO', true);
require_once("vendor/autoload.php");

// leo_config.php will automatically load if in the same directory as this file. If not, explicitly load it:
new \Leo\lib\Config('./leo_config.php');
```
Load events to LEO Platform

```php
//create a Leo-sdk object
$leo = new Leo\Sdk("BotName");

//These are optional parameters, see the docs for possible values
$stream_options = [];

//function is called with every commit to the stream
//returns data about the checkpoint
$checkpoint_function = function ($checkpointData) {
	var_dump($checkpointData);
};

// create a loader stream
$stream = $leo->createLoader($checkpoint_function, $stream_options);

for($i = 0; $i < 100000; $i++) {
	$event = [
		"id"=>"testing-$i",
		"data"=>"some order data",
		"other data"=>"some more data"
	];
	$meta = ["source"=>null, "start"=>$i];
  
	//write an event to the stream
	$stream->write("QueueName", $event, $meta);
}
$stream->end();
```

Enrich Events on LEO 

```php
$bot_name = "EnrichmentBot";
$in_queue_name = "QueueName";
$enriched_queue_name = "EnrichedQueueName";
$read_options = ['limit'=>100000, 'run_time'=> "4 minutes"];

$transform_function = function ($event, $meta) {
			var_dump($event);
			$event["newdata"] = "this is some new data";
			return $event;
		};

$leo = new Leo\Sdk($bot_name, $config);

$stream = $leo->createEnrichment($in_queue_name, $transform_function, $enriched_queue_name, $read_options );
```

Offload Events From LEO
-----------------------

```php 
	$bot_name = 'PHPOffloaderBot';
	$queue_name = 'PHPEnrichedQueue';
	$read_options = ['limit' => 500, 'run_time' => '4 minutes'];

	$leo = new Leo\Sdk($bot_name);
	$checkpointCount = $count = 0;

	$reader = $leo->createOffloader($queue_name, $read_options);
	foreach ($reader->events as $i => $event) {
		$count++;
		\Leo\lib\Utils::log($event);
		
		// print out events so we can see them - for debugging
		// print_r($event);

		if (++$checkpointCount == 1000) {
			$reader->checkpoint();
			$checkpointCount = 0;
		}
		
		// sleep for 0.1 of a second to be able to read events as they pass through the command line - for debugging
		// usleep(100000);
	}
	
	// checkpoint after we're done to tell the bot where we stopped last
	$reader->checkpoint();
	\Leo\lib\Utils::log(++$count);

```


Logging
-------
The LEO SDK will pass your PHP logs up to the LEO Platform so that you can debug them using the Data Innovation Center user interface.

You do this when you instantiate a new Leo\Sdk object by setting **enableLogging** with a value of **true** in your leo_config.php

Autoload
---------------------

The LEO PHP SDK uses Composer's autoload functionality to include classes that are needed.  Including the following code into any php file in your project will automatically load the LEO SDK.

```
require_once("vendor/autoload.php");
```

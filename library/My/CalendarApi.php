<?php
require_once 'Zend/Http/Client.php';

class My_CalendarApi {
	
    static public function getCalendarEntries() {
		$calendarId = 'c024b24f05198472aeed3516f88e43e4165e8e03aae15c9c4a048da46e962caf@group.calendar.google.com';
		$apiKey = 'AIzaSyA9ncwaogRxEc4Cf5_tErTYJxOygTrZLUI';
		
		$date = new DateTime('now', new DateTimeZone('UTC'));
		$now = $date->format('Y-m-d\TH:i:s') . 'Z';  // np. "2025-05-05T14:30:00Z"

		$maxResults = '10';
		$url = 'https://www.googleapis.com/calendar/v3/calendars/' 
		. $calendarId  
		.'/events?key='
		. $apiKey
		.'&timeMin='
		. $now
		.'&singleEvents=true&orderBy=startTime&maxResults='
		. $maxResults;
		
		$client = new Zend_Http_Client($url);
		$client->setConfig(array(
			'sslverifypeer' => false, // Wyłącza weryfikację certyfikatu
			'sslverifyhost' => false  // Wyłącza weryfikację hosta w certyfikacie
		));
		
		$response = $client->request('GET');
		if ($response->isSuccessful()) {
			$data = $response->getBody();
			echo $data;
		} else {
			echo 'Error';
		}
    }
}
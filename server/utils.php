<?php
function newConnection(Socket $client, string $id)
{
	global $users, $commands;

	socket_getpeername($client, $address); // Get ip address
	$user = DBController::selectUser('id', $id);

	// New user recieves all connected ips
	$callback = socket_encodeResponse(array(
		'command' => $commands[6],
		'address' => $address,
		'users' => getClientsInfo($address)
	));

	// Already connected users recieve only the new connection's ip
	$notification = socket_encodeResponse(array(
		'command' => $commands[5],
		'username' => $user->getUsername(),
		'avatar' => $user->getAvatar(),
		'address' => $address
	));

	socket_write($client, $callback, strlen($callback)); // Send response
	socket_sendForAll($notification, $address); // Send response for all connected users
	$users[$address] = $user;
}

function disconnection(Socket $client)
{
	global $sockets, $commands;

	socket_getpeername($client, $address); // Get ip address
	socket_remove($client, $sockets); // Remove client for connected clients array

	$response = socket_encodeResponse(array(
		'command' => $commands[1],
		'address' => $address
	));

	socket_sendForAll($response, $address); // Notify all users about disconnected client
}

function privateMessage(Socket $client, string $toIP, string $message)
{
	global $sockets, $commands;
	socket_getpeername($client, $fromIP);

	$dt = new DateTime();
	$hour = $dt->format('H') . ':' . $dt->format('i');

	if ($sockets[$fromIP] && $sockets[$toIP]) { // If the destionation socket exists
		$callback = socket_encodeResponse(array(
			'command' => $commands[4],
			'to' => $toIP,
			'message' => $message,
			'hour' => $hour
		));

		$response = socket_encodeResponse(array(
			'command' => $commands[2],
			'from' => $fromIP,
			'message' => $message,
			'hour' => $hour
		));

		socket_write($client, $callback, strlen($callback)); // Send message
		socket_write($sockets[$toIP], $response, strlen($response)); // Send message
	}
}

function grupalMessage(Socket $client, string $code, string $message)
{
	global $rooms, $sockets, $commands, $users;

	socket_getpeername($client, $fromIP);

	$dt = new DateTime();
	$hour = $dt->format('H') . ':' . $dt->format('i');

	$callback = socket_encodeResponse(array(
		'command' => $commands[3],
		'room' => $code,
		'message' => $message,
		'hour' => $hour
	));

	$notification = socket_encodeResponse(array(
		'command' => $commands[3],
		'room' => $code,
		'from' => $users[$fromIP]->getUsername(),
		'message' => $message,
		'hour' => $hour
	));

	socket_write($client, $callback, strlen($callback)); // Send message

	foreach ($rooms[$code]['members'] as $address) {
		if ($fromIP <=> $address) {
			socket_write($sockets[$address], $notification, strlen($notification));
		}
	}
}

function requestUsers(Socket $client, string $attr)
{
	socket_getpeername($client, $client_address);

	global $users, $sockets, $commands;
	$results = array();
	$keys = array_keys($sockets);

	$index = array_search($client_address, $keys);
	unset($keys[$index]);

	if (!empty($attr)) {
		foreach ($keys as $address) {
			$user = $users[$address];
			if (str_starts_with($address, $attr) || str_starts_with($user->getUsername(), $attr)) {
				array_push($results, array(
					'username' => $user->getUsername(),
					'avatar' => $user->getAvatar(),
					'address' => $address
				));
			}
		}
	}

	$response = socket_encodeResponse(array(
		'command' => $commands[7],
		'results' => $results
	));

	socket_write($client, $response, strlen($response));
}

function createRoom(Socket $client, string $name, string $password)
{
	global $rooms, $commands, $users;

	socket_getpeername($client, $address);

	$code = uniqid();

	$rooms[$code] = array(
		'name' => $name,
		'password' => password_hash($password, PASSWORD_DEFAULT),
		'owner' => $address
	);

	$callback = socket_encodeResponse(array(
		'command' => $commands[8],
		'code' => $code,
		'name' => $name,
		'owner' => $users[$address]->getUsername(),
		'isOwner' => true
	));

	$notification = socket_encodeResponse(array(
		'command' => $commands[8],
		'code' => $code,
		'name' => $name,
		'owner' => $users[$address]->getUsername(),
		'isOwner' => false
	));

	socket_write($client, $callback, strlen($callback));
	socket_sendForAll($notification, $address);
}

function getAllRooms(Socket $client)
{
	global $commands, $rooms, $users;

	$results = array();

	foreach ($rooms as $code => $room) {
		array_push($results, array(
			"code" => $code,
			"name" => $room['name'],
			"owner" => $users[$room['owner']]->getUsername()
		));
	}

	$response = socket_encodeResponse(array(
		"command" => $commands[12],
		"rooms" => $results
	));

	socket_write($client, $response, strlen($response));
}

function loginRoom(Socket $client, string $code, string $password)
{
	global $rooms, $commands;
	$success = false;

	if (!isset($rooms[$code]['members'])) {
		$rooms[$code]['members'] = array();
	}

	if (password_verify($password, $rooms[$code]['password'])) {
		socket_getpeername($client, $address);
		array_push($rooms[$code]['members'], $address);
		$success = true;
	}

	$response = socket_encodeResponse(array(
		'command' => $commands[14],
		'success' => $success,
		'name' => $rooms[$code]['name'],
		'code' => $code
	));

	socket_write($client, $response, strlen($response));
}

function getCreatedRooms(Socket $client)
{
	global $rooms, $commands;

	socket_getpeername($client, $address);

	$results = array();

	foreach ($rooms as $code => $room) {
		$owner = $room['owner'];

		if ($owner == $address) {
			array_push($results, array(
				"code" => $code,
				"name" => $room['name'],
			));
		}
	}

	$response = socket_encodeResponse(array(
		'command' => $commands[11],
		'rooms' => $results
	));

	socket_write($client, $response, strlen($response));
}

function deleteRoom(Socket $client, string $code)
{
	global $rooms, $commands;

	unset($rooms[$code]);

	$response = socket_encodeResponse(array(
		"command" => $commands[10],
		"code" => $code
	));

	// socket_write($client, $response, strlen($response));
	socket_sendForAll($response);
}

// Delete socket in specified array
function socket_remove(Socket $socket, array &$socket_array)
{
	$index = array_search($socket, $socket_array);
	unset($socket_array[$index]);
}

// Send message to all clients
function socket_sendForAll(string $message, ?string $exceptIP = NULL)
{
	global $sockets;

	foreach ($sockets as $address => $socket) {
		if ($address <=> $exceptIP)
			@socket_write($socket, $message, strlen($message));
	}

	return true;
}

// Get all ip addresses for connected clients
function getClientsInfo(?string $except = NULL): array
{
	global $sockets, $users;
	$keys = array_keys($sockets);
	$clients_info = array();

	if ($except && in_array($except, $keys)) {
		$index = array_search($except, $keys);
		unset($keys[$index]);
	}

	foreach ($keys as $key) {
		$user = $users[$key];
		array_push($clients_info, array(
			'username' => $user->getUsername(),
			'avatar' => $user->getAvatar(),
			'address' => $key
		));
	}

	return $clients_info;
}

// Encode the response to send
function socket_encodeResponse(string|array $response): ?string
{
	return socket_mask(json_encode($response), true);
}

// Decode the recieve response
function socket_decodeResponse(string $response): ?array
{
	return json_decode(socket_unmask($response), true);
}

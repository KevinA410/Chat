<?php
// Generate a username which has not been assigned
function genereteUsername(){
	global $usernames;

	$aux = 'User';
	$name = '';
	$flag = true;

	while($flag){
		$flag = false;
		$name = $aux . rand(0, 10000);
		
		foreach($usernames as $username){
			if($username == $name){
				$flag = true;
				break;
			}
		}
	}

	return $name;
}

// Delete socket in specified array
function socket_remove($socket, &$socket_array){
	$index = array_search($socket, $socket_array);
	unset($socket_array[$index]);
	// $socket_array = array_values($socket_array);
}

// Send message to all clients
function socket_sendForAll($message){
	global $sockets;

	foreach($sockets as $socket){
		@socket_write($socket, $message, strlen($message));
	}

	return true;
}

// Get all ip addresses for connected clients
function getClientsInfo(){
	global $sockets, $usernames;

	$clients_info = array();

	foreach($sockets as $address => $value){
		array_push($clients_info, array(
			'username' => $usernames[$address],
			'address' => $address
		));
	}

	return $clients_info;
}

// Encode the response to send
function socket_encodeResponse($response){
	return socket_mask(json_encode($response), true);
}

// Decode the recieve response
function socket_decodeResponse($response){
	return json_decode(socket_unmask($response), true);
}

?>
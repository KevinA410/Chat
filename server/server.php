<?php
include_once '../database/model/User.php';
include_once '../database/controller/DBController.php';

$server_address = '192.168.0.103';
$server_port = '9000';

$sockets = array(); // IP => Socket
$users = array(); // IP => User
$rooms = array(); // 
$commands = [
    'connection',           // [0]
    'disconnection',        // [1]
    'personal_message',     // [2]
    'group_message',        // [3]
    'verified_message',     // [4]
    'get_new_connection',   // [5]
    'get_all_connections',  // [6]
    'request_users',        // [7]
    'create_room',          // [8]
    'edit_room',            // [9]
    'delete_room',          // [10]
    'get_created_rooms',    // [11]
    'get_all_rooms',        // [12]
    'request_rooms',        // [13]
    'login_room',           // [14]

];

include_once 'socket.php'; // Include socket librery
include_once 'utils.php'; // Include additional functions

$master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); // Create socket TCP
socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, true); // Enable reusable port
socket_bind($master, $server_address, $server_port); // Connect port to master info
socket_listen($master); // Set socket to listen requests

while (true) { // Keep run
    $copy = $sockets;
    $null = NULL;
    
    array_push($copy, $master);
    socket_select($copy, $null, $null, 0, 10); // Writes clients who made a request in $copy

    if(in_array($master, $copy)){ // If there's a new connection request
        $new_socket = socket_accept($master); // Create new client socket
        $header = socket_read($new_socket, 1024); // Get header
        socket_handshaking($header, $new_socket, $server_address, $server_port); // Link to master
        socket_getpeername($new_socket, $new_address); // Get ip of new socket
        
        if(isset($sockets[$new_address])) {
            disconnection($new_socket);
        }

        $sockets[$new_address] = $new_socket;
        socket_remove($master, $copy); // Remove master from $copy_clients
    }

    foreach($copy as $client){ // Attend all clients requests
		while(socket_recv($client, $buffer, 1024, 0) >= 1){ // Check for incomming data
            $request = socket_decodeResponse($buffer); // Decode client's request

            if($request){ // If there's a request
                match($request['command']) {
                    // Set new connected user in the users array
                    $commands[0] => newConnection($client, $request['id']),
                    // Send private message
                    $commands[2] => privateMessage($client, $request['to'], $request['message']),
                    // Send grupal message
                    $commands[3] => grupalMessage($client, $request['room'], $request['message']),
                    // Send requested users
                    $commands[7] => requestUsers($client, $request['attr']),
                    // Create room
                    $commands[8] => createRoom($client, $request['name'], $request['password']),
                    // Delete room
                    $commands[10] => deleteRoom($client, $request['code']),
                    // Get all rooms
                    $commands[12] => getAllRooms($client),
                    // Get created rooms
                    $commands[11] => getCreatedRooms($client),
                    // Login in room
                    $commands[14] => loginRoom($client, $request['code'], $request['password']),

                };
            }
            
			break 2;
		}
		
		$buffer = @socket_read($client, 1024, PHP_NORMAL_READ);

		if ($buffer === false) { // If the client disconnected
            disconnection($client);
		}
    }
}

socket_close($master); // Close master socket
<?php
$server_address = '192.168.0.103';
$server_port = '9000';

$sockets = array(); // [IP => Socket]
$usernames = array(); // IP => username

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

        $username = array_key_exists($new_address, $usernames) ? $usernames[$new_address] : genereteUsername();
        $clients_info = getClientsInfo(); //Get ip of connected clients

        // New user recieves all connected ips
        $response1 = socket_encodeResponse(array(
            'command' => 'connected_users',
            'own_address' => $new_address,
            'own_name' => $username,
            'clients' => $clients_info
        ));

        // Already connected users recieve only the new connection's ip
        $response2 = socket_encodeResponse(array(
            'command' => 'new_connection',
            'username' => $username,
            'address' => $new_address
        ));

        socket_write($new_socket, $response1, strlen($response1)); // Send response
        socket_sendForAll($response2); // Send response for all connected users
        
        $sockets[$new_address] = $new_socket;
        $usernames[$new_address] = $username;

        socket_remove($master, $copy); // Remove master from $copy_clients
    }

    foreach($copy as $client){ // Attend all clients requests
		while(socket_recv($client, $buffer, 1024, 0) >= 1){ // Check for incomming data
            $request = socket_decodeResponse($buffer); // Decode client's request

            if($request){ // If there's a request
                socket_getpeername($client, $address); // Get ip address

                switch($request['command']){
                    case 'private_message': // Personal message (Client to Client)
                        $to_socket = $sockets[$request['to']]; // Get destionation socket
                        $dt = new DateTime();
                        $hour = $dt->format('H') . ':' . $dt->format('i');
    
                        if($to_socket){ // If the destionation socket exists
                            $callback = socket_encodeResponse(array(
                                'command' => 'verfied_message',
                                'to' => $request['to'],
                                'message' => $request['message'],
                                'hour' => $hour
                            ));

                            $response = socket_encodeResponse(array(
                                'command' => $request['command'],
                                'from' => $address,
                                'message' => $request['message'],
                                'hour' => $hour
                            ));
                            
                            socket_write($client, $callback, strlen($callback)); // Send message
                            socket_write($to_socket, $response, strlen($response)); // Send message
                        }

                        break;
                    case 'change_username':
                        $username = $request['new_name'];
                        $message;

                        if(!in_array($username, $usernames)){
                            $usernames[$address] = $username;
                            $message = 'Changed successfuly.';
                        }else{
                            $message = 'This username already exist.';
                        }

                        $callback = socket_encodeResponse(array(
                            'command' => 'change_username_callback',
                            'new_name' => $usernames[$address],
                            'message' => $message
                        ));

                        socket_write($client, $callback, strlen($callback)); // Send message

                        break;
                    default:
                }
            }
            
			break 2;
		}
		
		$buffer = @socket_read($client, 1024, PHP_NORMAL_READ);

		if ($buffer === false) { // If the client disconnected
            socket_getpeername($client, $address); // Get ip address
            socket_remove($client, $sockets); // Remove client for connected clients array
			
            $response = socket_encodeResponse(array(
                'command' => 'disconnected_user',
                'address' => $address
            ));
            
            socket_sendForAll($response); // Notify all users about disconnected client
		}
    }
}

socket_close($master); // Close master socket
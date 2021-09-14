$(document).ready(function () {
    // Global variables
    var wsURL = "ws://192.168.0.103:9000/server.php";
    let socket = new WebSocket(wsURL);
    var currentChat = null;
    const isLg = window.screen.width * window.devicePixelRatio >= 992 ? true : false;
    var mode = $("#mode").val();
    var commands = [
        'connection', // [0]
        'disconnection', // [1]
        'personal_message', // [2]
        'group_message', // [3]
        'verified_message', // [4]
        'get_new_connection', // [5]
        'get_all_connections', // [6]
        'request_users', // [7]
        'create_room', // [8]
        'edit_room', // [9]
        'delete_room', // [10]
        'get_created_rooms', // [11]
        'get_all_rooms', // [12]
        'request_rooms', // [13]
        'login_room', // [14]
    ];

    if (mode == 'private') {
        setChat(false);
    }

    // if (isLg) {
    //     $("#right").removeAttr('hidden');
    // }

    // Socket events
    socket.onopen = function (event) {
        if (mode == 'private') {
            // In private messages interface
            socket.send(JSON.stringify({
                "command": commands[0],
                "id": $("#userId").val()
            }));
        } else {
            // In chat rooms interface
            socket.send(JSON.stringify({
                "command": commands[12],
            }));

            socket.send(JSON.stringify({
                "command": commands[11],
            }));
        }

    }

    socket.onmessage = function (event) {
        var r = JSON.parse(event.data);
        switch (r.command) {
            case commands[6]:
                $("#profile_address").val(r.address);
                $("#connected").html(' ');

                r.users.forEach(client => {
                    var id = client.address.replaceAll('.', '_');

                    $("#connected").append(html_newConnection(id, client.username, client.avatar, client.address));

                    $("#" + id).on('click', function () {
                        setBadge(id, 0);
                        if (!currentChat) {
                            setChat(true);
                        }

                        $("#destination_avatar").attr('src', '../resources/profiles/' + client.avatar);
                        $("#destination_name").html(client.username);
                        $("#destination_address").html(client.address);


                        currentChat = client.address;
                        loadMessages(client.address);

                        if (!isLg) {
                            $("#btn_slide").click();
                        }
                    });
                });
                break;
            case commands[5]:
                var id = r.address.replaceAll('.', '_');

                $("#connected").append(html_newConnection(id, r.username, r.avatar, r.address));

                $("#" + id).on('click', function () {
                    setBadge(id, 0);
                    if (!currentChat) {
                        setChat(true);
                    }

                    $("#destination_avatar").attr('src', '../resources/profiles/' + r.avatar);
                    $("#destination_name").html(r.username);
                    $("#destination_address").html(r.address);

                    currentChat = r.address;
                    loadMessages(r.address);

                    if (!isLg) {
                        $("#btn_slide").click();
                    }
                });
                break;
            case commands[1]:
                // Remove ip from connected list
                var id = r.address.replaceAll('.', '_');
                $("#" + id).remove();

                if (currentChat == r.address) {
                    setChat(false);
                    currentChat = null;
                }
                break;
            case commands[2]:
                saveMessage(r.message, r.hour, r.from);

                if (currentChat == r.from) {
                    loadMessages(r.from);
                } else {
                    var id = r.from.replaceAll('.', '_');
                    setBadge(id, getBadge(id) + 1);
                }
                break;
            case commands[3]:
                if (!r.from) {
                    saveMessage(r.message, r.hour, r.room, 'You');
                    loadMessages(r.room);
                } else {
                    saveMessage(r.message, r.hour, r.room, r.from);

                    if (currentChat == r.room) {
                        loadMessages(r.room);
                    } else {
                        setBadge(r.room, getBadge(r.room) + 1);
                    }
                }
                break;
            case commands[4]:
                saveMessage(r.message, r.hour, r.to, 'You');
                loadMessages(r.to);
                break;
            case commands[7]:
                $("#results").html('');

                r.results.forEach(client => {
                    var id = client.address.replaceAll('.', '_');

                    $("#results").append(html_newConnection(id, client.username, client.avatar, client.address));

                    $("#" + id).on('click', function () {
                        if (!currentChat) {
                            setChat(true);
                        }

                        $("#destination_avatar").attr('src', '../resources/profiles/' + client.avatar);
                        $("#destination_name").html(client.username);
                        $("#destination_address").html(client.address);


                        currentChat = client.address;
                        loadMessages(client.address);

                        if (!isLg) {
                            $("#btn_slide").click();
                        }
                    });
                });
                break;
            case commands[8]:
                if (r.isOwner) {
                    $('#my-rooms').append(`
                    <div class="card p-1 ${r.code}" id="">
                        <div class="row row-cols-3">
                            <div class="col">
                                <p><strong>Name: </strong><span>${r.name}</span></p>
                            </div>
                            <div class="col text-center">
                            <p><strong>Code: </strong><span>#${r.code}</span></p>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-end">
                                    <button id="delete-${r.code}" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `);

                    $('#delete-' + r.code).on('click', function () {
                        socket.send(JSON.stringify({
                            "command": commands[10],
                            "code": r.code
                        }));
                    });
                }

                $('#rooms').append(html_getAllRooms(r.code, r.name, r.owner));

                $("#" + r.code).on('click', function () {
                    var password = prompt('Password');

                    if (password == null) {
                        return;
                    }

                    socket.send(JSON.stringify({
                        "command": commands[14],
                        "password": password,
                        "code": r.code
                    }));

                    currentChat = r.code;
                });

                break;
            case commands[10]:
                $('.' + r.code).remove();
                break;
            case commands[11]:
                r.rooms.forEach(room => {
                    $('#my-rooms').append(`
                    <div class="card p-1 ${room.code}" id="">
                        <div class="row row-cols-3">
                            <div class="col">
                                <p><strong>Name: </strong><span>${room.name}</span></p>
                            </div>
                            <div class="col text-center">
                            <p><strong>Code: </strong><span>#${room.code}</span></p>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-end">
                                    <button id="delete-${room.code}" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `);

                    $('#delete-' + room.code).on('click', function () {
                        socket.send(JSON.stringify({
                            "command": commands[10],
                            "code": room.code
                        }));
                    });

                });
                break;
            case commands[12]:
                r.rooms.forEach(room => {
                    $('#rooms').append(html_getAllRooms(room.code, room.name, room.owner));

                    $("#" + room.code).on('click', function () {
                        var password = prompt('Password');

                        if (password == null) {
                            return;
                        }

                        socket.send(JSON.stringify({
                            "command": commands[14],
                            "password": password,
                            "code": room.code
                        }));

                        currentChat = room.code;
                    });
                });
                break;
            case commands[14]:
                if (!r.success) {
                    alert('Incorrect Password');
                    return;
                }

                var roomClass = 'd-flex justify-content-center align-items-start row col-12 col-lg-9';
                $("#room-content").attr('class', roomClass + ' d-none');
                $("#right").removeAttr('hidden', true);

                $("#room_title").html(r.name);
                $("#room_code").html('#' + r.code);
                loadMessages(r.code);
                break;
            default:
                console.log("There's no function for this command");
        }
    }

    socket.onclose = function (event) {
        $("#form_logout").submit();
    }

    socket.onerror = function (event) {

    }

    // General events
    $("#btn_send").on('click', function () {
        var to = $("#destination_address").html();
        var message = $("#input_message").val();

        if (message == '') {
            alert("You can't send empty messages");
            return;
        }

        $("#input_message ").val('');

        socket.send(JSON.stringify({
            "command": commands[2],
            "to": to,
            "message": message
        }));
    });

    $("#btn_changeUsername").on('click', function () {
        socket.send(JSON.stringify({
            "command": "change_username",
            "new_name": $("#input_username").val()
        }));
    });

    $("#btn_slide").on('click', function () {
        if ($("#left").attr('hidden')) {
            $("#left").removeAttr('hidden');
            $("#right").attr('hidden', true);
        } else {
            $("#left").attr('hidden', true);
            $("#right").removeAttr('hidden');
        }
    });

    $("#searchBar").keyup(function () {
        socket.send(JSON.stringify({
            "command": commands[7],
            "attr": $("#searchBar").val()
        }));
    });

    $("#searchBar").on('focusin', function () {
        $("#results").removeAttr('hidden', true);
        $("#results").html('');
    });

    $("#searchBar").on('focusout', function () {
        $("#searchBar").val('');
        setTimeout(() => {
            $("#results").attr('hidden', true);
            $("#results").html('');
        }, 300);
    });

    // Room events
    $("#create_room").on('click', function () {
        var roomName = $("#room_name").val();
        var roomPassword = $("#room_password").val();

        if (!roomName) {
            alert('Room name cannot be empty');
            return;
        }

        socket.send(JSON.stringify({
            "command": commands[8],
            "name": roomName,
            "password": roomPassword,
        }));

        $("#room_name").val('');
        $("#room_password").val('');
    });

    $("#back_rooms").on('click', function () {
        var roomClass = 'd-flex justify-content-center align-items-start row col-12 col-lg-9';
        $("#room-content").attr('class', roomClass);
        $("#right").attr('hidden', true);

        currentChat = null;
    });

    $("#btn_sendGroup").on('click', function () {
        var room = $("#room_code").html();
        room = room.substring(1, room.length);

        var message = $("#input_message").val();

        if (message == '') {
            alert("You can't send empty messages");
            return;
        }

        $("#input_message ").val('');

        socket.send(JSON.stringify({
            "command": commands[3],
            "room": room,
            "message": message
        }));
    });

    // Auxiliar functions
    function html_newConnection(id, username, avatar, address) {
        return `
            <div id="${id}" class="card py-3 shadow-sm px-3 mb-3">
                <div class="row">
                    <!-- Avatar -->
                    <div class="col-1 col-lg-3">
                        <img src="../resources/profiles/${avatar}" width="60px" height="60px" alt="">
                    </div>
                    <div class="col-9 d-flex align-items-center">
                        <div class="ms-4 ms-lg-3">
                            <h5 class="h6 mb-1">${username} <span id="badge_${id}" class=""></span></h5>
                            <h5 class="h6 text-muted">
                                <div id="dot" class="connected-dot"></div>
                                ${address}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function html_getAllRooms(code, name, owner) {
        var html = `
            <div id="${code}" class="${code} card py-3 shadow-sm px-3 mb-3">
                <div class="ms-4 ms-lg-3">
                    <h5 class="h6 mb-1">${name} <span id="badge_${code}" class=""></span></h5>
                    <h5 class="h6 mb-1">${owner}</h5>
                    <h5 class="h6 text-muted">
                        #${code}
                    </h5>
                </div>
            </div>
        `;

        return html;
    }

    function setBadge(id, number) {
        var cls = number > 0 ? 'badge rounded-pill bg-primary mb-1' : '';
        var html = number > 0 ? number : '';

        $("#badge_" + id).attr('class', cls);
        $("#badge_" + id).html(html);
    }

    function getBadge(id) {
        var html = $("#badge_" + id).html();
        return html == '' ? 0 : parseInt(html);
    }

    function setChat(option) {
        var str = option ? 'd-block' : 'd-none';
        $("#chat-flag").attr('class', str);
    }

    function saveMessage(message, hour, keyAddress, fromAddress = keyAddress) {
        var conv = sessionStorage.getItem(keyAddress) ?
            JSON.parse(sessionStorage.getItem(keyAddress)) :
            new Array();

        conv.push({
            from: fromAddress,
            message: message,
            hour: hour
        });

        sessionStorage.setItem(keyAddress, JSON.stringify(conv));
    }

    function loadMessages(keyAddress) {
        var raw = '';
        var conv = sessionStorage.getItem(keyAddress) ?
            JSON.parse(sessionStorage.getItem(keyAddress)) :
            new Array();

        conv.forEach(item => {
            var user = item.from;
            var style = user == 'You' ? 'own-message' : 'others-message';

            raw += `
                <div class="card my-2 p-2 ${style}">
                    <p class="card-text mb-0 pb-0">
                        <span class="text-muted">${user}: </span>${item.message}
                    </p>
                    <p class="text-end my-0 py-0"><small class="text-muted">Sent at ${item.hour}</small></p>
                </div>
            `;
        });

        $("#messages").html(raw);
    }
});
<?php include_once 'layout/layout.php' ?>

<?php layout_begin() ?>
<input type="hidden" value="private" id="mode">
<div id="main-adapted" class="px-2">
    <!--  -->
    <div class="row mt-3">
        <!-- Search pannel -->
        <div class="col-12 col-lg-3" id="left">
            <!-- Connected users -->
            <div class="">
                <!-- Title -->
                <span class="d-block h5">Connected</span>
                <!-- Search form -->
                <div class="input-group flex-nowrap">
                    <input id="searchBar" type="search" class="form-control" placeholder="Search user"
                        aria-describedby="addon-wrapping">
                    <button class="btn btn-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#191919"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </button>
                </div>
                <div class="bg-light shadow" id="results">
                    
                </div>
                <hr class="my-2">
                <!-- Connected clients -->
                <div id="connected" class="">

                </div>
            </div>

        </div>
        <!-- Conversation panel -->
        <div class="col-12 col-lg-9" id="right" hidden>
            <div id="chat-flag">
                <!-- Information card -->
                <div class="card py-3 shadow px-3">
                    <div class="row">
                        <!-- Avatar -->
                        <div class="col-1">
                            <img id="destination_avatar" src="" width="60px" height="60px" alt="">
                        </div>
                        <div class="col-9 d-flex align-items-center">
                            <div class="ms-4 ms-lg-2">
                                <h4 class="h5"><strong id="destination_name"></strong></h4>
                                <h5 class="h6 text-muted">
                                    <span id="destination_address"></span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- messages container -->
                <div id="messages" class="mt-2"> </div>
                <!-- Send messages controls -->
                <div class="" id="send-controls">
                    <div class="input-group flex-nowrap">
                        <input id="input_message" type="text" class="form-control" placeholder="Write here..."
                            aria-describedby="addon-wrapping">
                        <button id="btn_send" class="btn btn-primary">
                            <!-- Send icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                class="bi bi-cursor-fill" viewBox="0 0 16 16">
                                <path
                                    d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
</div>
<?php layout_end() ?>
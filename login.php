<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" media="all">
        <link rel="stylesheet" href="output.css" type="text/css" media="all">
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.js"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//platform.linkedin.com/in.js">
            api_key: ['Client Id']
            onLoad: onLinkedInLoad
        </script>
    </head>
    <body>
        <header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
            <div class="container">
                <div class="navbar-header">
                </div>
                <nav id="bs-navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" id="logout">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <div id="login">
            <div name="loginform" id="loginform" >
                <div class="login-title">Sign In</div>
                <div class="login-container">
                    <script type="IN/Login"></script>
                </div>
            </div>
        </div>
        <div class="container">
            <div id="content">
                <div id="user-profile">
                    <div id="profile-img">
                        <label id="profilePicture"></label>
                    </div>
                    <div id="profile-info">
                        <div class='stars-profile'></div>
                        <div id="full-name">
                            <label id="firstName"></label>
                            <label id="lastName"></label>
                        </div>
                        <label id="headline"></label>
                        <label> <a href="#" onclick="getMore()">Read more</a></label>
                    </div>
                </div>
                <div id="seeMore">
                    <div class="row">
                        <div id="hidden" class='hidden'></div>
                        <div class="col-sm-8">
                            <div>
                                <h3>Connections </h3>
                                <span id="connections"></span>
                            </div>
                            <div>
                                <h3>Location </h3>
                                <span id="location"></span>
                            </div>
                            <div>
                                <h3>Summary </h3>
                                <span id="summary"></span>
                            </div>
                            <div>
                                <h3>Experience </h3>
                            </div>

                        </div>
                        <div class="col-sm-4"></div>  
                    </div> 
                    <div id='hidden'></div>
                     <button type="button" onclick='callPHP()' class="btn btn-primary">Extract Profile Info</button>
                </div>  
            </div>
        </div>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript">
                            function callPHP() {
                                var postData = $('#hidden').html();
                                var test = postData;
                                $.ajax({
                                    type: "POST",
                                    url: "functions.php",
                                    data: {'categories': test},
                                    success: function (output) {
                                        window.location = 'export.csv';
                                    }
                                });
                            }
                            ;

                            function onLinkedInLoad() {
                                var data = IN.Event.on(IN, "auth", getProfileData);
                            }

                            function onSuccess(data) {
                                //console.log(data);
                                $('#login').hide();
                                $('#content').show();
                                IN.API.Profile("me").result(ShowProfileData);
                                getReviews();
                            }

                            function onError(error) {
                                console.log(error);
                            }

                            function getProfileData() {
                                IN.API.Raw("/people/~").result(onSuccess).error(onError);
                            }

                            function getMore() {
                                IN.API.Raw("/people/~:(num-connections,industry,location,summary,public-profile-url,positions:(title,summary,start-date,end-date,is-current,company:(name,type,size,industry,ticker)))").result(displayConnections).error(onError);
                            }

                            function ShowProfileData(profiles) {
                                var member = profiles.values[0];
                                $("#firstName").html(member.firstName);
                                $("#lastName").html(member.lastName);
                                $("#headline").html(member.headline);
                                var img = $('<img />', {src: member.pictureUrl});
                                $("#profilePicture").html(img);

                                var str_json = JSON.stringify(member)
                                $("#hidden").html(str_json);
                            }

                            function displayConnections(connections) {
                                $('#seeMore').show();
                                $("#connections").html(connections.numConnections);
                                $("#location").html(connections.location.name);
                                $("#summary").html(connections.summary);


                                var len = (connections.positions.values).length;
                                for (i = 0; len > i; i++)
                                {
                                    $('#seeMore .col-sm-8').append('<div><h4>' + connections.positions.values[i].company.name + '</h4><span>' + connections.positions.values[i].title + '</span><span>' + connections.positions.values[i].summary + '</span></div>')
                                }
                            }

                            $("#logout").click(function () {
                                IN.User.logout(function () {
                                    window.location = "login.php";
                                });
                            });
        </script>
    </body>
</html>



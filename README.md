# Sign In with LinkedIn
This version includes the Sign in functionality using the LinkedIn API. Once signed in, the application can retrieve your profile data and the user can extract it in a CSV format

Before you get started …:</br></br>
-Create a developer profile on LinkedIn from <a href="https://developer.linkedin.com/">here</a> </br>
-From the top menu chose "My Apps" and start creating your first app </br>
-Once you have filled out all the fields you will get your unique Authentification Keys (Save them for a later usage) </br>
-Fill out the OAuth (2.0 and 1.0) fields with your test environment. (If you use the local host you must start the link with http://localhost:'your port'/)
</br>

<b>Step 1 - Initialize the SDK</b></br>
Place the following script block into the <head> section of your HTML and provide all of the argument values that are relevant to your application's needs:</br>

        <script type="text/javascript" src="//platform.linkedin.com/in.js">
            api_key: [API_KEY] 
            onLoad: onLinkedInLoad
        </script>

<b>Step 2 - Create the "Sign In with LinkedIn" button</b></br>
Place the following special script block in your HTML wherever you want the "Sign in with LinkedIn" button to be rendered.

                            <script type="IN/Login"></script>
<b>Step 3 - Handle async authentication & retrieve basic member data</b></br>
Use the onLoad argument in the SDK's script block when you initialize the SDK to choose a function to execute once the SDK has finished loading. 
                            
                            function onLinkedInLoad() {
                                var data = IN.Event.on(IN, "auth", getProfileData);
                            }
                            // Handle the successful return from the API call
                            function onSuccess(data) {
                                //console.log(data);
                                $('#login').hide();
                                $('#content').show();
                                IN.API.Profile("me").result(ShowProfileData);
                                getReviews();
                            };
                            // Handle an error response from the API call
                            function onError(error) {
                                console.log(error);
                            };
                            // Use the API call wrapper to request the member's basic profile data
                            function getProfileData() {
                                IN.API.Raw("/people/~").result(onSuccess).error(onError);
                            }
                            
<b>Step 4 - Retrieve the profile data</b></br>
During your authentication process, you will need to request the r_basicprofile member permission in order for your application to successfully make the API call to sign in with LinkedIn.

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
<b>Step 5 - Export profile data in a CSV file</b></br>

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

<?php

    // check to see if our cookie is set
    if (!isset($_COOKIE['apistarter'])) {
        $message = setInitialCookie();
    } else {
        $message = "<h2>You have a token</h2><br />Your token is already saved in a cookie.";
    }

    //----------------------------------------------------------------------------------------------------
    function setInitialCookie() {

        // In a later lesson we'll load our app constants from an INI file and database table,
        // and take these pieces of information out of the code.
        define("CONST_API_KEY", "d7e1a3d7dd2a43a4");
        define("CONST_API_HOST", "http://localhost/phpapistarter/");

        // get an API token
        $apiFunctionName = 'getToken';
        $postDataJSONArray = 'apiFunctionName=' . urlencode($apiFunctionName) . '&';
        $postDataJSONArray .= 'apiFunctionParams=' . '{"api_key":"' . urlencode(CONST_API_KEY).'"}';

        $res = file_get_contents(CONST_API_HOST."?".$postDataJSONArray);
        $resArray = json_decode($res, true);  

        if ($resArray['response'] == '200') {

            // extract the goodies
            $apiToken = $resArray['dataArray']['token'];
            $apiHost = CONST_API_HOST;
            $acceptedCookie = FALSE;
            
            // reset our cookie with a token,
            $cookieJSONArray = '{';
            $cookieJSONArray .= '"apiKey":"'.CONST_API_KEY.'",'; 
            $cookieJSONArray .= '"apiToken":"'.$apiToken.'",';
            $cookieJSONArray .= '"apiHost":"'.$apiHost.'",';
            $cookieJSONArray .= '}'; 

            // store the token in a cookie. It will be managed from the client side after this.
            setrawcookie("apistarter", urlencode($cookieJSONArray), 0, "/");

            $message = "<h2>Your new token is:</h2><br />".$apiToken;
            return $message;

        } else {
            // error
            echo "Error occurred while tyring to connect to API. " . $resArray['responseDescription'];
            die();
        }
    }
?>
<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>PHP API Starter Kit</title>
        <meta name="description" content="PHP API Starter Kit">
        <meta name="robots" content="noindex, nofollow">
        
        <!-- Add Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    </head>

    <body class="pt-5">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">&nbsp;</nav>

        <main role="main" class="container">
            <h1>PHP Restful API Starter Kit - Client</h1>
            <p class="mt-5">
                <div class="mb-3" id="notificationMessage"><?php echo $message; ?></div>
                <button class="btn btn-primary" id="deleteCookie">Delete the Cookie</button>
                <button class="btn btn-primary" id="getJQueryToken" disabled>Use jQuery Call for Token</button>
            </p>
        </main>

        <!-- Load JavaScript: jQuery, Popper, Bootstrap,  -->      
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script src="js/apiHandler.js" type="text/javascript"></script>
        <script src="js/cookieHandler.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(function () {
                // delete cookie
                $('#deleteCookie').click(function(event) {
                    event.preventDefault();
                    deleteCookie('apistarter');
                    $('#deleteCookie').prop('disabled', true);
                    $('#getJQueryToken').prop('disabled', false);
                    $('#notificationMessage').html("<h2>Cookie has been deleted.</h2><br />Refresh the page to cause PHP to call the API and get you a new token.<br />Or use the button below to have jQuery make the call.");
                });
                // get token via javascript and create cookie
                $('#getJQueryToken').click(function(event) {
                    event.preventDefault();
                    const apiKey = 'd7e1a3d7dd2a43a4';
                    const apiHost = 'http://localhost/phpapistarter/';
                    const apiFunctionName = 'getToken';
                    const apiFunctionParams = {api_key: apiKey};
                    
                    apiHandler(apiKey, apiHost, 'GET', apiFunctionName, JSON.stringify(apiFunctionParams), (jsonData) => {
                        res = jsonData.response;
                        if (res === '200') {
                            
                            apiToken = jsonData.dataArray.token;
                            
                            // reset our cookie with a token,
                            cookieArray = {
                                apiKey: apiKey, 
                                apiToken: apiToken, 
                                apiHost: apiHost,
                            };
                            encodedCookie = encodeCookie(JSON.stringify(cookieArray));
                            setCookie('apistarter', encodedCookie);
                            $('#notificationMessage').html("<h2>Your new token is:</h2><br />" + apiToken);
                            $('#deleteCookie').prop('disabled', false);
                            $('#getJQueryToken').prop('disabled', true);
                        }
                    });


                });
            });
        </script>  

    </body>

</html>
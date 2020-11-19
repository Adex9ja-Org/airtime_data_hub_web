<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{ config('app.name') }}</title>
        <style type="text/css">
            .main-content{
                background: #e2e2e2;
                width: 100%;
                margin: 0 auto;
                font-family: Calibri,serif;
            }
            .blue-line{ background-color: #811401; height: 3px; }
            .center{ text-align: center; }
            .container{  padding: 20px}
            .single-line{  height: 1px; }
            .grey-background{ background: #e2e2e2 }
            .dark-background {background: darkgray}
            .white-background{ background: white;}
            td:nth-child(2){ text-align: right}
            td { border-bottom: 1px solid lightgray; padding: 10px}
            .no-decoration{text-decoration: none; color: blue}
        </style>
        @yield('style')
    </head>
    <body>
        <div class="main-content">
            <center><img src="http://admin.airtimedatahub.com/images/logo.png" width="120px" height="120px"/></center>
            <div class="blue-line"></div>
            <div class="container white-background">
                @yield('content')
            </div>
            <div class="single-line dark-background"></div>
            <div class="container">
                <p class="center">Have questions or need help? Kindly contact us via <a href="mailto://support@airtimedatahub.com">support@airtimedatahub.com</a> or visit our <a href="https://airtimedatahub.com/faqs/" class="no-decoration">FAQ</a> page</p>
                <p class="center">Powered by: <a href="https://codefixbug.com" >CodeFixBug</a></p>
                <p class="center"> {{ date('Y') }} &copy; AirtimeData Hub</p>
            </div>

        </div>
    </body>
</html>

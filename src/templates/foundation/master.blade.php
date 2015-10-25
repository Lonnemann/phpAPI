<!DOCTYPE html>
<html lang="de">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}"/>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/css/foundation.min.css">


        <title>::Name-@yield('title')</title>

    </head>

    <body id="top">
        <header>
        </header>
            <div id="wrapper">
                <div id="content_wrapper">
                    @yield('content')
                </div>
            </div>
        <footer>
        </footer>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/js/foundation.min.js"></script>
        <script>
            $(document).foundation();
        </script>
        @yield('addtional-scripts')
    </body>

</html>

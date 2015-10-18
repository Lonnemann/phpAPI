<!DOCTYPE html>
<html lang="de">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}"/>

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
        @yield('addtional-scripts')
    </body>

</html>

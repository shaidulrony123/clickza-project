<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard — Portfolio Admin</title>
  
  @include('backend.layouts.include.header')
</head>
<body>


@include('backend.layouts.include.sidebar')


<!-- ══ MAIN CONTENT ══ -->
<main class="main-content" id="mainContent">
@include('backend.layouts.include.nav')
    
    @yield('content')

</main>

@include('backend.layouts.include.footer')

</body>
</html>
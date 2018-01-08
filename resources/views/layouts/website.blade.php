<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>大創娃娃屋 | @yield('title')</title>
    @include('website.partials.header')
  </head>

  <body>
      @include('website.partials.navigation')
      {{--  @include('website.partials.banner')  --}}
      <div class="container">
            @yield('content')
      </div>
      @include('website.partials.footer') 
   </body>

</html>

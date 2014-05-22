<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
      <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <title>
                  @section('title')
                  Primer Server
                  @show        
            </title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width">
            {{ HTML::style('css/bootstrap.css') }}
            {{ HTML::style('css/bootstrap-theme.css') }}
            {{ HTML::style('css/main.css') }}
            {{ HTML::script('js/vendor/modernizr-2.6.2-respond-1.1.0.min.js') }}
            <script>
                  var base_url = '{{ URL::to("/") }}';
            </script>
      </head>
      <body>
            <!--[if lt IE 7]>
                <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
            <![endif]-->
            <div class="navbar navbar-inverse navbar-static-top">
                  <div class="container">
                        <div class="navbar-header">

                              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                              </button>

                              {{ HTML::linkRoute('inicio','Primer Server',null,array('class'=>'navbar-brand')) ;}}"
                        </div>

                        <div class="navbar-collapse collapse">
                              <ul class="nav navbar-nav">  
                                    <li>
                                          {{ HTML::linkRoute('nosotros','Nosotros') ;}}
                                    </li>
                                    <li>
                                          {{ HTML::linkRoute('contacto','Contáctanos') ;}}                                    
                                    </li>                        
                              </ul>
                              @if(Auth::check())
                              <ul class="nav navbar-nav navbar-right">
                                    <li class="dropdown">
                                          <a href="#" class='dropdown-toggle' data-toggle='dropdown'>{{Auth::user()->email}} <b class="caret"></b></a>
                                          <ul class="dropdown-menu">
                                                @if(Auth::user()->is_admin)
                                                <li>{{ Html::linkRoute('admin.usuarios.index','Usuarios') ;}}</li> 
                                                <li>{{ Html::linkRoute('admin.planes.index','Planes') ;}}</li> 
                                                @else
                                                <li>{{ Html::linkRoute('usuario/inicio','Inicio') ;}}</li>
                                                @endif                                                
                                                <li>{{ Html::linkRoute('usuario/logout','Salir') ;}}</li>                                 
                                          </ul>
                                    </li>
                              </ul>
                              @else
                              {{ Form::open(array('url'=>'usuario/login','class'=>'navbar-form navbar-right','role'=>'form')) ;}}

                              <div class="form-group">
                                    <input type="text" name="correo" placeholder="Email" class="form-control">
                              </div>
                              <div class="form-group">
                                    <input type="password" name="password" placeholder="Password" class="form-control">
                              </div>
                              <button type="submit" class="btn btn-success">Entrar</button>

                              {{ Form::close() ;}}
                              @endif
                        </div><!--/.navbar-collapse -->
                  </div>
            </div>

            @if(Session::has('message'))
            <div class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{ Session::get('message') }}
                  {{ Session::forget('message'); }}        
            </div>                        
            @endif
            @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  {{ Session::get('error') }}
                  {{ Session::forget('message'); }}
            </div>                    
            @endif

            {{Session::get('dominio')}}
            
            @yield('content')

            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.js"></script>                
            {{ HTML::script('js/vendor/bootstrap.min.js') }}
            {{ HTML::script('js/main.js') }}
            @section('scripts')

            @show
      </body>
</html>

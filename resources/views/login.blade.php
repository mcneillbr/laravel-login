<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="_token" content="{{ csrf_token() }}" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" crossorigin="anonymous">
  <link type="text/css" rel="stylesheet" href="css/jsgrid/jsgrid.min.css" />
  <link type="text/css" rel="stylesheet" href="css/jsgrid/jsgrid-theme.min.css" />
  <style type="text/css">
  .error{
    font-size: 8pt;
    color: firebrick;
}
    #navUserlogin {
      min-width: 250px;
      padding: 14px 14px 0;
      overflow: hidden;
      background-color: rgba(255, 255, 255, .8);
      -webkit-box-shadow: 9px 9px 11px 1px rgba(120, 120, 120, 1);
      -moz-box-shadow: 9px 9px 11px 1px rgba(120, 120, 120, 1);
      box-shadow: 9px 9px 11px 1px rgba(120, 120, 120, 1);
    }
  </style>
  <title>sistema básico de criação de usuários</title>
  <script type="text/javascript" src="//code.jquery.com/jquery-3.2.1.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="//cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/jsgrid/jsgrid.min.js"></script>
  <script type="text/javascript" src="js/jsgrid/i18n/jsgrid-pt-br.js"></script>

</head>

<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">
        <i class="fa fa-users fa-lg"></i>
      </a>
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class="active hidden"><a href="#">Link a <span class="sr-only">(current)</span></a></li>
          <li class="hidden"><a href="#">Link b</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li>
            <p class="navbar-text">&nbsp;</p>
          </li>
          <li class="dropdown hidden" id="menuLogout">
            <a href="#" id="menuLogoutTitle" class="dropdown-toggle" data-toggle="dropdown"><b>{{ Auth::check() ? Auth::user()->name : ''}}</b> <span class="caret"></span></a>
            <ul id="navUserlogout" class="dropdown-menu">
              <li>
                <div class="row">
                  <div class="col-md-12">
                    <form class="form" role="form" method="GET" action="{{route('usr.logout')}}" id="frmUserlogout" name="frmUserlogout">
                      <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">
                           <span class="fa fa-sign-in icon"></span> <span class="text">Sair</span>
                         </button>
                      </div>
                    </form>

                  </div>
                </div>
              </li>
            </ul>
            <li class="dropdown hidden" id="menuLogin">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
            <ul id="navUserlogin" class="dropdown-menu">
              <li>
                <div class="row">
                  <div class="col-md-12">
                    <form class="form" role="form" method="post" action="{{route('usr.login')}}" id="frmUserlogin" name="frmUserlogin">
                      <div class="form-group">
                        <label class="sr-only" for="frmUserloginEmail">Email</label>
                        <input type="email" class="form-control" id="frmUserloginEmail" name="email" placeholder="e-mail" required>
                      </div>
                      <div class="form-group">
                        <label class="sr-only" for="frmUserloginPassword">Password</label>
                        <input type="password" class="form-control" id="frmUserloginPassword" name="password" placeholder="senha" required>
                        <div class="help-block text-center">
                          <label class=""><input type="checkbox" name="remember" > Lembre de mim</label>
                        </div>
                      </div>
                      <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">
                           <span class="fa fa-sign-in icon"></span> <span class="text">Entrar</span>
                         </button>
                      </div>
                      <div class="hidden">
                        {{ csrf_field() }}
                      </div>
                    </form>
                  </div>
                </div>
              </li>
            </ul>

          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <div id="alerts" class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1" id="jsGrid"></div>
    </div>

  </div>
  <!-- /.modal -->
  <div id="userDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userDialogTitle">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="userDialogTitle">Modal title</h4>
        </div>
        <div class="modal-body">
          <form id="frmUserDialog" name="frmUserDialog" class="form-horizontal" role="form" method="POST" action="">
            <div class="form-group">
              <label for="name" class="col-md-4 control-label">Name</label>
              <div class="col-md-6">
                <input id="user_name" type="text" class="form-control" name="name" value="" required autofocus>
              </div>
            </div>
            <div class="form-group">
              <label for="email" class="col-md-4 control-label">E-Mail</label>
              <div class="col-md-6">
                <input id="user_email" type="email" class="form-control" name="email" value="" required>
              </div>
            </div>
            <div class="form-group">
              <label for="email" class="col-md-4 control-label">Estado</label>
              <div class="col-md-6">
                <label class="">
                  <input id="user_state" name="state" type="checkbox" autocomplete="off" value="true" checked>
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="password" class="col-md-4 control-label">Senha</label>
              <div class="col-md-6">
                <input id="user_password" type="password" class="form-control" name="password" required>
              </div>
            </div>
            <div class="form-group">
              <label for="password-confirm" class="col-md-4 control-label">Confirmar a senha</label>
              <div class="col-md-6">
                <input id="user_password_confirmation" type="password" class="form-control" name="password_confirmation" required>
              </div>
            </div>
            <div class="hidden">
              <input id="user_id" name="id" value="" type="hidden" >
                {{ csrf_field() }}
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="btnuserDialogClose" data-dismiss="modal">Fechar</button>
          <button type="button" id="btnuserDialogSave" class="btn btn-primary">Salvar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <script type="text/javascript" src="js/base.js"></script>
  @php
  $showmenu = Auth::check() ? '#menuLogout' : '#menuLogin';
  @endphp
  <script type="text/javascript">
  $(function(){
    $('{{$showmenu}}').removeClass('hidden');
  });
  </script>
</body>

</html>

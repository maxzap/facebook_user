<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Facebook Profile</title>
  </head>
  <body>
    <form class="" action="{{ route('detalle_perfil') }}" method="POST">
      {{ csrf_field() }}
      @if(count($errors)>0)
    	<p>
    		<ul>
    			@foreach($errors as $error)
    				<li style="color:red">{{ $error }}</li>
    			@endforeach
    		</ul>
    	</p>
    	@endif
      <br><br>
      <label for="id">ID de Usuario</label>
      <input type="number" name="id" value="">
      <button type="submit" name="enviar">Buscar</button>
    </form>
    <br><br>
    <div class="users">
      @isset($users)
        <p>
          <ul>
            @foreach($users as $user)
              <li style="color:green">{{ "Apellido: " . $user->last_name }} {{ " - Nombre: " . $user->first_name }} {{ " / Id: " . $user->user_id }}</li>
            @endforeach
          </ul>
        </p>
      @endisset
    </div>
  </body>
</html>

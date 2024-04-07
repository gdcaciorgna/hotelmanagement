@include('layouts.header')
<div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="text-center text-dark mt-5">Inicio de sesión</h2>
        <div class="text-center mb-5 text-dark">Hotel Management</div>
        <div class="card my-5">

          <form class="card-body cardbody-color p-lg-5">

            <div class="text-center">
              <img src="{{asset('/img/profile.png')}}" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                width="150px" alt="profile">
            </div>

            <div class="mb-3">
              <input type="text" class="form-control" id="Email" aria-describedby="emailHelp"
                placeholder="Email">
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" id="password" placeholder="Contraseña">
            </div>
            <div class="text-center"><button type="submit" class="btn btn-color px-5 mb-5 w-100">Iniciar sesión</button></div>
            <div id="emailHelp" class="form-text text-center mb-5 text-dark">¿No estás registrado? <a href="#" class="text-dark fw-bold">Contactarte con el recepcionista del hotel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @include('layouts.footer')

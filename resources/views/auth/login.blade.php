@include('layouts.header')
<div class="container-xxl position-relative bg-white d-flex p-0">
  <!-- Spinner Start -->
  <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
      <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Cargando...</span>
      </div>
  </div>
  <!-- Spinner End -->


  <!-- Sign In Start -->
    <div class="container-fluid">
        <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                <form method="POST" action="{{ route('login') }}">
                @csrf

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3>Iniciar sesión</h3>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"  name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="juanperez@gmail.com">
                        <label for="email">Email</label>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password"placeholder="Contraseña">
                        <label for="password">Contraseña</label>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                
                    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Iniciar sesión</button>
                    <p class="text-center mb-0">¿No tenés una cuenta? <a href="tel:+5215512345678">Contactate con recepción</a></p>
                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Sign In End -->
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('lib/chart/chart.min.js')}}"></script>
<script src="{{asset('lib/easing/easing.min.js')}}"></script>
<script src="{{asset('lib/waypoints/waypoints.min.js')}}"></script>
<script src="{{asset('lib/tempusdominus/js/moment.min.js')}}"></script>
<script src="{{asset('lib/tempusdominus/js/moment-timezone.min.js')}}"></script>
<script src="{{asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js')}}"></script>

<!-- Template Javascript -->
<script src="{{asset('js/main.js')}}"></script>
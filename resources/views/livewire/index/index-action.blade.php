<div>
    <div class="container py-4">

        {{-- VISTA DE ADMIN --}}
        @if ($privilegio == 1)
            <h2 class="fw-bold mb-4">📊 Indicadores del Sistema</h2>

            <div class="row g-4">
                <!-- Total Usuarios -->
                <div class="col-md-3">
                    <div class="card-indicador position-relative" style="background: #4e8fbb;">
                        <div class="icono">👥</div>
                        <div class="titulo">Total Usuarios</div>
                        <p class="valor">{{ $totalUsuarios }}</p>
                    </div>
                </div>

                <!-- Usuarios Activos -->
                <div class="col-md-3">
                    <div class="card-indicador position-relative" style="background: #59c773;">
                        <div class="icono">✔️</div>
                        <div class="titulo">Usuarios Activos</div>
                        <p class="valor">{{ $activos }}</p>
                    </div>
                </div>

                <!-- Usuarios Inactivos -->
                <div class="col-md-3">
                    <div class="card-indicador position-relative" style="background: #e75b69;">
                        <div class="icono">⛔</div>
                        <div class="titulo">Inactivos</div>
                        <p class="valor">{{ $inactivos }}</p>
                    </div>
                </div>

                <!-- Nuevos Usuarios -->
                <div class="col-md-3">
                    <div class="card-indicador position-relative" style="background: #8a5edd;">
                        <div class="icono">📅</div>
                        <div class="titulo">Nuevos (30 días)</div>
                        <p class="valor">{{ $nuevos }}</p>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>

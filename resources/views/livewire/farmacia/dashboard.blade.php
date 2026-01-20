<div>
    <div class="container-fluid">

        <h5 class="fw-bold text-clinico mb-3">
            📊 Dashboard Farmacia
        </h5>

        <div class="row g-3">

            <!-- MEDICAMENTOS -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted">Medicamentos</small>
                        <h3 class="fw-bold text-primary">
                            {{ $totalMedicamentos }}
                        </h3>
                    </div>
                </div>
            </div>
            <!-- STOCK BAJO -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted">Stock bajo</small>
                        <h3 class="fw-bold text-danger">
                            {{ $stockBajo }}
                        </h3>
                    </div>
                </div>
            </div>
            <!-- COMPRAS HOY -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted">Compras hoy</small>
                        <h3 class="fw-bold text-success">
                            {{ $comprasHoy }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- PROVEEDORES -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted">Proveedores</small>
                        <h3 class="fw-bold text-secondary">
                            {{ $totalProveedores }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- KARDEX -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted">Kardex</small>
                        <h3 class="fw-bold text-secondary">
                            {{ $totalKardex }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

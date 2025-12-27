<div>

    <!-- ===== NAV BAR INTERNO ===== -->
    <ul class="nav nav-pills nav-clinico mb-3">

        <li class="nav-item">
            <a href="#"
               class="nav-link {{ $tab === 'dashboard' ? 'active' : '' }}"
               wire:click.prevent="setTab('dashboard')">
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="#"
               class="nav-link {{ $tab === 'medicamentos' ? 'active' : '' }}"
               wire:click.prevent="setTab('medicamentos')">
                Medicamentos
            </a>
        </li>

        <li class="nav-item">
            <a href="#"
               class="nav-link {{ $tab === 'proveedores' ? 'active' : '' }}"
               wire:click.prevent="setTab('proveedores')">
                Proveedores
            </a>
        </li>

        <li class="nav-item">
            <a href="#"
               class="nav-link {{ $tab === 'compras' ? 'active' : '' }}"
               wire:click.prevent="setTab('compras')">
                Compras
            </a>
        </li>

    </ul>

    <!-- ===== CONTENIDO DINÁMICO ===== -->
    <div class="card card-clinica">
        <div class="card-body">

            @if($tab === 'dashboard')
                <livewire:farmacia.dashboard />
            @elseif($tab === 'medicamentos')
                <livewire:farmacia.medicamentos />
            @elseif($tab === 'proveedores')
                <livewire:farmacia.proveedores />
            @elseif($tab === 'compras')
                <livewire:farmacia.compras />
            @endif

        </div>
    </div>

</div>

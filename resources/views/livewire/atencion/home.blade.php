<div>

    <div class="max-w-2xl mx-auto p-12">

        <div class="container">
            <div id="clasepadre">
                <div class="form-wrap">
                    <form id="survey-form">

                        {{-- TABS BOOTSTRAP --}}
                        <ul class="nav nav-tabs tabs-clinica" id="atencionTabs">
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'info' ? 'active' : '' }}"
                                    wire:click="cambiarTab('info')">Info </a>
                            </li>
                            @if ($privilegio == 5)
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'medico' ? 'active' : '' }}"
                                        wire:click="cambiarTab('medico')">Medico</a>
                                </li>
                            @endif

                            @if ($privilegio == 5)
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'servicios' ? 'active' : '' }}"
                                        wire:click="cambiarTab('servicios')">Servicios</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 'signos' ? 'active' : '' }}"
                                    wire:click="cambiarTab('signos')">Signos Vitales</a>
                            </li>
                            @if ($privilegio == 2 || $privilegio == 5)
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'consulta' ? 'active' : '' }}"
                                        wire:click="cambiarTab('consulta')">Consulta</a>
                                </li>
                            @endif
                            @if ($privilegio == 2 || $privilegio == 5)
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'medicamentos' ? 'active' : '' }}"
                                        wire:click="cambiarTab('medicamentos')">Medicamentos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'laboratorio' ? 'active' : '' }}"
                                        wire:click="cambiarTab('laboratorio')">Laboratorio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'imagen' ? 'active' : '' }}"
                                        wire:click="cambiarTab('imagen')">Imágen</a>
                                </li>
                            @endif
                            @if ($privilegio == 2)
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'insumos' ? 'active' : '' }}"
                                        wire:click="cambiarTab('insumos')">Insumos</a>
                                </li>
                            @endif
                            @if ($privilegio == 2 || $privilegio == 5)
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'resultados' ? 'active' : '' }}"
                                        wire:click="cambiarTab('resultados')">Resultados</a>
                                </li>
                            @endif
                            @if ($privilegio == 5)
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 'facturacion' ? 'active' : '' }}"
                                        wire:click="cambiarTab('facturacion')">Facturación</a>
                                </li>
                            @endif
                        </ul>

                        <div class="mt-3">

                            {{-- AQUI SE CARGA SOLO EL COMPONENTE SELECCIONADO --}}
                            @livewire($componente, ['id_atencion' => $id_atencion], key($componente))

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

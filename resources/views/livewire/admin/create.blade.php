<div class="container">
    <div id="clasepadre">
        <div class="form-wrap">
            <form id="survey-form">
                <h4>Datos Personales</h4>
                <hr>
                <div class="row">

                    <!-- NOMBRES -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nombres</label>
                            <input type="text" wire:model="nombres" placeholder="Ej: Juan Carlos" class="form-control"
                                required>
                            @error('nombres')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- APELLIDO PATERNO -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Apellido Paterno</label>
                            <input type="text" wire:model="apellido_paterno" placeholder="Ej: Pérez"
                                class="form-control" required>
                            @error('apellido_paterno')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- APELLIDO MATERNO -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Apellido Materno</label>
                            <input type="text" wire:model="apellido_materno" placeholder="Ej: Gómez"
                                class="form-control">
                            @error('apellido_materno')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- TIPO DOCS -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>TIPO DOCUMENTO</label>
                            <select wire:model="tipo_documento" class="form-control" required>
                                <option value="1">DNI</option>
                                <option value="2">Carné de Extranjería</option>
                                <option value="3">Pasaporte</option>
                            </select>
                            @error('tipo_documento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- DNI -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>DNI</label>
                            <input type="text" wire:model="dni" maxlength="12" placeholder="Número de DNI"
                                class="form-control" required>
                            @error('dni')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- GENERO -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Género</label>
                            <select wire:model="genero" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            @error('genero')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- DIRECCION -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" wire:model="direccion" class="form-control" required>
                            @error('direccion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- TELEFONO -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" wire:model="telefono" maxlength="9" class="form-control" required>
                            @error('telefono')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if ($privilegio == 1)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="name-label" for="foto_url">Foto Perfil</label>
                                <input type="file" wire:model='foto_url' accept="image/x-png,image/gif,image/jpeg"
                                    placeholder="Adjuntar foto" class="form-control" required>
                                @error('foto_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="name-label" for="firma_url">Firma Profesional</label>
                                <input type="file" wire:model='firma_url' accept="image/x-png,image/gif,image/jpeg"
                                    placeholder="Adjuntar firma" class="form-control" required>
                                @error('firma_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="name-label" for="fecha_nacimiento">Fecha de Nacimiento</label>
                            <input type="date" wire:model='fecha_nacimiento'
                                placeholder="seleccionar fecha de nacimiento" maxlength="9" class="form-control date"
                                required>
                            @error('fecha_nacimiento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                @if ($privilegio == 1)

                    <h4>Datos Profesionales {{ $privilegio }}</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="name-label" for="name">Nombre de Cargo</label>
                                <select wire:model="nombre_cargo" wire:change='showDetailCargo()'
                                    class="form-control" id="" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Doctor">Doctor (a)</option>
                                    <option value="Enfermero">Enfermero (a)</option>
                                    <option value="Laboratorio">Laboratorio</option>
                                    <option value="Recepcionista">Recepcionista</option>
                                    <option value="Farmaceutico">Farmaceutico (a)</option>
                                    <option value="Paciente">Paciente (a)</option>

                                </select>
                                @error('nombre_cargo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($showDetail)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="name-label" for="especialidad_cargo">Especialidad</label>
                                    <input type="text" wire:model='especialidad_cargo' placeholder="Especialidad"
                                        class="form-control" required>
                                    @error('especialidad_cargo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="name-label" for="colegiatura_cargo">Colegiatura</label>
                                    <input type="text" wire:model='colegiatura_cargo' placeholder="Colegiatura"
                                        class="form-control" required>
                                    @error('colegiatura_cargo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                    <h4>Acceso</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="email-label" for="email">Correo</label>
                                <input type="email" wire:model='email' placeholder="Nombre de usuario"
                                    class="form-control" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="email-label" for="email">Contraseña</label>
                                <input type="password" wire:model='password' placeholder="Ingresar contraseña"
                                    class="form-control" required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-actions">
                    <div class="text-left">
                        <button wire:click="agregar" wire:loading.attr="disabled" class="btn btn-primary btn-sm"
                            type="button"> <i class="fa fa-plus-circle"></i> <i wire:target="agregar"
                                wire:loading.class="fa fa-spinner fa-spin" aria-hidden="true"></i> Agregar
                            Usuario</button>

                        <button wire:click="default" wire:loading.attr="disabled" class="btn btn-secondary btn-sm"
                            type="button"> <i wire:target="default" wire:loading.class="fa fa-spinner fa-spin"
                                aria-hidden="true"></i>Limpiar</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

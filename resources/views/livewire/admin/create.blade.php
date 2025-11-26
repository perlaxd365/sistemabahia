<div class="container">
    <div id="clasepadre">
        <div class="form-wrap">
            <form id="survey-form">
                <h4>Datos Personales</h4>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="name-label" for="name">Nombres</label>
                            <input type="text" wire:model='name' placeholder="Nombres completos" class="form-control"
                                required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="email-label" for="email">DNI</label>
                            <input type="text" wire:model='dni' placeholder="Ingresar número de dni" maxlength="8"
                                class="form-control" required>
                            @error('dni')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="name-label" for="name">Dirección</label>
                            <input type="text" wire:model='direccion' placeholder="Dirección" class="form-control"
                                required>
                            @error('direccion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="email-label" for="email">Teléfono</label>
                            <input type="text" wire:model='telefono' placeholder="Ingresar número de teléfono"
                                maxlength="9" class="form-control" required>
                            @error('telefono')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="name-label" for="foto_url">Foto Perfil</label>
                            <input type="file" wire:model='foto_url'  accept="image/x-png,image/gif,image/jpeg" placeholder="Adjuntar foto"
                                class="form-control" required>
                            @error('foto_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>



                <h4>Datos Profesionales</h4>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="name-label" for="name">Nombre de Cargo</label>
                            <select wire:model="nombre_cargo" wire:change='showDetailCargo()' class="form-control"
                                id="" required>
                                <option value="">Seleccionar</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Doctor">Doctor (a)</option>
                                <option value="Enfermero">Enfermero (a)</option>
                                <option value="Recepcionista">Recepcionista</option>
                                <option value="Farmaceutico">Farmaceutico (a)</option>

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

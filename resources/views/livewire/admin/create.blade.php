
<div class="container">
    <div id="clasepadre">
        <div class="form-wrap">
            <form id="survey-form">
                <h4>Creación</h4>
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
                        <div class="form-group" >
                            <label id="name-label" for="name">Colegio</label>
                            <div wire:ignore>
                                  <select  wire:model="id_colegio" class="form-control select2" id="select2" required>
                                <option value="">Seleccionar</option>
                               
                            </select>
                            </div>
                          
                            @error('id_colegio')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="name-label" for="name">Rol</label>
                            <select wire:model="id_tipo_usuario" class="form-control select2 " id="" required>
                                <option value="">Seleccionar</option>
                               
                            </select>
                            @error('id_tipo_usuario')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
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

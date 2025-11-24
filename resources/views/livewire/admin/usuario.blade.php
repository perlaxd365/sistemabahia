<div class="contenedor" id="top">
    @include('livewire.admin.' . $view)
    <br>
    @if ($table)
        @include('livewire.admin.list')
    @endif

</div>
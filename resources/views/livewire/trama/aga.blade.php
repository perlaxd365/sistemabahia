<div>

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <strong>Reporte Infraestructura IPRESS</strong>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-sm">

                <thead class="table-light">

                    <tr>

                        <th>Periodo</th>
                        <th>IPRESS</th>
                        <th>UGIPRESS</th>
                        <th>Consultorios Físicos</th>
                        <th>Consultorios Funcionales</th>
                        <th>Camas</th>
                        <th>Total Médicos</th>
                        <th>Serums</th>
                        <th>Residentes</th>
                        <th>Enfermeras</th>
                        <th>Odontólogos</th>
                        <th>Psicólogos</th>
                        <th>Nutricionistas</th>
                        <th>Tecnólogos</th>
                        <th>Obstetrices</th>
                        <th>Farmacéuticos</th>
                        <th>Auxiliares</th>
                        <th>Otros</th>
                        <th>Ambulancias</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($datos as $row)
                        <tr>

                            <td>{{ $row->periodo_reporte }}</td>
                            <td>{{ $row->codigo_ipress }}</td>
                            <td>{{ $row->codigo_ugipress }}</td>
                            <td>{{ $row->consultorios_fisicos }}</td>
                            <td>{{ $row->consultorios_funcionales }}</td>
                            <td>{{ $row->camas_hospitalarias }}</td>
                            <td>{{ $row->total_medicos }}</td>
                            <td>{{ $row->medicos_serums }}</td>
                            <td>{{ $row->medicos_residentes }}</td>
                            <td>{{ $row->enfermeras }}</td>
                            <td>{{ $row->odontologos }}</td>
                            <td>{{ $row->psicologos }}</td>
                            <td>{{ $row->nutricionistas }}</td>
                            <td>{{ $row->tecnologos_medicos }}</td>
                            <td>{{ $row->obstetrices }}</td>
                            <td>{{ $row->farmaceuticos }}</td>
                            <td>{{ $row->auxiliares_tecnicos }}</td>
                            <td>{{ $row->otros_profesionales }}</td>
                            <td>{{ $row->ambulancias }}</td>

                        </tr>
                    @endforeach

                </tbody>

            </table>


            <hr>

            <h5>Trama generada</h5>

            @foreach ($datos as $row)
                <div class="alert alert-success">

                    {{ implode('|', [
                        $row->periodo_reporte,
                        $row->codigo_ipress,
                        $row->codigo_ugipress,
                        $row->consultorios_fisicos,
                        $row->consultorios_funcionales,
                        $row->camas_hospitalarias,
                        $row->total_medicos,
                        $row->medicos_serums,
                        $row->medicos_residentes,
                        $row->enfermeras,
                        $row->odontologos,
                        $row->psicologos,
                        $row->nutricionistas,
                        $row->tecnologos_medicos,
                        $row->obstetrices,
                        $row->farmaceuticos,
                        $row->auxiliares_tecnicos,
                        $row->otros_profesionales,
                        $row->ambulancias,
                    ]) }}

                </div>
            @endforeach

        </div>
<div>
    <button type="button" wire:click='exportarTxt' > exportar</button>
</div>
    </div>
</div>

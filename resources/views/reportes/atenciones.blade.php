  <h5><b>Atenciones de <p class="text-danger">{{ $name }}</p></b> </h5>
  <table class="table table-responsive">
      <thead class="thead-dark">
          <tr>
              <th scope="col">#</th>
              <th scope="col">Tipo de Atención</th>
              <th scope="col">Fecha Atención</th>
              <th scope="col">fecha finalización</th>
              <th scope="col">Estado</th>
          </tr>
      </thead>
      <tbody>


          @if ($atenciones->count())
              <?php $count = 1; ?>
              @foreach ($atenciones as $atencion)
                  <tr>
                      <th scope="row">{{ $count++ }}</th>
                      <td>{!! $atencion->relato_atencion !!}</td>
                      <td>{{ DateUtil::getFechaCompleta($atencion->fecha_inicio_atencion) }}
                      </td>
                      <td>{{ $atencion->fecha_fin_atencion ? DateUtil::getFechaCompleta($atencion->fecha_fin_atencion) : '' }}
                      </td>
                      <td class="text-center">
                          @if ($atencion->estado == 'PROCESO')
                              <span class="badge bg-success">Activa</span>
                          @elseif($atencion->estado == 'FINALIZADO')
                              <span class="badge bg-secondary">Finalizado</span>
                          @else
                              <span class="badge bg-warning">ANULADO</span>
                          @endif
                      </td>
                  </tr>
              @endforeach
          @else
              <tr>
                  <td>
                      <div class="card-body">
                          <strong>No se registraron atenciones.</strong>
                      </div>
                  </td>
              </tr>
          @endif
      </tbody>
  </table>

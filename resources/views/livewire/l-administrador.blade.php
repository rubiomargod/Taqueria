<div class="card shadow mt-4 p-4 border-0 rounded-4">
  <h4 class="fw-bold mb-3" style="color: var(--ColorPrincipal);">
    <i class="fas fa-cash-register me-2"></i> Ventas por Turno
  </h4>

  <div class="row">
    <div class="col-md-6">
      <h5 class="fw-semibold">Turno 1 (08:00 - 15:00)</h5>
      <table class="table table-bordered table-hover text-center">
        <thead class="table-light">
          <tr>
            <th>Cajero</th>
            <th>Total Vendido</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($ventasTurno1 as $venta)
          <tr>
            <td>{{ $venta['usuario'] }}</td>
            <td>${{ number_format($venta['total'], 2) }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="2">Sin ventas.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="col-md-6">
      <h5 class="fw-semibold">Turno 2 (15:00 - 22:00)</h5>
      <table class="table table-bordered table-hover text-center">
        <thead class="table-light">
          <tr>
            <th>Cajero</th>
            <th>Total Vendido</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($ventasTurno2 as $venta)
          <tr>
            <td>{{ $venta['usuario'] }}</td>
            <td>${{ number_format($venta['total'], 2) }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="2">Sin ventas.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
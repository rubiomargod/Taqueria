<div class="container py-4" style="--bs-body-bg: var(--FondoBase); color: var(--TextoOscuro);">
  <div class="card shadow rounded-4 p-4 border-0" style="background-color: white;">
    <h2 class="text-center mb-4 fw-bold" style="color: var(--ColorPrincipal);">
      <i class="fas fa-table me-2"></i> Ventas Registradas
    </h2>

    {{-- FILTRO POR FECHA --}}
    <div class="row mb-4">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Desde:</label>
        <input type="date" class="form-control" wire:model="fecha_inicio">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Hasta:</label>
        <input type="date" class="form-control" wire:model="fecha_fin">
      </div>
      <div class="col-md-4 d-flex align-items-end gap-2">
        <button class="btn fw-semibold text-white" style="background-color: var(--ColorPrincipal);" wire:click="filtrar">
          <i class="fas fa-filter me-1"></i> Filtrar
        </button>
        <button class="btn text-white fw-semibold" style="background-color: var(--GrisNeutro);" wire:click="limpiarFiltros">
          <i class="fas fa-eraser me-1"></i> Limpiar
        </button>
      </div>
    </div>

    {{-- TABLA DE VENTAS --}}
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead style="background-color: var(--ColorSecundario); color: var(--TextoClaro);">
          <tr>
            <th>ID</th>
            <th>Comanda</th>
            <th>Mesa</th>
            <th>Total</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($ventas as $venta)
          <tr>
            <td>{{ $venta->id }}</td>
            <td>#{{ $venta->comanda->id }}</td>
            <td>Mesa {{ $venta->comanda->mesa->numero }}</td>
            <td>${{ number_format($venta->total, 2) }}</td>
            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5">No hay ventas en este rango.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
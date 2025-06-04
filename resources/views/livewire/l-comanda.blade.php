<div class="p-4" style="background-color: var(--FondoBase);">
  <h2 class="h3 fw-bold mb-5 text-center" style="color: var(--TextoOscuro);">
    <i class="bi bi-journal-check me-2" style="color: var(--ColorPrincipal);"></i> Listado de Comandas
  </h2>
>>>>>>> Stashed changes

  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
    @forelse($comandas as $comanda)
    <div class="col">
      <div class="card h-100 shadow-sm rounded-lg" style="background-color: var(--TextoClaro); border: 1px solid var(--GrisNeutro);">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-3" style="color: var(--ColorPrincipal);">
            Comanda #{{ $comanda->id }}
          </h5>
          <p class="card-text mb-2" style="color: var(--TextoOscuro);">
            <i class="bi bi-person-fill me-2" style="color: var(--ColorAcento);"></i> <strong>Mesero:</strong> {{ $comanda->mesero->name ?? 'N/A' }}
          </p>
          <p class="card-text mb-2" style="color: var(--TextoOscuro);">
            <i class="bi bi-stickies-fill me-2" style="color: var(--ColorSecundario);"></i> <strong>Mesa:</strong> Mesa #{{ $comanda->mesa->numero ?? 'N/A' }}
          </p>
          <p class="card-text mb-3" style="color: var(--TextoOscuro);">
            <i class="bi bi-calendar-fill me-2" style="color: var(--GrisNeutro);"></i> <strong>Fecha:</strong> {{ $comanda->fecha }}
          </p>
          <div class="d-flex gap-2">
            <button wire:click="verDetalles({{ $comanda->id }})" class="btn btn-primary btn-sm" style="background-color: var(--ColorPrincipal); border-color: var(--ColorPrincipal); color: var(--TextoClaro);">
              <i class="bi bi-eye-fill me-1"></i> Ver Detalles
            </button>
            <button wire:click="cerrarVenta({{ $comanda->id }})" class="btn btn-success btn-sm" style="background-color: var(--ColorAcento); border-color: var(--ColorAcento); color: var(--TextoClaro);">
              <i class="bi bi-check-circle-fill me-1"></i> Cerrar Venta
            </button>
          </div>
        </div>
      </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
      <div class="alert alert-info" role="alert" style="background-color: var(--FondoBase); color: var(--GrisNeutro); border-color: var(--GrisNeutro);">
        <i class="bi bi-info-circle-fill me-2"></i> No hay comandas registradas.
      </div>
    </div>
    @endforelse
  </div>
</div>
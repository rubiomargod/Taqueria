<div class="p-4" style="background-color: var(--FondoBase);">
  <h2 class="h3 fw-bold mb-5 text-center" style="color: var(--TextoOscuro);">
    <i class="bi bi-journal-check me-2" style="color: var(--ColorPrincipal);"></i> Listado de Comandas
  </h2>
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
            <button wire:click="AbrirModalDetalles({{ $comanda->id }})" class="btn btn-primary btn-sm" style="background-color: var(--ColorPrincipal); border-color: var(--ColorPrincipal); color: var(--TextoClaro);">
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Livewire.on('AbrirModalDetalles', () => {
        let modal = new bootstrap.Modal(document.getElementById('ModalDetalles'));
        modal.show();
      });

      Livewire.on('CerrarModalDetalles', () => {
        let modal = bootstrap.Modal.getInstance(document.getElementById('ModalDetalles'));
        modal.hide();
      });
    });
  </script>
  <div class="modal fade" id="ModalDetalles" tabindex="-1" aria-labelledby="ModalDetallesLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4" style="background-color: var(--FondoBase);">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="ModalDetallesLabel" style="color: var(--TextoOscuro);">
            <i class="bi bi-journal-text me-2" style="color: var(--ColorPrincipal);"></i> Detalles de Comanda #{{ $comandaId ?? 'N/A' }}
          </h5>
          <button type="button" class="btn-close" wire:click="CerrarModalDetalles" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <div class="mb-4 p-3 rounded" style="background-color: var(--TextoClaro); border: 1px solid var(--GrisNeutro);">
            <h6 class="fw-bold mb-3" style="color: var(--ColorPrincipal);">Información de la Comanda</h6>
            <p class="mb-1" style="color: var(--TextoOscuro);">
              <i class="bi bi-person-fill me-2" style="color: var(--ColorAcento);"></i> <strong>Mesero:</strong> {{ $meseroNombre ?? 'Cargando...' }}
            </p>
            <p class="mb-1" style="color: var(--TextoOscuro);">
              <i class="bi bi-stickies-fill me-2" style="color: var(--ColorSecundario);"></i> <strong>Mesa:</strong> Mesa #{{ $mesaNumero ?? 'Cargando...' }}
            </p>
            <p class="mb-0" style="color: var(--TextoOscuro);">
              <i class="bi bi-calendar-fill me-2" style="color: var(--GrisNeutro);"></i> <strong>Fecha:</strong> {{ $comandaFecha ?? 'Cargando...' }}
            </p>
          </div>

          <h6 class="fw-bold mb-3" style="color: var(--ColorPrincipal);">
            <i class="bi bi-plus-circle-fill me-2"></i> Agregar Productos
          </h6>

          <form wire:submit.prevent="agregarDetalleProducto">
            <div class="row g-3 mb-4 align-items-end">
              <div class="col-md-6">
                <label for="idProducto" class="form-label fw-semibold" style="color: var(--TextoOscuro);">Producto</label>
                <select wire:model.defer="idProducto" id="idProducto" class="form-select shadow-sm">
                  <option value="">-- Selecciona un Producto --</option>
                  @foreach($productosDisponibles as $producto)
                  <option value="{{ $producto->id }}">{{ $producto->nombre }} (${{ number_format($producto->precio, 2) }})</option>
                  @endforeach
                </select>
                @error('idProducto') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
              <div class="col-md-3">
                <label for="cantidad" class="form-label fw-semibold" style="color: var(--TextoOscuro);">Cantidad</label>
                <input type="number" wire:model.defer="cantidad" id="cantidad" class="form-control shadow-sm" min="1" placeholder="Ej. 2">
                @error('cantidad') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100" style="background-color: var(--ColorAcento); border-color: var(--ColorAcento); color: var(--TextoClaro);">
                  <i class="bi bi-cart-plus-fill me-1"></i> Añadir
                </button>
              </div>
            </div>
          </form>

          <h6 class="fw-bold mb-3" style="color: var(--ColorPrincipal);">
            <i class="bi bi-list-check me-2"></i> Productos en Comanda
          </h6>
          @if ($comandaDetalles && count($comandaDetalles) > 0)
          <div class="table-responsive">
            <table class="table table-striped table-hover table-sm" style="background-color: var(--TextoClaro);">
              <thead>
                <tr>
                  <th scope="col" style="color: var(--TextoOscuro);">Producto</th>
                  <th scope="col" class="text-center" style="color: var(--TextoOscuro);">Cantidad</th>
                  <th scope="col" class="text-end" style="color: var(--TextoOscuro);">Subtotal</th>
                  <th scope="col" class="text-center" style="color: var(--TextoOscuro);">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($comandaDetalles as $detalle)
                <tr>
                  <td style="color: var(--TextoOscuro);">{{ $detalle['nombre'] ?? 'N/A' }}</td>
                  <td class="text-center" style="color: var(--TextoOscuro);">{{ $detalle['cantidad'] }}</td>
                  <td class="text-end" style="color: var(--TextoOscuro);">${{ number_format($detalle['cantidad'] * ($detalle['precio'] ?? 0), 2) }}</td>
                  <td class="text-center">
                    <button wire:click="eliminarDetalleProducto({{ $detalle['id'] }})"
                      onclick="confirm('¿Seguro que deseas eliminar este producto de la comanda?') || event.stopImmediatePropagation()"
                      class="btn btn-danger btn-sm" style="background-color: var(--ColorPrincipal); border-color: var(--ColorPrincipal);">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2" class="text-end fw-bold" style="color: var(--TextoOscuro);">Total:</td>
                  <td class="text-end fw-bold" style="color: var(--TextoOscuro);">
                    ${{ number_format(collect($comandaDetalles)->sum(fn($d) => $d['cantidad'] * $d['precio']), 2) }}
                  </td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
          @else
          <div class="alert alert-info text-center" role="alert" style="background-color: var(--FondoBase); color: var(--GrisNeutro); border-color: var(--GrisNeutro);">
            <i class="bi bi-info-circle-fill me-2"></i> Esta comanda no tiene productos aún.
          </div>
          @endif
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" style="background-color: var(--GrisNeutro); color: var(--TextoClaro);" wire:click="CerrarModalDetalles">
            <i class="bi bi-x-circle-fill me-1"></i> Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="ModalVenta" tabindex="-1" aria-labelledby="ModalVentaLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4" style="background-color: var(--FondoBase);">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="ModalVentaLabel" style="color: var(--TextoOscuro);">
            <i class="bi bi-receipt-cutoff me-2"></i> Ticket de Comanda #{{ $comandaId ?? 'N/A' }}
          </h5>
          <button type="button" class="btn-close" wire:click="$dispatch('CerrarModalVenta')" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p style="color: var(--TextoOscuro);">
            <strong>Mesero:</strong> {{ $meseroNombre }} <br>
            <strong>Mesa:</strong> #{{ $mesaNumero }} <br>
            <strong>Fecha:</strong> {{ $comandaFecha }}
          </p>

          <table class="table table-sm table-borderless">
            <thead>
              <tr style="color: var(--ColorPrincipal);">
                <th>Producto</th>
                <th class="text-center">Cant.</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($comandaDetalles as $detalle)
              <tr style="color: var(--TextoOscuro);">
                <td>{{ $detalle['nombre'] }}</td>
                <td class="text-center">{{ $detalle['cantidad'] }}</td>
                <td class="text-end">${{ number_format($detalle['cantidad'] * $detalle['precio'], 2) }}</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr style="color: var(--ColorAcento); font-weight: bold;">
                <td colspan="2" class="text-end">Total:</td>
                <td class="text-end">${{ number_format($totalVenta, 2) }}</td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="modal-footer border-0">
          <button class="btn btn-success" wire:click="confirmarVenta">
            <i class="bi bi-cash-coin me-1"></i> Confirmar Venta
          </button>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Livewire.on('AbrirModalVenta', () => {
        new bootstrap.Modal(document.getElementById('ModalVenta')).show();
      });
      Livewire.on('CerrarModalVenta', () => {
        let modal = bootstrap.Modal.getInstance(document.getElementById('ModalVenta'));
        modal.hide();
      });
    });
  </script>
</div>
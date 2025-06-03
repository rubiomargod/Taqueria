<div class="p-4">
  <h2 class="text-xl font-bold mb-6">Listado de Comandas</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($comandas as $comanda)
    <div class="border rounded-lg shadow p-4 bg-white">
      <div class="text-lg font-semibold mb-2">#{{ $comanda->id }}</div>
      <div class="mb-1">
        <strong>Mesero:</strong> {{ $comanda->mesero->name ?? 'N/A' }}
      </div>
      <div class="mb-1">
        <strong>Mesa:</strong> Mesa #{{ $comanda->mesa->numero ?? 'N/A' }}
      </div>
      <div class="mb-2">
        <strong>Fecha:</strong> {{ $comanda->fecha }}
      </div>
      <div class="flex gap-2">
        <button wire:click="verDetalles({{ $comanda->id }})" class="bg-blue-600 text-white px-3 py-1 rounded">
          Ver Detalles
        </button>
        <button wire:click="cerrarVenta({{ $comanda->id }})" class="bg-green-600 text-white px-3 py-1 rounded">
          Cerrar Venta
        </button>
      </div>
    </div>
    @empty
    <div class="col-span-full text-center text-gray-600">
      No hay comandas registradas.
    </div>
    @endforelse
  </div>
</div>
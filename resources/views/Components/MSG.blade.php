@if (session('success') || session('error'))
<div class="modal fade" id="modalMessages" tabindex="-1" aria-labelledby="modalLabelMessages" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow rounded-lg border-2 {{ session('error') ? 'border-danger' : 'border-success' }}">
      <div class="modal-header {{ session('error') ? 'bg-danger text-white' : 'bg-success text-white' }}">
        <h5 class="modal-title fw-semibold" id="modalLabelMessages">
          @if (session('error'))
          <i class="bi bi-exclamation-triangle-fill me-2"></i> Error
          @else
          <i class="bi bi-check-circle-fill me-2"></i> Éxito
          @endif
        </h5>
      </div>
      <div class="modal-body py-3">
        <p class="mb-0 {{ session('error') ? 'text-danger' : 'text-success' }}">
          @if (session('error'))
          <i class="bi bi-x-octagon-fill me-2"></i> {{ session('error') }}
          @else
          <i class="bi bi-check2-circle me-2"></i> {{ session('success') }}
          @endif
        </p>
      </div>
    </div>
  </div>
</div>
@endif
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('modalMessages');
    if (modalElement) {
      const modalInstance = new bootstrap.Modal(modalElement);
      modalInstance.show();
      setTimeout(() => {
        modalInstance.hide();
      }, 1500);
    }
  });
</script>
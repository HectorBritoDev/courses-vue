<div class="col-12 pt-0 mt-0">
    <h2 class="text-muted">{{ __('Requisitos para tomar el curso') }}</h2>
    <hr />

    @forelse ($requirements as $requirement)
    <div class="col-6">
        <div class="card bg-light p-3">
            <p class="mb-0">
                {{ $requirement->requirement }}
            </p>
        </div>
    </div>
    @empty
    <div class="alert alert-dark">
        <i class="fa fa-info-circle"></i>
        {{ __('Hay ningún requisito para este curso') }}
    </div>
    @endforelse
</div>

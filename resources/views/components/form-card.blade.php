@props(['action', 'method' => 'POST', 'cancel' => null, 'submit' => 'Simpan', 'files' => false])
<div {{ $attributes->class('row') }}>
    <div class="col-lg-8">
        <div class="card">
            <form action="{{ $action }}" method="POST" @if($files) enctype="multipart/form-data" @endif data-loading>
                @csrf
                @if(strtoupper($method) !== 'POST')
                    @method($method)
                @endif
                <div class="card-body">
                    {{ $slot }}
                </div>
                <div class="card-footer bg-white d-flex gap-2 justify-content-end">
                    @if($cancel)
                        <a href="{{ $cancel }}" class="btn btn-outline-secondary">Batal</a>
                    @endif
                    <button type="submit" class="btn btn-primary"><x-icon name="save" /> {{ $submit }}</button>
                </div>
            </form>
        </div>
    </div>
    @isset($aside)
        <div class="col-lg-4">{{ $aside }}</div>
    @endisset
</div>

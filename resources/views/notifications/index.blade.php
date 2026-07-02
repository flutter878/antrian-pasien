@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="mb-0 fw-bold">Notifikasi</h4>
        <small class="text-muted">Daftar notifikasi Anda</small>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($notifications->count())
        <div class="list-group">
            @foreach($notifications as $note)
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-semibold">{{ $note->data['title'] ?? 'Notifikasi' }}</div>
                    <div class="small text-muted">{{ $note->data['message'] ?? '' }}</div>
                    <div class="small text-muted">{{ $note->created_at->translatedFormat('d M Y H:i') }}</div>
                </div>
                <div class="ms-3 text-end">
                    @if(is_null($note->read_at))
                    <form method="POST" action="{{ route('notifications.read', $note->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-primary">Tandai dibaca</button>
                    </form>
                    @else
                    <span class="badge bg-light text-dark">Dibaca</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-3">
            {{ $notifications->links() }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            Tidak ada notifikasi.
        </div>
        @endif
    </div>
</div>
@endsection

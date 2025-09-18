@extends('layouts.app')

@section('title', 'Berita Sekolah')

@section('content')
    <section class="news py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-success">Berita & Informasi</h2>
                <p class="text-muted">Update berita terbaru dan pengumuman sekolah</p>
            </div>

            <div class="row">
                {{--  @forelse($beritas as $berita)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            @if ($berita->gambar)
                                <img src="{{ asset('storage/' . $berita->gambar) }}" class="card-img-top"
                                    alt="{{ $berita->judul }}" style="height:200px; object-fit:cover;">
                            @else
                                <img src="https://source.unsplash.com/600x400/?school,students" class="card-img-top"
                                    alt="{{ $berita->judul }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold">{{ $berita->judul }}</h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit(strip_tags($berita->isi), 100, '...') }}
                                </p>
                                <a href="{{ route('berita.show', $berita->slug) }}"
                                    class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada berita terbaru.</p>
                    </div>
                @endforelse  --}}
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{--  {{ $beritas->links('pagination::bootstrap-4') }}  --}}
            </div>
        </div>
    </section>
@endsection

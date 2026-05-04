@extends($layout)

@section('title', 'Papan Informasi Madrasah')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-inbox mr-2 animate__animated animate__pulse animate__infinite"></i> 
                            Papan Siaran & Informasi
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pusat informasi resmi. Temukan pembaruan, jadwal, dan pengumuman penting terbaru dari pihak Madrasah di sini.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-envelope-open-text fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <div class="col-lg-8 mx-auto">
        <!-- ANNOUNCEMENT FEED -->
        <div class="announcement-feed mb-5">
            @forelse($announcements as $item)
                @php $isRead = $item->isReadBy(auth()->id()); @endphp
                
                <div class="card premium-card border-0 mb-4 announcement-card cursor-pointer {{ !$isRead ? 'unread-card' : '' }}" onclick="showAnnouncement({{ $item->id }})">
                    <div class="card-body p-4 p-md-5 position-relative">
                        
                        @if(!$isRead)
                        <!-- New Badge Ribbon -->
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-danger text-lg text-white font-weight-bold shadow-sm">
                                BARU
                            </div>
                        </div>
                        @endif

                        <div class="d-flex align-items-start">
                            <div class="avatar-lg {{ $item->type == 'Guru' ? 'bg-soft-indigo text-indigo' : 'bg-soft-success text-success' }} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mr-4 shadow-sm" style="width: 60px; height: 60px; font-size: 24px;">
                                <i class="fas {{ $item->type == 'Guru' ? 'fa-user-tie' : 'fa-globe' }}"></i>
                            </div>
                            <div class="flex-grow-1 pr-md-5">
                                <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                                    <span class="badge {{ $item->type == 'Guru' ? 'bg-indigo' : 'bg-success' }} text-white px-3 py-1 shadow-sm uppercase font-weight-bold" style="border-radius: 8px;">
                                        {{ $item->type }}
                                    </span>
                                    <span class="text-muted text-sm font-weight-bold">
                                        <i class="far fa-clock mr-1"></i> {{ $item->created_at->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                                <h4 class="font-weight-bold text-dark mb-2 announcement-title">{{ $item->title }}</h4>
                                <p class="text-muted mb-0 font-weight-normal text-md" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit(strip_tags($item->content), 120) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0 pb-4 px-4 px-md-5">
                        <span class="text-indigo font-weight-bold text-sm read-more-text"><i class="fas fa-book-reader mr-1"></i> BACA SELENGKAPNYA <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white border-dashed rounded-20 shadow-sm">
                    <div class="avatar-xl bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 text-muted" style="width: 100px; height: 100px; font-size: 40px;">
                        <i class="fas fa-comment-slash"></i>
                    </div>
                    <h4 class="font-weight-bold text-muted mb-2">Belum Ada Siaran</h4>
                    <p class="text-muted mb-0">Tidak ada pengumuman untuk Anda saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- READING MODAL -->
<div class="modal fade animate__animated animate__zoomIn" id="announcementModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg-premium rounded-20 overflow-hidden">
            <div class="modal-header bg-gradient-indigo text-white border-0 py-4 px-4 px-md-5 d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2" style="gap:10px;">
                        <span id="modal-badge" class="badge bg-white text-indigo px-3 py-1 shadow-sm uppercase font-weight-bold" style="border-radius: 8px;"></span>
                        <span id="modal-date" class="text-white-50 text-sm font-weight-bold"><i class="far fa-clock mr-1"></i></span>
                    </div>
                    <h3 id="modal-title" class="font-weight-bold mb-0 text-white"></h3>
                </div>
                <button type="button" class="close text-white ml-3" data-dismiss="modal" aria-label="Close" style="opacity: 1;">
                    <i class="fas fa-times-circle fa-lg"></i>
                </button>
            </div>
            
            <div class="modal-body p-4 p-md-5 bg-white" style="min-height: 200px;">
                <div id="modal-content" class="text-dark font-weight-normal text-md" style="line-height: 1.8; font-size: 1.05rem;">
                    <!-- Loaded via JS -->
                </div>
            </div>
            
            <div class="modal-footer border-0 p-4 bg-light-soft justify-content-center">
                <button type="button" class="btn btn-indigo rounded-pill px-5 py-2 font-weight-bold shadow-indigo-light text-white" data-dismiss="modal">
                    <i class="fas fa-check-circle mr-2"></i> SAYA SUDAH MEMBACA INI
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Indigo Design System */
    .bg-gradient-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important; }
    .bg-indigo { background: #4f46e5 !important; }
    .text-indigo { color: #4f46e5 !important; }
    .bg-soft-indigo { background: #e0e7ff !important; }
    .bg-soft-success { background: #d1fae5 !important; }
    .btn-indigo { background: #4f46e5; color: #fff; border: none; }
    .btn-indigo:hover { background: #4338ca; color: #fff; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 20px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05) !important; transition: all 0.3s ease; }
    .rounded-20 { border-radius: 20px; }
    .bg-light-soft { background: #f8fafc; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    .border-dashed { border: 2px dashed #cbd5e1; }
    .shadow-lg-premium { box-shadow: 0 20px 40px rgba(0,0,0,0.2); }

    /* Announcement Card Interactions */
    .announcement-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(79, 70, 229, 0.15) !important; border-color: #4f46e5; }
    .announcement-card .read-more-text { opacity: 0; transition: all 0.3s ease; transform: translateX(-10px); }
    .announcement-card:hover .read-more-text { opacity: 1; transform: translateX(0); }
    .announcement-card:hover .announcement-title { color: #4f46e5 !important; }
    
    /* Unread Card Styling */
    .unread-card { border-left: 6px solid #ef4444; }

    /* Ribbon */
    .ribbon-wrapper { height: 70px; overflow: hidden; position: absolute; right: -2px; top: -2px; width: 70px; z-index: 10; }
    .ribbon-wrapper.ribbon-lg { height: 100px; width: 100px; }
    .ribbon-wrapper .ribbon {
        box-shadow: 0 0 3px rgba(0,0,0,.3); font-size: .8rem; line-height: 100%; padding: .375rem 0;
        position: relative; right: -2px; text-align: center; text-shadow: 0 -1px 0 rgba(0,0,0,.4);
        text-transform: uppercase; top: 10px; -webkit-transform: rotate(45deg); transform: rotate(45deg); width: 90px;
    }
    .ribbon-wrapper.ribbon-lg .ribbon { font-size: 1rem; padding: .5rem 0; right: 0; top: 22px; width: 130px; }
    
    /* Modal Content HTML formatting */
    #modal-content p { margin-bottom: 1rem; }
    #modal-content b, #modal-content strong { color: #1e293b; font-weight: 800; }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    function showAnnouncement(id) {
        Swal.fire({
            title: 'Membuka Siaran...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        $.get('{{ url("admin/announcements") }}/' + id)
            .done(response => {
                const data = response.data;
                Swal.close();
                
                $('#modal-title').text(data.title);
                $('#modal-content').html(data.content);
                $('#modal-date').html('<i class="far fa-clock mr-1"></i> ' + new Date(data.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }));
                
                const badge = $('#modal-badge');
                if (data.type === 'Guru') {
                    badge.html('<i class="fas fa-user-tie mr-1"></i> GURU');
                } else {
                    badge.html('<i class="fas fa-globe mr-1"></i> UMUM');
                }

                $('#announcementModal').modal('show');
                
                $('#announcementModal').on('hidden.bs.modal', function () {
                    window.location.reload();
                });
            })
            .fail(() => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat isi pengumuman' });
            });
    }
</script>
@endpush

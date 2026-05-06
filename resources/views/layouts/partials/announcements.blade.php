@php
    $breakingNews = App\Models\Post::where('post_type', 'announcement')->orderBy('created_at', 'desc')->limit(5)->get();
@endphp

 @forelse ($breakingNews as $b)
     <div class="media mb-3 pb-3 border-bottom align-items-center">
         <div class="announcement-date-box mr-3 bg-light text-success d-flex flex-column align-items-center justify-content-center shadow-sm" style="width: 55px; height: 55px; border-radius: 12px; border: 1px solid #eee;">
             <span class="font-weight-bold" style="font-size: 1.1rem; line-height: 1;">{{ $b->created_at->format('d') }}</span>
             <small class="text-uppercase" style="font-size: 0.65rem; font-weight: 700;">{{ $b->created_at->format('M') }}</small>
         </div>
         <div class="media-body">
             <h6 class="mt-0 mb-1" style="font-size: 0.9rem; line-height: 1.4;">
                 <a href="{{ route('front.post_show', $b->post_slug) }}" class="text-dark post-title-hover font-weight-bold"
                     style="text-decoration: none;">
                     {{ Str::limit(strip_tags($b->post_title), 60, '...') }}
                 </a>
             </h6>
         </div>
     </div>
 @empty
    <div class="text-center py-4 text-muted">
        <i class="fa fa-info-circle mb-2" style="font-size: 20px;"></i>
        <p class="mb-0">Belum ada data pengumuman.</p>
    </div>
@endforelse

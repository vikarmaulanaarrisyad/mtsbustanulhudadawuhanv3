 @php
     $breakingNews = App\Models\Post::where('post_type', 'post')->orderBy('created_at', 'desc')->limit(5)->get();
 @endphp

 @forelse ($breakingNews as $b)
     <div class="media mb-3 pb-3 border-bottom align-items-center">
         @php
             $thumb = $b->post_image ? Storage::url($b->post_image) : asset('images/no-image.png');
         @endphp
         <img src="{{ $thumb }}" class="mr-3 rounded shadow-sm" style="width: 65px; height: 65px; object-fit: cover;">
         <div class="media-body">
             <h6 class="mt-0 mb-1" style="font-size: 0.9rem; line-height: 1.4;">
                 <a href="{{ route('front.post_show', $b->post_slug) }}" class="text-dark post-title-hover font-weight-bold"
                     style="text-decoration: none;">
                     {{ Str::limit(strip_tags($b->post_title), 60, '...') }}
                 </a>
             </h6>
             <small class="text-muted"><i class="fa fa-calendar-alt mr-1"></i> {{ $b->created_at->format('d M Y') }}</small>
         </div>
     </div>
 @empty
     <div class="text-center py-4 text-muted bg-light rounded">
         <i class="fa fa-newspaper mb-2 opacity-50" style="font-size: 30px;"></i>
         <p class="mb-0 small">Belum ada berita terbaru.</p>
     </div>
 @endforelse

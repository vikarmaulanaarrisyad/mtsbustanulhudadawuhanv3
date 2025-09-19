 @php
     $breakingNews = App\Models\Post::orderBy('created_at', 'desc')->limit(5)->get();
 @endphp

 @foreach ($breakingNews as $b)
     <div class="media mb-3 pb-3 border-bottom">
         {{--  <img src="{{ Storage::url($b->post_image) }}" class="mr-3 img-circle" alt="..."
                                        width="80" height="40">  --}}
         <div class="media-body">
             <h6 class="mt-0 font-weight-bold post-title-hover">
                 <a href="{{ route('front.post_show', $b->post_slug) }}" class="text-dark post-title-hover"
                     style="text-decoration: none;">
                     <p class="post-title-hover text-justify">
                         {{ Str::limit(strip_tags($b->post_title), 200, '...') }}</p>
                 </a>
             </h6>
             <small class="text-muted text-justify">
                 {{ $b->created_at->format('d M Y') }} &bull;
                 {!! Str::limit(strip_tags($b->post_content), 250, '...') !!}
             </small>
         </div>
     </div>
 @endforeach

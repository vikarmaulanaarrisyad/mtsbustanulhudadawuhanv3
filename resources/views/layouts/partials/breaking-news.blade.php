 @php
     $breakingNews = App\Models\Post::where('post_type', 'post')->orderBy('created_at', 'desc')->limit(5)->get();
 @endphp

 @foreach ($breakingNews as $b)
     <div class="media border-bottom">
         <div class="media-body">
             <h6 class="mt-0 font-weight-bold post-title-hover">
                 <a href="{{ route('front.post_show', $b->post_slug) }}" class="text-dark post-title-hover"
                     style="text-decoration: none;">
                     <p class="post-title-hover text-justify">
                         {{ Str::limit(strip_tags($b->post_title), 200, '...') }}</p>
                 </a>
             </h6>
         </div>
     </div>
 @endforeach

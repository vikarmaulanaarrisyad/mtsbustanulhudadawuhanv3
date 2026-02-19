@php
    $breakingNews = App\Models\Post::where('post_type', 'prestasi')->orderBy('created_at', 'desc')->limit(5)->get();
@endphp

@forelse ($breakingNews as $b)
    <div class="media border-bottom py-2">
        <div class="media-body">
            <h6 class="mt-0 font-weight-bold">
                <a href="{{ route('front.post_show', $b->post_slug) }}" class="text-dark post-title-hover"
                    style="text-decoration: none;">
                    <p class="mb-0 text-justify">
                        {{ Str::limit(strip_tags($b->post_title), 200, '...') }}
                    </p>
                </a>
            </h6>
        </div>
    </div>
@empty
    <div class="text-center py-4 text-muted">
        <i class="fa fa-info-circle mb-2" style="font-size: 20px;"></i>
        <p class="mb-0">Belum ada data prestasi.</p>
    </div>
@endforelse

 @php
     $sambutan = App\Models\WelcomeMessage::first();
 @endphp

 <div class="kepala-card">
     <div class="kepala-header">
         <span>Sambutan Kepala Madrasah</span>
     </div>

     <div class="kepala-body">

         <div class="kepala-profile">
             <img src="{{ Storage::url($sambutan->path_image ?? '') }}" alt="Kepala Madrasah">
             <div class="kepala-info">
                 <h6>{{ $sambutan->name ?? '-' }}</h6>
                 <small>Kepala Madrasah</small>
             </div>
         </div>

         <div class="sidebar-body">
             <div class="media border-bottom">
                 <div class="media-body">
                     @if ($sambutan)
                         <div class="kepala-sambutan">
                             {!! $sambutan->content !!}
                         </div>
                     @else
                         <p class="text-muted">Belum ada sambutan tersedia.</p>
                     @endif
                 </div>
             </div>
         </div>
     </div>
 </div>

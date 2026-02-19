 @php
     $sambutan = App\Models\WelcomeMessage::first();
 @endphp

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

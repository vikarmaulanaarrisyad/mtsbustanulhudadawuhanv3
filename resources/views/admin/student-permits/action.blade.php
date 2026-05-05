<div class="btn-group">
    @if($row->attachment)
        <a href="{{ Storage::url($row->attachment) }}" target="_blank" class="btn btn-sm btn-info btn-icon" title="Lihat Lampiran">
            <i class="fas fa-paperclip"></i>
        </a>
    @endif
    @if($row->status == 'pending')
        <button class="btn btn-sm btn-success btn-icon" title="Setujui" onclick="approvePermit({{ $row->id }}, 'approved')">
            <i class="fas fa-check"></i>
        </button>
        <button class="btn btn-sm btn-danger btn-icon" title="Tolak" onclick="approvePermit({{ $row->id }}, 'rejected')">
            <i class="fas fa-times"></i>
        </button>
    @endif
</div>

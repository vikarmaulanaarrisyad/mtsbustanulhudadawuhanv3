@extends('layouts.app')
@section('title', 'Kelola Soal: ' . $bank->name)
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Informasi Bank Soal</h3>
            </div>
            <div class="card-body">
                <p><strong>Mata Pelajaran:</strong> {{ $bank->subject->name ?? '-' }}</p>
                <p><strong>Kelas:</strong> {{ $bank->class_level }}</p>
                <p><strong>Total Soal:</strong> <span class="badge badge-info">{{ $bank->questions->count() }}</span></p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">Tambah Soal Baru</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.cbt.bank.storeQuestion', $bank->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <textarea name="question_text" class="form-control" rows="3" required placeholder="Tuliskan soal di sini..."></textarea>
                    </div>
                    
                    <label>Pilihan Jawaban (Pilih salah satu sebagai jawaban benar)</label>
                    <div class="form-group">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend"><div class="input-group-text"><input type="radio" name="correct_option" value="0" required></div></div>
                            <input type="text" name="options[]" class="form-control" placeholder="Opsi A" required>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend"><div class="input-group-text"><input type="radio" name="correct_option" value="1"></div></div>
                            <input type="text" name="options[]" class="form-control" placeholder="Opsi B" required>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend"><div class="input-group-text"><input type="radio" name="correct_option" value="2"></div></div>
                            <input type="text" name="options[]" class="form-control" placeholder="Opsi C" required>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend"><div class="input-group-text"><input type="radio" name="correct_option" value="3"></div></div>
                            <input type="text" name="options[]" class="form-control" placeholder="Opsi D" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Simpan Soal</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Soal</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($bank->questions as $index => $q)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="w-100">
                                    <strong>{{ $index + 1 }}. </strong> {!! nl2br(e($q->question_text)) !!}
                                    <ul class="mt-2 pl-4" style="list-style-type: lower-alpha;">
                                        @foreach($q->options as $opt)
                                            <li class="{{ $opt->is_correct ? 'text-success font-weight-bold' : '' }}">
                                                {!! $opt->option_text !!} {!! $opt->is_correct ? '<i class="fas fa-check-circle"></i>' : '' !!}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <form action="{{ route('admin.cbt.bank.destroyQuestion', $q->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Belum ada soal di bank ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Kelola Soal: ' . $bank->name)
@section('subtitle', 'Bank Soal CBT')

@section('content')
{{-- PREMIUM HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 overflow-hidden position-relative" style="border-radius:20px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-md-7 text-white">
                        <a href="{{ route('admin.cbt.bank.index') }}" class="btn btn-sm btn-glass mb-3 rounded-pill px-3">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                        <h1 class="display-5 font-weight-bold mb-1">{{ $bank->name }}</h1>
                        <p class="mb-0 opacity-80 lead">
                            <span class="mr-3"><i class="fas fa-book-open mr-1"></i> {{ $bank->subject->name ?? '-' }}</span>
                            <span class="mr-3"><i class="fas fa-layer-group mr-1"></i> Kelas {{ $bank->class_level }}</span>
                            <span><i class="fas fa-question-circle mr-1"></i> {{ $bank->questions->count() }} Butir Soal</span>
                        </p>
                    </div>
                    <div class="col-md-5 text-right d-none d-md-block">
                        <div class="d-flex justify-content-end" style="gap: 12px;">
                            <a href="{{ route('admin.cbt.bank.downloadTemplate', $bank->id) }}" class="btn btn-light rounded-xl font-weight-bold shadow-lg hover-scale">
                                <i class="fas fa-file-excel mr-2 text-success"></i> Download Template
                            </a>
                            <button class="btn btn-warning rounded-xl font-weight-bold shadow-lg hover-scale" data-toggle="modal" data-target="#importModal">
                                <i class="fas fa-cloud-upload-alt mr-2"></i> Import Soal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Abstract background shapes --}}
            <div class="header-shape-1"></div>
            <div class="header-shape-2"></div>
        </div>
    </div>
</div>

{{-- Mobile buttons --}}
<div class="row d-md-none mb-3 px-3">
    <div class="col-6 pr-1"><a href="{{ route('admin.cbt.bank.downloadTemplate', $bank->id) }}" class="btn btn-light btn-block rounded-lg shadow-sm"><i class="fas fa-download mr-1"></i> Template</a></div>
    <div class="col-6 pl-1"><button class="btn btn-warning btn-block rounded-lg shadow-sm" data-toggle="modal" data-target="#importModal"><i class="fas fa-upload mr-1"></i> Import</button></div>
</div>

{{-- STATS SECTION --}}
@php
    $typeCounts = $bank->questions->groupBy('question_type')->map->count();
    $typeLabels = \App\Models\CbtQuestion::typeLabels();
    $typeColors = ['pilihan_ganda'=>'#3b82f6','ganda_komplek'=>'#8b5cf6','penjodohan'=>'#f59e0b','essay'=>'#10b981','uraian'=>'#06b6d4'];
    $typeIcons = ['pilihan_ganda'=>'dot-circle','ganda_komplek'=>'check-double','penjodohan'=>'random','essay'=>'pen-fancy','uraian'=>'file-alt'];
@endphp

<div class="row mb-4">
    @foreach($typeLabels as $key => $label)
    <div class="col-lg-2-5 col-md-4 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius:15px; overflow:hidden;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="icon-shape shadow-sm mr-2" style="background: {{ $typeColors[$key] }}20; color: {{ $typeColors[$key] }};">
                        <i class="fas fa-{{ $typeIcons[$key] }}"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase">{{ $label }}</span>
                </div>
                <h3 class="font-weight-bold mb-0 ml-1">{{ $typeCounts[$key] ?? 0 }} <small class="text-muted text-xs">butir</small></h3>
            </div>
            <div class="stat-progress" style="background: {{ $typeColors[$key] }}; width: {{ $bank->questions->count() > 0 ? (($typeCounts[$key] ?? 0) / $bank->questions->count() * 100) : 0 }}%;"></div>
        </div>
    </div>
    @endforeach
</div>

{{-- ALERTS --}}
@if(session('success'))
<div class="alert alert-custom alert-success shadow-lg border-0 mb-4 animate__animated animate__fadeInDown">
    <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
    <div class="alert-content">
        <h6 class="font-weight-bold mb-0">Berhasil!</h6>
        <span>{!! session('success') !!}</span>
    </div>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

@if(session('warning'))
<div class="alert alert-custom alert-warning shadow-lg border-0 mb-4 animate__animated animate__fadeInDown">
    <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="alert-content">
        <h6 class="font-weight-bold mb-0">Perhatian!</h6>
        <span>{!! session('warning') !!}</span>
        @if(session('import_errors'))
        <div class="mt-2 small bg-white-20 p-2 rounded">
            <ul class="mb-0 pl-3">
                @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

{{-- VALIDATION ERRORS --}}
@if($errors->any())
<div class="alert alert-custom alert-danger shadow-lg border-0 mb-4 animate__animated animate__shakeX">
    <div class="alert-icon"><i class="fas fa-times-circle"></i></div>
    <div class="alert-content">
        <h6 class="font-weight-bold mb-0">Terjadi Kesalahan Input:</h6>
        <ul class="mb-0 pl-3 small">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">
    {{-- LEFT: ADD QUESTION FORM --}}
    <div class="col-lg-5 col-xl-4">
        <div class="card shadow-lg border-0 mb-4 sticky-top-custom" style="border-radius:20px;">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-plus-circle mr-2 text-primary"></i>Tambah Soal Manual</h5>
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('admin.cbt.bank.storeQuestion', $bank->id) }}" method="POST" enctype="multipart/form-data" id="questionForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="form-group mb-3">
                        <label class="form-label-premium">TIPE SOAL</label>
                        <select name="question_type" id="questionType" class="form-control premium-select" required>
                            @foreach($typeLabels as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label-premium">PERTANYAAN</label>
                        <textarea name="question_text" class="form-control premium-input" rows="4" required placeholder="Tuliskan teks pertanyaan di sini..."></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label-premium">GAMBAR SOAL (OPSIONAL)</label>
                        <div class="custom-file-premium">
                            <input type="file" name="question_image" id="qImage" class="d-none" accept="image/*">
                            <label for="qImage" class="file-label-box" id="qImageLabel">
                                <i class="fas fa-image mr-2 text-muted"></i> <span>Pilih Gambar...</span>
                            </label>
                        </div>
                    </div>

                    {{-- PG/PGK Options --}}
                    <div id="optionsSection" class="animate__animated animate__fadeIn">
                        <label class="form-label-premium d-flex justify-content-between">
                            <span>PILIHAN JAWABAN</span>
                            <span class="badge badge-soft-primary" id="correctHint">Pilih 1 jawaban</span>
                        </label>
                        @foreach(['A','B','C','D','E'] as $i => $letter)
                        <div class="option-row mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text option-check-bg">
                                        <input type="radio" name="correct_option" value="{{ $i }}" class="correct-input custom-control-input-premium">
                                        <label class="mb-0 font-weight-bold ml-1">{{ $letter }}</label>
                                    </div>
                                </div>
                                <input type="text" name="options[]" class="form-control premium-input-sm" placeholder="Isi Opsi {{ $letter }}" {{ $i < 2 ? 'required' : '' }}>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Matching Pairs --}}
                    <div id="matchingSection" style="display:none;" class="animate__animated animate__fadeIn">
                        <label class="form-label-premium">PASANGAN PENJODOHAN</label>
                        <div id="matchingPairs" class="mb-3">
                            @for($i=0;$i<3;$i++)
                            <div class="matching-pair-row d-flex mb-2 align-items-center">
                                <input type="text" name="matching_premises[]" class="form-control premium-input-sm" placeholder="Premis {{ $i+1 }}">
                                <div class="px-2"><i class="fas fa-link text-muted"></i></div>
                                <input type="text" name="matching_responses[]" class="form-control premium-input-sm" placeholder="Respon {{ $i+1 }}">
                            </div>
                            @endfor
                        </div>
                        <input type="hidden" name="matching_pairs" value="1">
                        <button type="button" class="btn btn-sm btn-soft-primary mb-3 w-100" onclick="addMatchingPair()">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Baris Pasangan
                        </button>
                    </div>

                    {{-- Essay Answer Key --}}
                    <div id="essaySection" style="display:none;" class="animate__animated animate__fadeIn">
                        <div class="form-group">
                            <label class="form-label-premium">KUNCI JAWABAN (REFERENSI)</label>
                            <textarea name="answer_key" class="form-control premium-input" rows="3" placeholder="Tuliskan kata kunci atau jawaban referensi..."></textarea>
                        </div>
                    </div>

                    <div class="form-group mb-4 mt-3">
                        <label class="form-label-premium">BOBOT NILAI</label>
                        <input type="number" name="score_weight" class="form-control premium-input w-50" value="1" min="1" max="100">
                    </div>

                    <div class="d-flex" style="gap:10px;">
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg font-weight-bold rounded-xl py-3 mt-4 hover-lift" id="btnSubmitQuestion">
                            <i class="fas fa-save mr-2"></i> SIMPAN SOAL
                        </button>
                        <button type="button" class="btn btn-light btn-lg shadow-sm rounded-xl py-3 mt-4 d-none" id="btnCancelEdit" onclick="cancelEdit()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: QUESTION LIST --}}
    <div class="col-lg-7 col-xl-8">
        <div class="card shadow-lg border-0" style="border-radius:20px; min-height: 600px;">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">Daftar Soal CBT</h4>
                    <p class="text-muted text-sm mb-0 mt-1"><i class="fas fa-info-circle mr-1"></i> Menampilkan total {{ $bank->questions->count() }} butir soal</p>
                </div>
                <div class="d-flex" style="gap:8px;">
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="toggleExpandAll()">
                        <i class="fas fa-expand-alt mr-1"></i> <span id="expandText">Expand All</span>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-soft-primary rounded-pill px-3 dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="filterQuestions('all')">Semua Tipe</a>
                            @foreach($typeLabels as $k => $l)
                            <a class="dropdown-item" href="#" onclick="filterQuestions('{{ $k }}')">{{ $l }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0" id="questionListContainer">
                @forelse($bank->questions as $idx => $q)
                @php
                    $badgeColor = $typeColors[$q->question_type] ?? '#6b7280';
                    $icon = $typeIcons[$q->question_type] ?? 'question';
                @endphp
                <div class="question-wrapper q-type-{{ $q->question_type }} border-bottom px-4 py-4" style="transition: all 0.3s;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1 pr-3">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge-premium mr-2" style="background: {{ $badgeColor }}15; color: {{ $badgeColor }}; border: 1px solid {{ $badgeColor }}30;">
                                    <i class="fas fa-{{ $icon }} mr-1"></i> {{ $typeLabels[$q->question_type] ?? $q->question_type }}
                                </span>
                                <span class="text-muted text-xs font-weight-bold"><i class="fas fa-star text-warning mr-1"></i> Bobot: {{ $q->score_weight }}</span>
                            </div>
                            
                            <div class="q-content-box">
                                <div class="d-flex">
                                    <span class="font-weight-bold text-primary mr-2" style="font-size: 1.1rem;">#{{ $idx+1 }}</span>
                                    <div class="q-text" style="font-size: 1.05rem; line-height: 1.6; color: #1e293b;">
                                        {!! nl2br(e($q->question_text)) !!}
                                    </div>
                                </div>

                                @if($q->question_image)
                                <div class="mt-3 mb-3">
                                    <div class="image-preview-container shadow-sm rounded-lg overflow-hidden border" style="max-width: 300px;">
                                        <img src="{{ asset('storage/'.$q->question_image) }}" alt="Gambar Soal" class="img-fluid q-image-zoom" onclick="previewImage(this.src)">
                                        <div class="img-overlay"><i class="fas fa-search-plus"></i></div>
                                    </div>
                                </div>
                                @endif

                                {{-- PG / PGK Options --}}
                                @if($q->hasOptions())
                                <div class="options-container mt-3 pl-4">
                                    @foreach($q->options as $oi => $opt)
                                    <div class="option-item d-flex align-items-center py-2 px-3 mb-2 rounded-lg {{ $opt->is_correct ? 'option-correct' : 'option-normal' }}">
                                        <div class="option-letter mr-3">{{ chr(65+$oi) }}</div>
                                        <div class="option-text flex-grow-1">
                                            {!! e($opt->option_text) !!}
                                            @if($opt->option_image)
                                            <div class="mt-1">
                                                <img src="{{ asset('storage/'.$opt->option_image) }}" class="rounded shadow-xs border" style="max-height: 60px; cursor: pointer;" onclick="previewImage(this.src)">
                                            </div>
                                            @endif
                                        </div>
                                        @if($opt->is_correct)
                                        <div class="ml-2 text-success"><i class="fas fa-check-circle"></i></div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                {{-- Matching Pairs --}}
                                @if($q->isMatching() && $q->matching_pairs)
                                <div class="mt-4 pl-4">
                                    <div class="matching-visual shadow-sm rounded-lg border bg-light p-3">
                                        <h6 class="text-xs font-weight-bold text-muted text-uppercase mb-3"><i class="fas fa-link mr-1"></i> Pasangan Benar:</h6>
                                        <div class="row no-gutters">
                                            @foreach($q->matching_pairs as $premise => $response)
                                            <div class="col-md-6 mb-2">
                                                <div class="d-flex align-items-center bg-white p-2 rounded shadow-xs border-left-success">
                                                    <div class="text-dark small flex-fill">{{ $premise }}</div>
                                                    <div class="mx-2"><i class="fas fa-arrow-right text-muted text-xs"></i></div>
                                                    <div class="text-success font-weight-bold small flex-fill">{{ $response }}</div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Essay/Uraian Answer Key --}}
                                @if(($q->isEssay() || $q->isUraian()) && $q->answer_key)
                                <div class="mt-3 pl-4">
                                    <div class="bg-soft-success p-3 rounded-lg border-left-success-4">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-key text-success mr-2"></i>
                                            <small class="text-success font-weight-bold text-uppercase">Kunci Jawaban Referensi</small>
                                        </div>
                                        <div class="text-dark-blue small">{!! nl2br(e($q->answer_key)) !!}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="q-actions d-flex flex-column" style="gap: 8px;">
                            <button class="btn btn-action btn-outline-danger" title="Hapus Soal" onclick="deleteQuestion({{ $q->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-action btn-outline-warning" title="Edit Soal" onclick="editQuestion({{ $q->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 empty-state">
                    <div class="mb-4">
                        <div class="empty-icon-box mx-auto">
                            <i class="fas fa-folder-open text-muted fa-3x"></i>
                        </div>
                    </div>
                    <h5 class="font-weight-bold text-dark">Bank Soal Masih Kosong</h5>
                    <p class="text-muted mx-auto" style="max-width: 300px;">Mulai dengan menambahkan soal secara manual atau import massal menggunakan file Excel.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- IMPORT MODAL --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content overflow-hidden border-0 shadow-2xl" style="border-radius:25px;">
            <div class="modal-header text-white border-0 py-4 px-4 d-flex align-items-center" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                <div class="modal-title-box">
                    <h4 class="modal-title font-weight-bold mb-0 text-white"><i class="fas fa-file-import mr-2"></i>Import Soal CBT</h4>
                    <p class="mb-0 opacity-80 small">Pastikan format Excel sesuai dengan template yang disediakan.</p>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal">
                    <span style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.cbt.bank.importQuestions', $bank->id) }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body p-4">
                    {{-- Step Grid --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="import-step-card p-3 rounded-xl border h-100 bg-light-gradient">
                                <div class="step-num">1</div>
                                <h6 class="font-weight-bold mb-1">Unduh Template</h6>
                                <p class="text-xs text-muted mb-3">Siapkan data soal Anda menggunakan template resmi kami.</p>
                                <a href="{{ route('admin.cbt.bank.downloadTemplate', $bank->id) }}" class="btn btn-sm btn-white btn-block rounded-pill shadow-sm border">
                                    <i class="fas fa-download mr-1 text-primary"></i> Download .xlsx
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="import-step-card p-3 rounded-xl border h-100 bg-light-gradient">
                                <div class="step-num">2</div>
                                <h6 class="font-weight-bold mb-1">Isi Data Soal</h6>
                                <p class="text-xs text-muted mb-0">Dukung 5 tipe soal: PG, PGK, Penjodohan, Essay, & Uraian.</p>
                                <p class="text-xs text-muted mb-0 mt-1">Sertakan gambar langsung di dalam cell Excel.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Area --}}
                    <div class="upload-section mb-4">
                        <h6 class="form-label-premium mb-2">PILIH FILE EXCEL</h6>
                        <div class="drop-zone-premium" id="dropZone">
                            <input type="file" name="file" id="excelFile" accept=".xlsx,.xls" required class="d-none">
                            <div class="drop-content py-5 text-center">
                                <div class="upload-icon-pulse mb-3">
                                    <i class="fas fa-file-excel fa-3x text-success"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1">Tarik file Excel ke sini</h6>
                                <p class="text-muted small mb-0">atau <span class="text-primary font-weight-bold cursor-pointer">pilih dari komputer</span></p>
                                <p class="text-xs text-muted mt-2">Maksimum ukuran file: 10 MB</p>
                            </div>
                            <div class="file-preview-premium d-none" id="filePreview">
                                <div class="d-flex align-items-center p-3 bg-white rounded-lg border shadow-sm">
                                    <i class="fas fa-file-excel fa-2x text-success mr-3"></i>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="font-weight-bold text-dark text-truncate" id="fileName">Template.xlsx</div>
                                        <div class="text-muted text-xs" id="fileSize">0 KB</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-soft-danger rounded-circle p-2 ml-2" onclick="clearFile()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Bar (Hidden initially) --}}
                    <div id="importProgress" class="d-none mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-xs font-weight-bold text-primary">Sedang memproses...</span>
                            <span class="text-xs font-weight-bold text-primary" id="progressPercent">0%</span>
                        </div>
                        <div class="progress rounded-pill shadow-sm" style="height: 12px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%" id="progressBar"></div>
                        </div>
                        <p class="text-xs text-muted mt-2 text-center">Mohon tunggu, jangan tutup jendela ini sampai selesai.</p>
                    </div>

                    <div class="info-footer p-3 rounded-lg bg-soft-info border-0">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-lightbulb text-info mr-3 mt-1"></i>
                            <div class="small">
                                <strong class="text-info">Tips:</strong> Untuk gambar opsi, letakkan gambar di cell yang disediakan pada template. Gambar akan otomatis ter-extract dan terhubung dengan soal.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-xl px-4 py-2" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-xl px-5 py-2 font-weight-bold shadow-lg" id="btnImport">
                        <i class="fas fa-check-circle mr-2"></i> MULAI IMPORT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- IMAGE PREVIEW MODAL --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <img src="" id="previewImg" class="img-fluid rounded-lg shadow-2xl">
                <button type="button" class="btn btn-white rounded-circle position-absolute" style="top:-15px; right:-15px; width:40px; height:40px;" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* PREMIUM DESIGN TOKENS */
:root {
    --primary: #4f46e5;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark-blue: #1e293b;
}

.rounded-xl { border-radius: 12px !important; }
.rounded-2xl { border-radius: 20px !important; }
.bg-soft-primary { background: #eef2ff; color: #4f46e5; }
.bg-soft-success { background: #ecfdf5; color: #10b981; }
.bg-soft-info { background: #f0f9ff; color: #06b6d4; }
.bg-soft-danger { background: #fef2f2; color: #ef4444; }
.hover-scale:hover { transform: scale(1.03); transition: transform 0.2s; }
.hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; transition: all 0.2s; }

/* HEADER */
.btn-glass { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.2); }
.btn-glass:hover { background: rgba(255,255,255,0.25); color: white; }
.header-shape-1 { position: absolute; width: 250px; height: 250px; top: -100px; right: -50px; background: rgba(255,255,255,0.1); border-radius: 50%; }
.header-shape-2 { position: absolute; width: 150px; height: 150px; bottom: -60px; left: 5%; background: rgba(255,255,255,0.06); border-radius: 50%; }

/* STAT CARDS */
.stat-card { transition: all 0.3s; position: relative; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important; }
.icon-shape { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
.stat-progress { position: absolute; bottom: 0; left: 0; height: 3px; opacity: 0.6; }

/* FORM PREMIUM */
.form-label-premium { font-size: 0.75rem; font-weight: 800; color: #64748b; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem; }
.premium-input, .premium-select { border-radius: 10px; border: 2px solid #e2e8f0; padding: 0.6rem 0.8rem; height: auto; transition: all 0.2s; }
.premium-input:focus, .premium-select:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
.premium-input-sm { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.4rem 0.6rem; font-size: 0.9rem; }
.file-label-box { display: block; border: 2px dashed #cbd5e1; border-radius: 10px; padding: 1rem; text-align: center; cursor: pointer; transition: all 0.2s; }
.file-label-box:hover { border-color: var(--primary); background: #f8fafc; }
.option-check-bg { background: #f1f5f9; border: none; border-radius: 8px 0 0 8px; }
.btn-soft-primary { background: #eef2ff; color: #4f46e5; border: none; }
.btn-soft-primary:hover { background: #e0e7ff; }

/* QUESTION LIST */
.question-wrapper:hover { background: #f8fafc; }
.badge-premium { padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
.q-image-zoom { cursor: pointer; transition: transform 0.3s; }
.image-preview-container:hover .q-image-zoom { transform: scale(1.05); }
.image-preview-container { position: relative; }
.img-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; opacity: 0; transition: 0.3s; pointer-events: none; }
.image-preview-container:hover .img-overlay { opacity: 1; }
.option-item { border: 1px solid transparent; transition: all 0.2s; }
.option-normal { background: #f8fafc; border-color: #f1f5f9; color: #475569; }
.option-correct { background: #ecfdf5; border-color: #10b98140; color: #065f46; font-weight: 600; }
.option-letter { width: 28px; height: 28px; background: rgba(0,0,0,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; }
.option-correct .option-letter { background: var(--success); color: white; }
.btn-action { width: 34px; height: 34px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 8px; }

/* IMPORT MODAL */
.bg-light-gradient { background: linear-gradient(to bottom, #ffffff, #f8fafc); }
.step-num { width: 24px; height: 24px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.75rem; margin-bottom: 8px; }
.drop-zone-premium { border: 3px dashed #e2e8f0; border-radius: 20px; transition: all 0.3s; background: #fafbfc; cursor: pointer; }
.drop-zone-premium.dragover { border-color: var(--primary); background: #eef2ff; }
.upload-icon-pulse { width: 70px; height: 70px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
.drop-zone-premium:hover .upload-icon-pulse { transform: scale(1.1); transition: 0.2s; }

/* UTILS */
.border-left-success-4 { border-left: 4px solid var(--success); }
.cursor-pointer { cursor: pointer; }
.sticky-top-custom { position: sticky; top: 1rem; z-index: 100; }
@media (max-width: 991px) { .sticky-top-custom { position: static; } }
.col-lg-2-5 { flex: 0 0 20%; max-width: 20%; }
@media (max-width: 991px) { .col-lg-2-5 { flex: 0 0 33.33%; max-width: 33.33%; } }
@media (max-width: 575px) { .col-lg-2-5 { flex: 0 0 50%; max-width: 50%; } }

/* ANIMATIONS */
.animate__animated { animation-duration: 0.6s; }
</style>
@endsection

@push('scripts')
<script>
$(function(){
    // File input label update
    $('#qImage').on('change', function(){
        var fileName = $(this).val().split('\\').pop();
        if(fileName) $('#qImageLabel span').text(fileName).addClass('text-primary font-weight-bold');
    });

    // Question type toggle
    $('#questionType').on('change', function(e, isInit){
        var t = $(this).val();
        if (!t) return;

        var isPG = t === 'pilihan_ganda' || t === 'ganda_komplek';
        
        $('#optionsSection').toggle(isPG);
        $('#matchingSection').toggle(t === 'penjodohan');
        $('#essaySection').toggle(t === 'essay' || t === 'uraian');

        // Toggle required attribute safely
        if(isPG) {
            $('input[name="options[]"]').slice(0, 2).attr('required', true);
        } else {
            $('input[name="options[]"]').removeAttr('required');
        }
        
        // Toggle radio vs checkbox for PG vs PGK
        const correctInputs = $('.correct-input');
        if(t === 'ganda_komplek'){
            correctInputs.attr('type', 'checkbox').attr('name', 'correct_option[]');
            $('#correctHint').text('Pilih multiple jawaban').removeClass('badge-soft-primary').addClass('badge-soft-purple');
        } else {
            correctInputs.attr('type', 'radio').attr('name', 'correct_option');
            $('#correctHint').text('Pilih 1 jawaban').removeClass('badge-soft-purple').addClass('badge-soft-primary');
        }
    });

    // Trigger initial state without recursion risk
    $('#questionType').trigger('change', [true]);

    // Drop zone logic
    var dz=$('#dropZone'), fi=$('#excelFile'), dc=$('.drop-content'), fp=$('#filePreview');
    dz.on('click',function(){fi.click();});
    dz.on('dragover',function(e){e.preventDefault();$(this).addClass('dragover');});
    dz.on('dragleave drop',function(){$(this).removeClass('dragover');});
    dz.on('drop',function(e){
        e.preventDefault();
        fi[0].files=e.originalEvent.dataTransfer.files;
        fi.trigger('change');
    });
    fi.on('change',function(){
        if(this.files.length){
            var f=this.files[0];
            $('#fileName').text(f.name);
            $('#fileSize').text((f.size/1024).toFixed(1)+' KB');
            dc.addClass('d-none');
            fp.removeClass('d-none');
        }
    });

    // Import form submit simulation
    $('#importForm').on('submit',function(){
        $('#importProgress').removeClass('d-none');
        $('#btnImport').html('<i class="fas fa-spinner fa-spin mr-2"></i>MENGIMPORT...').prop('disabled',true);
        
        // Simulate progress for UI feel
        var progress = 0;
        var interval = setInterval(function() {
            progress += Math.random() * 15;
            if (progress > 90) {
                progress = 95;
                clearInterval(interval);
            }
            $('#progressBar').css('width', progress + '%');
            $('#progressPercent').text(Math.round(progress) + '%');
        }, 800);
    });
});

function clearFile(){
    $('#excelFile').val('');
    $('#filePreview').addClass('d-none');
    $('.drop-content').removeClass('d-none');
}

function addMatchingPair(){
    var n=$('#matchingPairs .matching-pair-row').length+1;
    $('#matchingPairs').append('<div class="matching-pair-row d-flex mb-2 align-items-center animate__animated animate__fadeInSmall"><input type="text" name="matching_premises[]" class="form-control premium-input-sm" placeholder="Premis '+n+'"><div class="px-2"><i class="fas fa-link text-muted"></i></div><input type="text" name="matching_responses[]" class="form-control premium-input-sm" placeholder="Respon '+n+'"></div>');
}

var expanded = false;
function toggleExpandAll(){
    expanded = !expanded;
    if(expanded){
        $('.options-container, .matching-visual, .bg-soft-success').hide();
        $('#expandText').text('Expand All');
    } else {
        $('.options-container, .matching-visual, .bg-soft-success').show();
        $('#expandText').text('Collapse All');
    }
}

function previewImage(src){
    $('#previewImg').attr('src', src);
    $('#imagePreviewModal').modal('show');
}

function filterQuestions(type){
    if(type === 'all'){
        $('.question-wrapper').show();
    } else {
        $('.question-wrapper').hide();
        $('.q-type-' + type).show();
    }
}
function deleteQuestion(id) {
    Swal.fire({
        title: 'Hapus Soal?',
        text: "Data soal akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.action = `/admin/cbt/bank/questions/${id}`;
            form.method = 'POST';
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function editQuestion(id) {
    Swal.fire({
        title: 'Memuat Data...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    $.get(`/admin/cbt/bank/questions/${id}/edit`, function(q) {
        Swal.close();
        
        // Populate form
        $('#formMethod').val('PUT');
        $('#questionForm').attr('action', `/admin/cbt/bank/questions/${id}`);
        $('#questionType').val(q.question_type).trigger('change');
        $('textarea[name="question_text"]').val(q.question_text);
        $('input[name="score_weight"]').val(q.score_weight);
        
        if (q.answer_key) $('textarea[name="answer_key"]').val(q.answer_key);

        // Populate options
        if (q.options && q.options.length > 0) {
            q.options.forEach((opt, index) => {
                $(`input[name="options[]"]`).eq(index).val(opt.option_text);
                if (opt.is_correct) {
                    if (q.question_type === 'ganda_komplek') {
                        $(`.correct-input[value="${index}"]`).prop('checked', true);
                    } else {
                        $(`.correct-input[value="${index}"]`).prop('checked', true);
                    }
                }
            });
        }

        // Populate matching pairs
        if (q.question_type === 'penjodohan' && q.matching_pairs) {
            $('#matchingPairs').empty();
            let pairs = q.matching_pairs;
            Object.keys(pairs).forEach((premise, index) => {
                $('#matchingPairs').append(`<div class="matching-pair-row d-flex mb-2 align-items-center animate__animated animate__fadeInSmall">
                    <input type="text" name="matching_premises[]" class="form-control premium-input-sm" value="${premise}">
                    <div class="px-2"><i class="fas fa-link text-muted"></i></div>
                    <input type="text" name="matching_responses[]" class="form-control premium-input-sm" value="${pairs[premise]}">
                </div>`);
            });
        }

        // UI Changes
        $('.card-header h5').html('<i class="fas fa-edit mr-2 text-warning"></i>Edit Soal');
        $('#btnSubmitQuestion').html('<i class="fas fa-save mr-2"></i> UPDATE SOAL').removeClass('btn-primary').addClass('btn-warning');
        $('#btnCancelEdit').removeClass('d-none');
        
        // Scroll to form
        $('html, body').animate({ scrollTop: $("#questionForm").offset().top - 100 }, 500);
    });
}

function cancelEdit() {
    $('#formMethod').val('POST');
    $('#questionForm').attr('action', `{{ route('admin.cbt.bank.storeQuestion', $bank->id) }}`);
    $('#questionForm')[0].reset();
    $('#questionType').trigger('change');
    
    $('.card-header h5').html('<i class="fas fa-plus-circle mr-2 text-primary"></i>Tambah Soal Manual');
    $('#btnSubmitQuestion').html('<i class="fas fa-save mr-2"></i> SIMPAN SOAL').removeClass('btn-warning').addClass('btn-primary');
    $('#btnCancelEdit').addClass('d-none');
}
</script>
@endpush


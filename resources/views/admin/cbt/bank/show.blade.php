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
                            <button class="btn btn-info rounded-xl font-weight-bold shadow-lg hover-scale" data-toggle="modal" data-target="#aiModal">
                                <i class="fas fa-robot mr-2"></i> AI Generator
                            </button>
                            <button class="btn btn-danger rounded-xl font-weight-bold shadow-lg hover-scale" onclick="truncateQuestions()">
                                <i class="fas fa-trash-alt mr-2"></i> Kosongkan
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
    <div class="col-4 pr-1"><a href="{{ route('admin.cbt.bank.downloadTemplate', $bank->id) }}" class="btn btn-light btn-block rounded-lg shadow-sm px-1"><i class="fas fa-download mr-1"></i> Template</a></div>
    <div class="col-4 px-1"><button class="btn btn-warning btn-block rounded-lg shadow-sm px-1" data-toggle="modal" data-target="#importModal"><i class="fas fa-upload mr-1"></i> Import</button></div>
    <div class="col-4 px-1"><button class="btn btn-info btn-block rounded-lg shadow-sm px-1" data-toggle="modal" data-target="#aiModal"><i class="fas fa-robot mr-1"></i> AI</button></div>
    <div class="col-4 pl-1"><button class="btn btn-danger btn-block rounded-lg shadow-sm px-1" onclick="truncateQuestions()"><i class="fas fa-trash mr-1"></i> Hapus</button></div>
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
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="w-100">
                    <h5 class="modal-title font-weight-bold text-dark d-flex align-items-center">
                        <i class="fas fa-cloud-upload-alt text-primary mr-3 fa-lg"></i> IMPORT SOAL & MEDIA
                    </h5>
                    <ul class="nav nav-tabs nav-tabs-premium border-0 mt-4" id="importTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="excel-tab" data-toggle="tab" href="#excel-panel" role="tab" style="border-radius: 12px 12px 0 0;"><i class="fas fa-file-excel mr-2 text-success"></i>Import Excel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="media-tab" data-toggle="tab" href="#media-panel" role="tab" style="border-radius: 12px 12px 0 0;"><i class="fas fa-images mr-2 text-primary"></i>Upload Gambar</a>
                        </li>
                    </ul>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>

            <div class="tab-content" id="importTabContent">
                {{-- TAB 1: EXCEL IMPORT --}}
                <div class="tab-pane fade show active" id="excel-panel" role="tabpanel">
                    <form action="{{ route('admin.cbt.bank.importQuestions', $bank->id) }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="alert alert-soft-info border-0 rounded-xl mb-4 p-3 d-flex align-items-center">
                                <i class="fas fa-info-circle mr-3 text-info fa-lg"></i>
                                <div class="text-xs">Gunakan tab ini untuk mengimport data teks soal. Jika file sangat besar, disarankan mengunggah gambar secara terpisah di tab <b>Upload Gambar</b>.</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="import-step-card p-3 rounded-xl border h-100 bg-light-gradient">
                                        <div class="step-num">1</div>
                                        <h6 class="font-weight-bold mb-1 text-sm">Unduh Template</h6>
                                        <p class="text-xs text-muted mb-3">Siapkan data soal Anda menggunakan template resmi.</p>
                                        <a href="{{ route('admin.cbt.bank.downloadTemplate', $bank->id) }}" class="btn btn-sm btn-white btn-block rounded-pill shadow-sm border text-xs font-weight-bold">
                                            <i class="fas fa-download mr-1 text-primary"></i> DOWNLOAD TEMPLATE
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="import-step-card p-3 rounded-xl border h-100 bg-light-gradient">
                                        <div class="step-num">2</div>
                                        <h6 class="font-weight-bold mb-1 text-sm">Upload File Excel</h6>
                                        <p class="text-xs text-muted mb-0">Pastikan format .xlsx dan tipe soal sudah benar.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="upload-section mb-4">
                                <h6 class="form-label-premium mb-2">FILE EXCEL <span class="text-danger">*</span></h6>
                                <div class="drop-zone-premium" id="dropZone">
                                    <input type="file" name="file" id="excelFile" accept=".xlsx,.xls" required class="d-none">
                                    <div class="drop-content py-4 text-center">
                                        <div class="upload-icon-pulse mb-2">
                                            <i class="fas fa-file-excel fa-2x text-success"></i>
                                        </div>
                                        <h6 class="font-weight-bold text-dark mb-1 text-sm">Tarik file Excel ke sini</h6>
                                        <p class="text-xs text-muted mb-0">atau <span class="text-primary font-weight-bold cursor-pointer">pilih file</span></p>
                                    </div>
                                    <div class="file-preview-premium d-none" id="filePreview">
                                        <div class="d-flex align-items-center p-2 bg-white rounded-lg border shadow-sm">
                                            <i class="fas fa-file-excel fa-xl text-success mr-2"></i>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="font-weight-bold text-dark text-truncate text-xs" id="fileName">Template.xlsx</div>
                                            </div>
                                            <button type="button" class="btn btn-xs btn-soft-danger rounded-circle p-1 ml-1" onclick="clearFile()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="importProgress" class="d-none mt-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-xs font-weight-bold text-primary" id="progressStatus">Mengimport data...</span>
                                    <span class="text-xs font-weight-bold text-primary" id="progressPercent">0%</span>
                                </div>
                                <div class="progress rounded-pill shadow-sm" style="height: 10px;">
                                    <div class="progress-bar progress-bar-animated bg-primary" id="progressBar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light rounded-xl px-4" data-dismiss="modal">BATAL</button>
                            <button type="submit" class="btn btn-primary rounded-xl px-5 font-weight-bold shadow-lg" id="btnImport">
                                <i class="fas fa-check-circle mr-2"></i> MULAI IMPORT
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TAB 2: IMAGE UPLOAD --}}
                <div class="tab-pane fade" id="media-panel" role="tabpanel">
                    <form action="{{ route('admin.cbt.bank.uploadImages', $bank->id) }}" method="POST" enctype="multipart/form-data" id="imageUploadForm">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="alert alert-soft-primary rounded-xl border-0 mb-4 p-3 d-flex align-items-start">
                                <i class="fas fa-lightbulb mr-3 mt-1 text-primary fa-lg"></i>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-sm">Sync Gambar (Dukung ZIP)</h6>
                                    <p class="text-xs mb-0">Unggah file <b>.zip</b> berisi semua gambar atau pilih banyak file sekaligus. Sistem akan otomatis menghubungkannya berdasarkan nama file:</p>
                                    <div class="row mt-2">
                                        <div class="col-6"><code class="text-xs">1.jpg</code> <span class="text-xs">-> Soal No 1</span></div>
                                        <div class="col-6"><code class="text-xs">1_A.jpg</code> <span class="text-xs">-> Opsi A No 1</span></div>
                                        <div class="col-6"><code class="text-xs">1_P1.jpg</code> <span class="text-xs">-> Kiri (Premis) 1</span></div>
                                        <div class="col-6"><code class="text-xs">1_R1.jpg</code> <span class="text-xs">-> Kanan (Respon) 1</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="upload-section mb-4">
                                <h6 class="form-label-premium mb-2">PILIH FILE GAMBAR ATAU ZIP <span class="text-danger">*</span></h6>
                                <div class="drop-zone-premium" id="dropZoneImages" style="min-height: 180px;">
                                    <input type="file" name="images[]" id="imageFiles" accept="image/*,.zip" multiple required class="d-none">
                                    <div class="drop-content py-5 text-center" id="imageDropContent">
                                        <div class="upload-icon-pulse mb-3">
                                            <i class="fas fa-file-archive fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="font-weight-bold text-dark mb-1">Tarik file ZIP atau Gambar ke sini</h6>
                                        <p class="text-xs text-muted mb-0">atau <span class="text-primary font-weight-bold cursor-pointer">pilih file</span></p>
                                    </div>
                                    <div class="file-preview-premium d-none" id="imagePreviewContainer">
                                        <div class="d-flex align-items-center p-3 bg-white rounded-lg border shadow-sm">
                                            <i class="fas fa-photo-video fa-2x text-primary mr-3"></i>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="font-weight-bold text-dark" id="imageCountText">0 Gambar terpilih</div>
                                                <div class="text-xs text-muted">Siap untuk dihubungkan ke bank soal</div>
                                            </div>
                                            <button type="button" class="btn btn-soft-danger rounded-circle p-2 ml-2" onclick="clearImages()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light rounded-xl px-4" data-dismiss="modal">BATAL</button>
                            <button type="submit" class="btn btn-primary rounded-xl px-5 font-weight-bold shadow-lg" id="btnUploadImages">
                                <i class="fas fa-sync-alt mr-2"></i> HUBUNGKAN GAMBAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

{{-- AI GENERATOR MODAL --}}
<div class="modal fade" id="aiModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl" style="border-radius:25px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold text-dark d-flex align-items-center">
                    <div class="icon-shape bg-soft-primary mr-3"><i class="fas fa-robot"></i></div>
                    AI QUESTION GENERATOR (GEMINI AI)
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div id="aiInputSection">
                    <div class="alert alert-soft-primary border-0 rounded-xl mb-4 p-3 d-flex align-items-center">
                        <i class="fas fa-magic mr-3 fa-lg"></i>
                        <div class="text-sm">Tempelkan materi/teks di bawah ini, dan AI akan secara otomatis merancang butir soal untuk Anda.</div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label class="form-label-premium">MATERI / SUMBER TEKS</label>
                        <textarea id="aiSourceText" class="form-control premium-input" rows="5" placeholder="Tempelkan materi teks di sini..."></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label-premium">ATAU UNGGAH GAMBAR (FOTO BUKU/SOAL)</label>
                        <div class="custom-file-premium">
                            <input type="file" id="aiSourceImage" class="d-none" accept="image/*">
                            <label for="aiSourceImage" class="file-label-box" id="aiSourceImageLabel">
                                <i class="fas fa-camera mr-2 text-muted"></i> <span>Ambil Foto atau Pilih Gambar...</span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label-premium">TIPE SOAL</label>
                                <select id="aiQuestionType" class="form-control premium-select">
                                    <option value="pilihan_ganda">Pilihan Ganda (1 Jawaban)</option>
                                    <option value="ganda_komplek">Ganda Kompleks (Multiple)</option>
                                    <option value="penjodohan">Penjodohan (Matching)</option>
                                    <option value="essay">Essay Singkat</option>
                                    <option value="uraian">Uraian Panjang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label-premium">JUMLAH SOAL</label>
                                <div class="d-flex align-items-center">
                                    <input type="range" class="custom-range flex-grow-1 mr-3" id="aiQuestionCount" min="1" max="20" value="5" oninput="$('#countLabel').text(this.value)">
                                    <span class="badge badge-primary py-2 px-3 rounded-lg" style="min-width: 45px;"><span id="countLabel">5</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-primary btn-block btn-lg rounded-xl font-weight-bold shadow-lg py-3 mt-2" onclick="generateQuestions()">
                        <i class="fas fa-wand-magic-sparkles mr-2"></i> GENERATE SOAL SEKARANG
                    </button>
                </div>

                <div id="aiLoadingSection" class="text-center py-5 d-none">
                    <div class="mb-4">
                        <div class="upload-icon-pulse bg-soft-primary mx-auto" style="width: 100px; height: 100px;">
                            <i class="fas fa-brain fa-3x text-primary animate-pulse"></i>
                        </div>
                    </div>
                    <h5 class="font-weight-bold text-dark">AI Sedang Berpikir...</h5>
                    <p class="text-muted">Menganalisis teks dan merancang butir soal terbaik.</p>
                    <div class="progress rounded-pill mx-auto mt-4" style="height: 8px; max-width: 300px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 100%"></div>
                    </div>
                </div>

                <div id="aiPreviewSection" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h6 class="font-weight-bold text-dark mb-0">HASIL GENERATE AI:</h6>
                        <div class="text-xs font-weight-bold text-muted uppercase tracking-widest"><i class="fas fa-info-circle mr-1"></i> Tinjau & Edit jika perlu sebelum menyimpan</div>
                    </div>
                    
                    <div id="aiQuestionsContainer" class="mb-4 custom-scrollbar" style="max-height: 450px; overflow-y: auto;">
                        <!-- AI Questions will be injected here -->
                    </div>

                    <div class="d-flex" style="gap:12px;">
                        <button type="button" class="btn btn-light rounded-xl font-weight-bold px-4" onclick="$('#aiPreviewSection').addClass('d-none'); $('#aiInputSection').removeClass('d-none');">
                            <i class="fas fa-undo mr-2"></i> ULANGI
                        </button>
                        <button type="button" class="btn btn-success flex-grow-1 rounded-xl font-weight-bold shadow-lg" onclick="saveAiQuestions()">
                            <i class="fas fa-save mr-2"></i> SIMPAN SEMUA SOAL KE BANK
                        </button>
                    </div>
                </div>
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
.nav-tabs-premium .nav-link { border: none; font-weight: 700; color: #64748b; padding: 12px 20px; transition: 0.3s; }
.nav-tabs-premium .nav-link:hover { color: var(--primary); background: #f8fafc; }
.nav-tabs-premium .nav-link.active { color: var(--primary); border-bottom: 3px solid var(--primary); background: transparent; }
.alert-soft-primary { background: #eef2ff; color: #4338ca; }
.alert-soft-info { background: #f0f9ff; color: #0369a1; }
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

    // Drop zone logic for Images
    var dzi=$('#dropZoneImages'), ifi=$('#imageFiles'), idc=$('#imageDropContent'), ipc=$('#imagePreviewContainer');
    dzi.on('click',function(){ifi.click();});
    dzi.on('dragover',function(e){e.preventDefault();$(this).addClass('dragover');});
    dzi.on('dragleave drop',function(){$(this).removeClass('dragover');});
    dzi.on('drop',function(e){
        e.preventDefault();
        ifi[0].files=e.originalEvent.dataTransfer.files;
        ifi.trigger('change');
    });
    ifi.on('change',function(){
        if(this.files.length){
            $('#imageCountText').text(this.files.length + ' Gambar terpilih');
            idc.addClass('d-none');
            ipc.removeClass('d-none');
        }
    });

    // Image Upload form submit
    $('#imageUploadForm').on('submit', function() {
        $('#btnUploadImages').html('<i class="fas fa-spinner fa-spin mr-2"></i>MENGHUBUNGKAN...').prop('disabled', true);
        Swal.fire({
            title: 'Sedang Memproses...',
            text: 'Harap tunggu, sistem sedang menghubungkan gambar ke soal.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
    });
});

function clearFile(){
    $('#excelFile').val('');
    $('#filePreview').addClass('d-none');
    $('.drop-content').first().removeClass('d-none');
}

function clearImages(){
    $('#imageFiles').val('');
    $('#imagePreviewContainer').addClass('d-none');
    $('#imageDropContent').removeClass('d-none');
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

function truncateQuestions() {
    Swal.fire({
        title: 'Kosongkan Bank Soal?',
        text: "SEMUA soal dan file gambar di bank ini akan dihapus permanen! Tindakan ini tidak dapat dibatalkan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang menghapus semua data...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            let form = document.createElement('form');
            form.action = `{{ route('admin.cbt.bank.truncateQuestions', $bank->id) }}`;
            form.method = 'POST';
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// AI GENERATOR FUNCTIONS
let generatedQuestions = [];

function generateQuestions() {
    const text = $('#aiSourceText').val();
    const type = $('#aiQuestionType').val();
    const count = $('#aiQuestionCount').val();
    const imageFile = $('#aiSourceImage')[0].files[0];

    if (!text && !imageFile) {
        Swal.fire({ icon: 'warning', title: 'Input Kosong', text: 'Harap masukkan teks materi atau unggah gambar agar AI bisa bekerja.', customClass: { popup: 'rounded-2xl' } });
        return;
    }

    $('#aiInputSection').addClass('d-none');
    $('#aiLoadingSection').removeClass('d-none');

    let formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('source_text', text);
    formData.append('type', type);
    formData.append('count', count);
    if (imageFile) formData.append('source_image', imageFile);

    $.ajax({
        url: '{{ route("admin.cbt.bank.ai_generate", $bank->id) }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.success) {
                generatedQuestions = res.questions;
                renderAiPreview();
                $('#aiLoadingSection').addClass('d-none');
                $('#aiPreviewSection').removeClass('d-none');
            } else {
                handleAiError(res.message);
            }
        },
        error: function(err) {
            handleAiError(err.responseJSON?.message || 'Terjadi kesalahan sistem saat menghubungi AI.');
        }
    });
}

// Add image label listener
$(document).on('change', '#aiSourceImage', function(){
    var fileName = $(this).val().split('\\').pop();
    if(fileName) $('#aiSourceImageLabel span').text(fileName).addClass('text-primary font-weight-bold');
});

function handleAiError(msg) {
    $('#aiLoadingSection').addClass('d-none');
    $('#aiInputSection').removeClass('d-none');
    Swal.fire({ icon: 'error', title: 'AI Error', text: msg, customClass: { popup: 'rounded-2xl' } });
}

function renderAiPreview() {
    const container = $('#aiQuestionsContainer');
    container.empty();

    generatedQuestions.forEach((q, idx) => {
        let html = `
            <div class="card border mb-3 shadow-sm rounded-xl overflow-hidden">
                <div class="card-header bg-light-gradient py-2 d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-sm text-primary">Soal #${idx + 1}</span>
                    <div>
                        <button class="btn btn-xs btn-soft-info mr-1" onclick="generateAiImage(${idx})" title="Generate Ilustrasi AI">
                            <i class="fas fa-magic"></i> Ilustrasi
                        </button>
                        <button class="btn btn-xs btn-link text-danger p-0" onclick="removeAiQuestion(${idx})"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <textarea class="form-control text-sm font-weight-bold mb-3 border-0 bg-transparent p-0" rows="2" onchange="updateAiQuestion(${idx}, 'question_text', this.value)">${q.question_text}</textarea>
                    
                    <div id="aiImagePreview-${idx}" class="mb-3 ${q.image_url ? '' : 'd-none'}">
                        <div class="position-relative rounded overflow-hidden shadow-sm" style="max-width: 200px;">
                            <img src="${q.image_url || ''}" class="img-fluid border rounded">
                            <button class="btn btn-xs btn-danger position-absolute" style="top:5px; right:5px;" onclick="removeAiImage(${idx})"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
        `;

        if (q.options) {
            html += `<div class="row no-gutters">`;
            q.options.forEach((opt, oIdx) => {
                const letter = String.fromCharCode(65 + oIdx);
                const isCorrect = opt.is_correct === true || opt.is_correct === "true" || opt.is_correct === 1;
                html += `
                    <div class="col-md-6 mb-2 pr-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text ${isCorrect ? 'bg-success text-white border-success' : 'bg-light'} font-weight-bold cursor-pointer" onclick="toggleAiOptionCorrect(${idx}, ${oIdx})">${letter}</span>
                            </div>
                            <input type="text" class="form-control" value="${opt.text || opt.option_text}" onchange="updateAiOption(${idx}, ${oIdx}, this.value)">
                        </div>
                    </div>
                `;
            });
            html += `</div>`;
        } else if (q.matching_pairs) {
            html += `<div class="bg-light p-2 rounded border">
                <h6 class="text-xs font-weight-bold text-muted mb-2 uppercase tracking-widest"><i class="fas fa-link mr-1"></i> Pasangan Penjodohan:</h6>`;
            Object.keys(q.matching_pairs).forEach((premise, pIdx) => {
                html += `
                    <div class="d-flex mb-1">
                        <div class="bg-white px-2 py-1 border rounded-left text-xs font-weight-bold text-muted d-flex align-items-center">${pIdx+1}</div>
                        <input type="text" class="form-control form-control-sm border-left-0 rounded-0" value="${premise}" readonly>
                        <div class="px-2 bg-white border-top border-bottom d-flex align-items-center"><i class="fas fa-arrow-right text-xs text-muted"></i></div>
                        <input type="text" class="form-control form-control-sm rounded-right" value="${q.matching_pairs[premise]}" onchange="updateAiMatching(${idx}, '${premise.replace(/'/g, "\\'")}', this.value)">
                    </div>
                `;
            });
            html += `</div>`;
        } else if (q.answer_key) {
            html += `
                <div class="bg-soft-success p-2 rounded border-left-success-4">
                    <small class="text-success font-weight-bold text-uppercase d-block mb-1">Kunci Jawaban:</small>
                    <textarea class="form-control form-control-sm border-0 bg-transparent p-0 text-dark" rows="2" onchange="updateAiQuestion(${idx}, 'answer_key', this.value)">${q.answer_key}</textarea>
                </div>
            `;
        }

        html += `</div></div>`;
        container.append(html);
    });
}

function toggleAiOptionCorrect(qIdx, oIdx) {
    const type = $('#aiQuestionType').val();
    if (type === 'pilihan_ganda') {
        generatedQuestions[qIdx].options.forEach((opt, i) => {
            opt.is_correct = (i === oIdx);
        });
    } else {
        generatedQuestions[qIdx].options[oIdx].is_correct = !generatedQuestions[qIdx].options[oIdx].is_correct;
    }
    renderAiPreview();
}

function updateAiMatching(qIdx, premise, newVal) {
    generatedQuestions[qIdx].matching_pairs[premise] = newVal;
}

function generateAiImage(idx) {
    const qText = generatedQuestions[idx].question_text;
    const btn = event.currentTarget;
    const originalHtml = $(btn).html();

    $(btn).html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

    $.post('{{ route("admin.cbt.bank.ai_generate_image", $bank->id) }}', {
        _token: '{{ csrf_token() }}',
        question_text: qText
    }).done(res => {
        if (res.success) {
            generatedQuestions[idx].image_url = res.image_url;
            generatedQuestions[idx].image_path = res.image_path;
            renderAiPreview();
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
        }
    }).fail((err) => {
        const msg = err.responseJSON?.message || 'Gagal menghubungi AI Image Generator.';
        Swal.fire({ icon: 'error', title: 'Error', text: msg });
    }).always(() => {
        $(btn).html(originalHtml).prop('disabled', false);
    });
}

function removeAiImage(idx) {
    delete generatedQuestions[idx].image_url;
    delete generatedQuestions[idx].image_path;
    renderAiPreview();
}

function removeAiQuestion(idx) {
    generatedQuestions.splice(idx, 1);
    renderAiPreview();
}

function updateAiQuestion(idx, key, val) {
    generatedQuestions[idx][key] = val;
}

function updateAiOption(qIdx, oIdx, val) {
    generatedQuestions[qIdx].options[oIdx].text = val;
}

function saveAiQuestions() {
    if (generatedQuestions.length === 0) {
        Swal.fire({ icon: 'warning', title: 'Kosong', text: 'Tidak ada soal untuk disimpan.', customClass: { popup: 'rounded-2xl' } });
        return;
    }

    Swal.fire({
        title: 'Simpan ke Bank?',
        text: `Konfirmasi untuk menyimpan ${generatedQuestions.length} soal ini ke dalam database.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'YA, SIMPAN',
        customClass: { popup: 'rounded-2xl' }
    }).then(res => {
        if (res.isConfirmed) {
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            
            $.post('{{ route("admin.cbt.bank.ai_save", $bank->id) }}', {
                _token: '{{ csrf_token() }}',
                questions: generatedQuestions,
                type: $('#aiQuestionType').val()
            }).done(res => {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, customClass: { popup: 'rounded-2xl' } })
                    .then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message, customClass: { popup: 'rounded-2xl' } });
                }
            }).fail(err => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan sistem saat menyimpan soal.', customClass: { popup: 'rounded-2xl' } });
            });
        }
    });
}
</script>
@endpush


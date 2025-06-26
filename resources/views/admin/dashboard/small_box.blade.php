<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>120</h3>
                <p>Post</p>
            </div>
            <div class="icon">
                <i class="fas fa-pencil-alt"></i> <!-- icon untuk Post -->
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>15</h3>
                <p>Kategori</p>
            </div>
            <div class="icon">
                <i class="fas fa-tags"></i> <!-- icon untuk Kategori -->
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>45</h3>
                <p>Tags</p>
            </div>
            <div class="icon">
                <i class="fas fa-tag"></i> <!-- icon untuk Tags -->
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>230</h3>
                <p>Komentar</p>
            </div>
            <div class="icon">
                <i class="fas fa-comments"></i> <!-- icon untuk Komentar -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-link"></i></span>
            <!-- icon untuk Tautan -->

            <div class="info-box-content">
                <span class="info-box-text">Tautan</span>
                <span class="info-box-number">75</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-alt"></i></span>
            <!-- icon untuk Halaman -->

            <div class="info-box-content">
                <span class="info-box-text">Halaman</span>
                <span class="info-box-number">10</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-book"></i></span>
            <!-- icon untuk Tulisan -->

            <div class="info-box-content">
                <span class="info-box-text">Tulisan</span>
                <span class="info-box-number">100</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-quote-right"></i></span>
            <!-- icon untuk Kutipan -->

            <div class="info-box-content">
                <span class="info-box-text">Kutipan</span>
                <span class="info-box-number">20</span>
            </div>
        </div>
    </div>
</div>

@if ($academicYear->admission_semester == 1)
    {{--  <div class="row">
        <!-- PPDB - Jumlah Pendaftar -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-graduate"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PPDB - Jumlah Pendaftar</span>
                    <span class="info-box-number">350</span>
                </div>
            </div>
        </div>

        <!-- PPDB - Kuota Jalur Prestasi -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Kuota Jalur Prestasi</span>
                    <span class="info-box-number">50</span>
                </div>
            </div>
        </div>

        <!-- PPDB - Pendaftar Jalur Prestasi -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pendaftar Jalur Prestasi</span>
                    <span class="info-box-number">70</span>
                </div>
            </div>
        </div>

        <!-- PPDB - Kuota Jalur Zonasi -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-map-marker-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Kuota Jalur Zonasi</span>
                    <span class="info-box-number">150</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- PPDB - Pendaftar Jalur Zonasi -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pendaftar Jalur Zonasi</span>
                    <span class="info-box-number">160</span>
                </div>
            </div>
        </div>

        <!-- PPDB - Kuota Jalur Afirmasi -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-hand-holding-heart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Kuota Jalur Afirmasi</span>
                    <span class="info-box-number">100</span>
                </div>
            </div>
        </div>

        <!-- PPDB - Pendaftar Jalur Afirmasi -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pendaftar Jalur Afirmasi</span>
                    <span class="info-box-number">120</span>
                </div>
            </div>
        </div>

        <!-- kosong untuk tata letak rapi -->
        <div class="col-12 col-sm-6 col-md-3"></div>
    </div>  --}}

    <div class="row">
        <!-- Total Pendaftar -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-graduate"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PPDB - Jumlah Pendaftar</span>
                    {{--  <span class="info-box-number">{{ $totalApplicants }}</span>  --}}
                </div>
            </div>
        </div>

        @foreach ($admissionTypes as $type)
            <!-- Kuota -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1">
                        <i
                            class="fas
                            @if ($type->admission_type_name == 'Prestasi') fa-star
                            @elseif($type->admission_type_name == 'Zonasi') fa-map-marker-alt
                            @elseif($type->admission_type_name == 'Afirmasi') fa-hand-holding-heart
                            @else fa-cog @endif"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kuota Jalur {{ $type->admission_type_name }}</span>
                        {{--  <span class="info-box-number">{{ $type->quota }}</span>  --}}
                    </div>
                </div>
            </div>

            <!-- Pendaftar -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1">
                        <i class="fas fa-user-check"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pendaftar Jalur {{ $type->admission_type_name }}</span>
                        {{--  <span class="info-box-number">{{ $type->applicants_count }}</span>  --}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <!-- PPDB - Diterima -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PPDB - Diterima</span>
                    <span class="info-box-number">280</span>
                </div>
            </div>
        </div>

        <!-- PPDB - Ditolak -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PPDB - Ditolak</span>
                    <span class="info-box-number">70</span>
                </div>
            </div>
        </div>

        <!-- PPDB - Berkas Lengkap -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Berkas Lengkap</span>
                    <span class="info-box-number">310</span>
                </div>
            </div>
        </div>

        <!-- PPDB - Berkas Belum Lengkap -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-excel"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Berkas Belum Lengkap</span>
                    <span class="info-box-number">40</span>
                </div>
            </div>
        </div>
    </div>
@endif

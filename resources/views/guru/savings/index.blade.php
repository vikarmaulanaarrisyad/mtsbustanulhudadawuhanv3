@extends($layout)

@section('title', 'Tabungan Siswa')

@section('content')
<!-- ANDROID-STYLE UI FOR GURU SAVINGS -->
<div class="savings-wrapper pb-20 font-outfit">
    <!-- TOP HEADER - MODERN GRADIENT -->
    <div class="header-banner bg-grad-rose pt-10 pb-24 px-6 relative overflow-hidden rounded-b-[3rem]">
        <div class="max-w-7xl mx-auto relative z-10 text-white text-center">
            <span class="bg-white/20 backdrop-blur-md text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-3 inline-block">
                <i class="fas fa-piggy-bank mr-2"></i> Wali Kelas Service
            </span>
            <h1 class="text-3xl font-black tracking-tight leading-tight">Tabungan Kelas {{ $homeroomClass->kelas_lengkap }}</h1>
            
            <!-- TOTAL BALANCE PILL -->
            <div class="mt-6 bg-white/10 backdrop-blur-lg px-8 py-4 rounded-full border border-white/20 inline-block shadow-2xl">
                <span class="block text-[8px] font-black text-white/60 uppercase tracking-widest mb-1">Total Saldo Kelas</span>
                <h2 class="text-2xl font-black text-white mb-0">Rp {{ number_format($totalSavings, 0, ',', '.') }}</h2>
            </div>
        </div>
        
        <!-- Decoration Circles -->
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-30px] bottom-[-30px] w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-4xl mx-auto px-6 -mt-12 relative z-20">
        <!-- QUICK STATS - APP GRID -->
        <div class="row g-4 mb-8">
            <div class="col-6">
                <div class="stat-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-3 shadow-sm">
                        <i class="fas fa-arrow-down text-sm"></i>
                    </div>
                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Setoran Hari Ini</span>
                    <h5 class="text-sm font-black text-slate-800 mb-0">Rp {{ number_format($totalDepositsToday, 0, ',', '.') }}</h5>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-3 shadow-sm">
                        <i class="fas fa-exchange-alt text-sm"></i>
                    </div>
                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</span>
                    <h5 class="text-sm font-black text-slate-800 mb-0">{{ $totalTransactionsToday }} TRX</h5>
                </div>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div class="bg-white rounded-[2.5rem] p-4 shadow-2xl shadow-slate-200/50 border border-slate-50 mb-8 flex items-center">
            <i class="fas fa-search ml-4 text-slate-300"></i>
            <input type="text" id="student_search" class="flex-grow bg-transparent border-none py-3 px-4 focus:ring-0 font-bold text-slate-600 text-sm" placeholder="Cari nama siswa...">
        </div>

        <!-- STUDENT LIST - ANDROID STYLE -->
        <div class="space-y-4" id="studentList">
            <!-- Data loaded via JS -->
            <div class="text-center py-20 text-slate-300 animate-pulse">
                <i class="fas fa-circle-notch fa-spin fa-2x mb-4"></i>
                <p class="font-bold text-xs">Memuat data siswa...</p>
            </div>
        </div>
    </div>
</div>

<!-- TRANSACTION MODAL - APP STYLE -->
<div class="modal fade" id="modal-transaction" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('guru.savings.store') }}" method="post" id="formTransaction" class="w-full">
            @csrf
            <input type="hidden" name="student_id" id="student_id">
            <input type="hidden" name="type" id="transaction_type">
            <div class="modal-content border-0 bg-transparent">
                <div class="bg-white rounded-[3rem] overflow-hidden shadow-3xl border border-slate-100">
                    <!-- Modal Header -->
                    <div class="px-8 pt-10 pb-6 text-center relative">
                        <div id="modalIconBox" class="w-20 h-20 rounded-[1.8rem] flex items-center justify-center mx-auto mb-5 shadow-2xl transition-all">
                            <i id="modalIcon" class="fas fa-exchange-alt text-3xl text-white"></i>
                        </div>
                        <h3 id="modalTitle" class="text-xl font-black text-slate-800 mb-1">Transaksi</h3>
                        <p id="student_name_display" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0">-</p>
                        <button type="button" class="absolute top-6 right-6 w-8 h-8 bg-slate-50 rounded-full text-slate-400 hover:text-rose-500 transition-all" data-dismiss="modal">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-8 pb-8">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-4">Nominal (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-slate-300 text-xl">Rp</span>
                                    <input type="number" name="amount" class="w-full bg-slate-50 border-none rounded-[1.5rem] py-6 pl-16 pr-6 text-2xl font-black text-slate-800 focus:ring-4 focus:ring-indigo-500/10 transition-all" placeholder="0" min="1000" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-4">Keterangan</label>
                                <input type="text" name="description" class="w-full bg-slate-50 border-none rounded-[1.2rem] py-4 px-6 font-bold text-slate-600 text-sm focus:ring-4 focus:ring-indigo-500/10 transition-all" placeholder="Opsional">
                            </div>

                            <button type="submit" id="submitBtn" class="w-full py-5 rounded-[1.5rem] shadow-2xl transition-all font-black text-white text-md tracking-wide">
                                KONFIRMASI
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&display=swap');
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .bg-grad-rose { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); }
    
    .student-card {
        background: white; border-radius: 2rem; padding: 20px;
        border: 1px solid #f1f5f9; transition: all 0.3s;
    }
    .student-card:active { transform: scale(0.98); background: #f8fafc; }

    .btn-circle { width: 40px; height: 40px; border-radius: 14px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; }
    
    .shadow-3xl { box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.1); }
</style>
@endsection

@push('scripts')
<script>
    let studentData = [];
    
    function loadStudents() {
        const search = $('#student_search').val() || '';
        $.get('{{ route("guru.savings.data") }}', { 
            search: { value: search }
        }, function(res) {
            studentData = res.data;
            renderStudents();
        });
    }

    function renderStudents() {
        const container = $('#studentList');
        if (studentData.length === 0) {
            container.html('<div class="text-center py-20"><p class="font-black text-slate-400 text-xs">Siswa tidak ditemukan</p></div>');
            return;
        }

        let html = '';
        studentData.forEach(s => {
            const balance = new Intl.NumberFormat('id-ID').format(s.balance);
            const historyUrl = '{{ route("guru.savings.history", ":id") }}'.replace(':id', s.id);
            
            html += `
            <div class="student-card shadow-sm active:shadow-inner transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-800 mb-0">${s.nama_lengkap}</h4>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">${s.nisn || '---'}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest">Saldo</span>
                        <h5 class="text-sm font-black text-indigo-600 mb-0">Rp ${balance}</h5>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-50">
                    <a href="${historyUrl}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest no-underline">
                        <i class="fas fa-history mr-1"></i> Riwayat
                    </a>
                    <div class="flex items-center space-x-2">
                        <button onclick="transactionForm(${s.id}, '${s.nama_lengkap.replace(/'/g, "\\'")}', 'debit')" class="btn-circle bg-emerald-50 text-emerald-600">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button onclick="transactionForm(${s.id}, '${s.nama_lengkap.replace(/'/g, "\\'")}', 'credit')" class="btn-circle bg-amber-50 text-amber-600">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>`;
        });
        container.html(html);
    }

    $(function() {
        loadStudents();
        $('#student_search').on('input', function() {
            clearTimeout(window.searchTimer);
            window.searchTimer = setTimeout(loadStudents, 500);
        });

        $('#formTransaction').submit(function(e) {
            e.preventDefault();
            let btn = $('#submitBtn');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>...');

            $.post($(this).attr('action'), $(this).serialize())
                .done(res => {
                    Swal.fire({ icon: 'success', title: 'BERHASIL', text: res.message, showConfirmButton: false, timer: 1500 });
                    $('#modal-transaction').modal('hide');
                    loadStudents();
                })
                .fail(xhr => {
                    Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message });
                })
                .always(() => {
                    btn.prop('disabled', false).html('KONFIRMASI');
                });
        });
    });

    function transactionForm(id, name, type) {
        $('#student_id').val(id);
        $('#student_name_display').text(name);
        $('#transaction_type').val(type);
        
        const iconBox = $('#modalIconBox');
        const title = $('#modalTitle');
        const btn = $('#submitBtn');
        
        if(type === 'debit') {
            iconBox.removeClass('bg-amber-500').addClass('bg-emerald-500 shadow-emerald-500/30');
            title.text('Setor Tunai');
            btn.removeClass('bg-amber-500').addClass('bg-emerald-500');
        } else {
            iconBox.removeClass('bg-emerald-500').addClass('bg-amber-500 shadow-amber-500/30');
            title.text('Tarik Tunai');
            btn.removeClass('bg-emerald-500').addClass('bg-amber-500');
        }
        $('#modal-transaction').modal('show');
    }
</script>
@endpush

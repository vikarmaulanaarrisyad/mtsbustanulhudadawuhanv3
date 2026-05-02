<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu QR Siswa - Premium Customizer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        @page {
            size: auto;
            margin: 1cm;
        }

        .card-grid { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        .card-grid-vertical {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        /* Paper Size Dimensions */
        .page-a4 { width: 210mm; }
        .page-f4 { width: 215mm; min-height: 330mm; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; margin: 0 !important; }
            .print-container { padding: 0 !important; margin: 0 !important; }
            .page-a4 { width: 210mm !important; }
            .page-f4 { width: 215mm !important; }
            .card-grid { 
                display: grid !important; 
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10mm !important;
            }
            .card-grid-vertical {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 5mm !important;
            }
            .card-item { break-inside: avoid; page-break-inside: avoid; margin: 0 !important; }
        }

        /* Card Sizes */
        .card-standard { width: 85.6mm; height: 54mm; }
        .card-vertical { width: 54mm; height: 85.6mm; }
    </style>
</head>
<body x-data="{ 
    theme: 'modern', 
    primaryColor: '#4f46e5', 
    showLogo: true, 
    showSchoolName: true,
    layout: 'horizontal',
    fontSize: 'normal',
    cardRounding: '1rem',
    paperSize: 'f4'
}">
    <!-- Configuration Panel (No Print) -->
    <div class="no-print sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200 px-6 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <i class="fas fa-palette"></i>
                </div>
                <div>
                    <h1 class="text-slate-900 font-extrabold text-lg leading-tight">Card Designer</h1>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Kustomisasi Kartu Presensi</p>
                </div>
            </div>

            <div class="flex items-center space-x-3 bg-slate-100 p-1 rounded-2xl">
                <!-- Paper Size -->
                <div class="flex items-center space-x-1 px-2 border-r border-slate-200 mr-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase mr-2">Kertas:</span>
                    <button @click="paperSize = 'a4'" :class="paperSize === 'a4' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all">A4</button>
                    <button @click="paperSize = 'f4'" :class="paperSize === 'f4' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all">F4 (Folio)</button>
                </div>

                <!-- Theme Select -->
                <div class="flex items-center space-x-1 px-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase mr-2">Tema:</span>
                    <button @click="theme = 'modern'; layout = 'horizontal'" :class="theme === 'modern' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all">Modern</button>
                    <button @click="theme = 'classic'; layout = 'horizontal'" :class="theme === 'classic' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all">Classic</button>
                    <button @click="theme = 'idcard'; layout = 'vertical'" :class="theme === 'idcard' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500'" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all">Pro ID</button>
                </div>
                
                <div class="w-px h-6 bg-slate-200 mx-2"></div>

                <!-- Color Picker -->
                <div class="flex items-center space-x-2 px-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase">Warna:</span>
                    <input type="color" x-model="primaryColor" class="w-8 h-8 rounded-lg border-0 cursor-pointer bg-transparent">
                </div>

                <div class="w-px h-6 bg-slate-200 mx-2"></div>

                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-xs font-black shadow-lg shadow-indigo-200 transition-all active:scale-95">
                    <i class="fas fa-print mr-2"></i> CETAK SEKARANG
                </button>
            </div>
        </div>
        
        <!-- Extended Settings -->
        <div class="max-w-7xl mx-auto mt-4 pt-4 border-t border-slate-100 flex items-center space-x-6">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" x-model="showLogo" class="rounded text-indigo-600">
                <span class="text-xs font-bold text-slate-600">Tampilkan Logo</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" x-model="showSchoolName" class="rounded text-indigo-600">
                <span class="text-xs font-bold text-slate-600">Nama Sekolah</span>
            </label>
            <div class="flex items-center space-x-2">
                <span class="text-[10px] font-black text-slate-400 uppercase">Radius:</span>
                <select x-model="cardRounding" class="text-xs font-bold bg-slate-50 border-0 rounded-lg px-2 py-1">
                    <option value="0px">Siku (0px)</option>
                    <option value="0.5rem">Small (8px)</option>
                    <option value="1rem">Medium (16px)</option>
                    <option value="2rem">Large (32px)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Print Canvas -->
    <div :class="`page-${paperSize}`" class="print-container mx-auto p-8 min-h-screen transition-all duration-500">
        <div :class="layout === 'horizontal' ? 'card-grid' : 'card-grid-vertical'">
            @foreach($students as $student)
                <div class="card-item flex items-center justify-center">
                    <!-- THEME: MODERN -->
                    <template x-if="theme === 'modern'">
                        <div :style="{ borderRadius: cardRounding }" class="card-standard bg-white shadow-[0_20px_50px_rgba(0,0,0,0.1)] relative overflow-hidden border border-slate-100 flex group hover:shadow-indigo-100 transition-all duration-500">
                            <!-- Left Accent Gradient -->
                            <div :style="{ background: `linear-gradient(to bottom, ${primaryColor}, ${primaryColor}dd)` }" class="w-3 h-full"></div>
                            
                            <!-- Main Content -->
                            <div class="flex-1 p-4 flex flex-col relative bg-gradient-to-br from-white via-white to-slate-50">
                                <!-- Watermark Logo -->
                                <div class="absolute -right-4 -bottom-4 opacity-[0.03] w-32 h-32 rotate-12">
                                    <img src="{{ asset('storage/' . ($setting->path_image ?? 'default.jpg')) }}" 
                                         onerror="this.src='{{ asset('AdminLTE/dist/img/AdminLTELogo.png') }}'"
                                         class="w-full h-full object-contain">
                                </div>

                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <template x-if="showLogo">
                                            <div class="w-10 h-10 bg-slate-50 rounded-lg p-1 border border-slate-100">
                                                <img src="{{ asset('storage/' . ($setting->path_image ?? 'default.jpg')) }}" 
                                                     onerror="this.src='{{ asset('AdminLTE/dist/img/AdminLTELogo.png') }}'"
                                                     class="w-full h-full object-contain">
                                            </div>
                                        </template>
                                        <div x-show="showSchoolName">
                                            <p class="text-[7px] font-black uppercase tracking-widest text-slate-400 leading-none mb-1">Kartu Presensi</p>
                                            <h2 class="text-slate-800 font-extrabold text-[9px] leading-tight max-w-[120px]">{{ $setting->company_name ?? 'MADRASAH' }}</h2>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-1.5 rounded-xl border border-slate-100 shadow-inner">
                                        {!! QrCode::size(70)->margin(1)->generate($student->nisn ?? $student->nis) !!}
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <h3 class="text-slate-900 font-jakarta font-black text-xs leading-none mb-1 uppercase tracking-tight">{{ $student->nama_lengkap }}</h3>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-[8px] font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded-md">{{ $student->kelas_lengkap }}</span>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">NISN: {{ $student->nisn ?? '-' }}</span>
                                    </div>
                                    <p class="text-[7px] text-slate-300 font-bold uppercase mt-2 tracking-[0.2em]">{{ $student->academicYear->academic_year ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- THEME: CLASSIC -->
                    <template x-if="theme === 'classic'">
                        <div :style="{ borderRadius: cardRounding }" class="card-standard bg-white border-2 border-slate-800 flex flex-col p-0 overflow-hidden">
                            <div :style="{ backgroundColor: primaryColor }" class="px-3 py-2 text-white flex items-center justify-between">
                                <span class="text-[9px] font-black uppercase tracking-[0.2em]">Kartu Siswa</span>
                                <span class="text-[9px] font-bold">{{ $setting->company_name ?? 'SEKOLAH' }}</span>
                            </div>
                            <div class="flex-1 flex p-3">
                                <div class="flex-1 flex flex-col">
                                    <div class="mb-2">
                                        <p class="text-[7px] text-slate-400 font-black uppercase mb-0.5">Nama Lengkap</p>
                                        <p class="text-[10px] font-black text-slate-800 uppercase">{{ $student->nama_lengkap }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <p class="text-[7px] text-slate-400 font-black uppercase mb-0.5">Kelas / NISN</p>
                                        <p class="text-[10px] font-bold text-slate-700">{{ $student->kelas_lengkap }} / {{ $student->nisn ?? '-' }}</p>
                                    </div>
                                    <div class="mt-auto">
                                        <p class="text-[8px] font-black italic text-slate-400 tracking-tighter">Bawa kartu ini setiap hari untuk presensi.</p>
                                    </div>
                                </div>
                                <div class="w-[80px] h-[80px] border-2 border-slate-100 rounded-lg flex items-center justify-center p-1">
                                    {!! QrCode::size(70)->margin(0)->generate($student->nisn ?? $student->nis) !!}
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- THEME: ID CARD (VERTICAL) -->
                    <template x-if="theme === 'idcard'">
                        <div :style="{ borderRadius: cardRounding }" class="card-vertical bg-white shadow-2xl relative overflow-hidden border border-slate-100 flex flex-col items-center p-4">
                            <!-- Background Decor -->
                            <div :style="{ backgroundColor: primaryColor }" class="absolute top-0 left-0 right-0 h-24 -skew-y-6 -translate-y-8 opacity-10"></div>
                            
                            <div x-show="showLogo" class="w-14 h-14 bg-white rounded-2xl p-2 shadow-xl border border-slate-50 mb-3 relative z-10">
                                <img src="{{ asset('storage/' . ($setting->path_image ?? 'default.jpg')) }}" 
                                     onerror="this.src='{{ asset('AdminLTE/dist/img/AdminLTELogo.png') }}'"
                                     class="w-full h-full object-contain">
                            </div>

                            <div x-show="showSchoolName" class="text-center mb-4 relative z-10">
                                <h2 class="text-slate-800 font-black text-[9px] leading-tight uppercase tracking-tight">{{ $setting->company_name ?? 'MADRASAH' }}</h2>
                                <p class="text-[7px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Kartu Identitas Siswa</p>
                            </div>

                            <div class="w-32 h-32 bg-white p-2 rounded-[2rem] shadow-inner border border-slate-50 mb-4 flex items-center justify-center">
                                {!! QrCode::size(110)->margin(1)->generate($student->nisn ?? $student->nis) !!}
                            </div>

                            <div class="text-center w-full mt-2">
                                <h3 :style="{ color: primaryColor }" class="font-jakarta font-black text-[11px] leading-tight mb-1 uppercase tracking-tight">{{ $student->nama_lengkap }}</h3>
                                <p class="text-slate-500 font-bold text-[9px]">{{ $student->kelas_lengkap }}</p>
                                
                                <div class="mt-4 pt-4 border-t border-slate-50 flex justify-center space-x-4">
                                    <div>
                                        <p class="text-[6px] text-slate-300 font-black uppercase">NISN</p>
                                        <p class="text-[8px] font-black text-slate-800">{{ $student->nisn ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[6px] text-slate-300 font-black uppercase">TP</p>
                                        <p class="text-[8px] font-black text-slate-800">{{ $student->academicYear->academic_year ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Strip -->
                            <div :style="{ backgroundColor: primaryColor }" class="absolute bottom-0 left-0 right-0 h-1.5"></div>
                        </div>
                    </template>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Mobile Warning -->
    <div class="no-print lg:hidden fixed inset-0 bg-slate-900 z-[100] flex flex-col items-center justify-center p-8 text-center text-white">
        <i class="fas fa-desktop text-5xl mb-6 text-indigo-400"></i>
        <h2 class="text-2xl font-black mb-4 leading-tight">Gunakan Perangkat Desktop</h2>
        <p class="text-slate-400 text-sm leading-relaxed mb-8">Fitur kustomisasi kartu dan pencetakan dioptimalkan untuk tampilan desktop agar hasil cetak presisi.</p>
        <a href="{{ route('student-attendances.index') }}" class="bg-indigo-600 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest">Kembali ke Dashboard</a>
    </div>
</body>
</html>

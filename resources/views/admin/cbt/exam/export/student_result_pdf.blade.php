<!DOCTYPE html>
<html>
<head>
    <title>Detail Hasil Ujian - {{ $studentExam->student->nama_lengkap }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; text-align: center; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        .question-box { border: 1px solid #eee; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .question-text { font-weight: bold; margin-bottom: 10px; }
        .options { margin-left: 20px; }
        .option { margin-bottom: 5px; }
        .correct { color: #10b981; font-weight: bold; }
        .wrong { color: #ef4444; font-weight: bold; }
        .student-ans { font-style: italic; margin-top: 5px; border-left: 3px solid #ddd; padding-left: 10px; }
        .score-banner { background: #f8fafc; padding: 15px; text-align: center; border-radius: 10px; margin-bottom: 20px; }
        .score-val { font-size: 24px; font-weight: 900; color: #4f46e5; }
    </style>
</head>
<body>
    <div class="header">
        <h2>HASIL DETAIL UJIAN CBT</h2>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">NAMA SISWA</td>
            <td width="35%">: <b>{{ $studentExam->student->nama_lengkap }}</b></td>
            <td width="15%">UJIAN</td>
            <td width="35%">: {{ $studentExam->exam->name }}</td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>: {{ $studentExam->student->nisn }}</td>
            <td>MAPEL</td>
            <td>: {{ $studentExam->exam->bank->subject->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>KELAS</td>
            <td>: {{ $studentExam->student->classGroup->group_name ?? '-' }}</td>
            <td>TANGGAL</td>
            <td>: {{ $studentExam->start_time ? $studentExam->start_time->format('d/m/Y H:i') : '-' }}</td>
        </tr>
    </table>

    <div class="score-banner">
        <div style="font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase;">NILAI AKHIR</div>
        <div class="score-val">{{ number_format($studentExam->final_score, 1) }}</div>
        <div style="font-size: 9px; color: #94a3b8; margin-top: 5px;">Pelanggaran: {{ $studentExam->violation_count }} kali</div>
    </div>

    <h3>ANALISIS JAWABAN</h3>

    @foreach($studentExam->answers as $index => $ans)
        <div class="question-box">
            <div class="question-text">
                {{ $index + 1 }}. {!! strip_tags($ans->question->question_text) !!}
            </div>
            
            <div class="student-ans">
                @if($ans->question->type == 'multiple_choice')
                    @php 
                        $correctOption = $ans->question->options->where('is_correct', true)->first();
                        $isCorrect = $ans->is_correct;
                    @endphp
                    Jawaban Anda: <b>{{ $ans->option->option_text ?? '-' }}</b> 
                    @if($isCorrect)
                        <span class="correct">(BENAR)</span>
                    @else
                        <span class="wrong">(SALAH)</span>
                        <br><small>Kunci Jawaban: {{ $correctOption->option_text ?? '-' }}</small>
                    @endif
                @else
                    Tipe soal: {{ $ans->question->type }} (Cek Dashboard Admin untuk detail dinamis)
                @endif
            </div>
        </div>
    @endforeach

    <div style="margin-top: 30px; font-size: 9px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px;">
        Dokumen ini dihasilkan secara otomatis oleh Sistem CBT MTS BUSTANUL HUDA DAWUHAN pada {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>

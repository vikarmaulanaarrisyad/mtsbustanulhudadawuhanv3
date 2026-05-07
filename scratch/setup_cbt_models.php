<?php

$modelsDir = __DIR__ . '/../app/Models/';

$models = [
    'CbtBank' => "    protected \$guarded = [];\n\n    public function subject()\n    {\n        return \$this->belongsTo(Subject::class);\n    }\n\n    public function teacher()\n    {\n        return \$this->belongsTo(Teacher::class);\n    }\n\n    public function questions()\n    {\n        return \$this->hasMany(CbtQuestion::class);\n    }\n\n    public function exams()\n    {\n        return \$this->hasMany(CbtExam::class);\n    }",
    'CbtQuestion' => "    protected \$guarded = [];\n\n    public function bank()\n    {\n        return \$this->belongsTo(CbtBank::class, 'cbt_bank_id');\n    }\n\n    public function options()\n    {\n        return \$this->hasMany(CbtOption::class);\n    }",
    'CbtOption' => "    protected \$guarded = [];\n\n    public function question()\n    {\n        return \$this->belongsTo(CbtQuestion::class, 'cbt_question_id');\n    }",
    'CbtExam' => "    protected \$guarded = [];\n\n    protected \$casts = [\n        'exam_date' => 'date',\n        'is_active' => 'boolean',\n    ];\n\n    public function bank()\n    {\n        return \$this->belongsTo(CbtBank::class, 'cbt_bank_id');\n    }\n\n    public function classes()\n    {\n        return \$this->belongsToMany(ClassGroup::class, 'cbt_exam_classes', 'cbt_exam_id', 'class_group_id');\n    }\n\n    public function studentExams()\n    {\n        return \$this->hasMany(CbtStudentExam::class);\n    }",
    'CbtExamClass' => "    protected \$guarded = [];\n\n    public \$timestamps = false;",
    'CbtStudentExam' => "    protected \$guarded = [];\n\n    protected \$casts = [\n        'start_time' => 'datetime',\n        'end_time' => 'datetime',\n    ];\n\n    public function exam()\n    {\n        return \$this->belongsTo(CbtExam::class, 'cbt_exam_id');\n    }\n\n    public function student()\n    {\n        return \$this->belongsTo(Student::class);\n    }\n\n    public function answers()\n    {\n        return \$this->hasMany(CbtStudentAnswer::class);\n    }",
    'CbtStudentAnswer' => "    protected \$guarded = [];\n\n    protected \$casts = [\n        'is_doubtful' => 'boolean',\n        'is_correct' => 'boolean',\n    ];\n\n    public function studentExam()\n    {\n        return \$this->belongsTo(CbtStudentExam::class, 'cbt_student_exam_id');\n    }\n\n    public function question()\n    {\n        return \$this->belongsTo(CbtQuestion::class, 'cbt_question_id');\n    }\n\n    public function option()\n    {\n        return \$this->belongsTo(CbtOption::class, 'cbt_option_id');\n    }"
];

foreach ($models as $name => $code) {
    $file = $modelsDir . $name . '.php';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = preg_replace('/\{\s+use HasFactory;/', "{\n    use HasFactory;\n\n" . $code, $content);
        file_put_contents($file, $content);
        echo "Updated $name\n";
    }
}

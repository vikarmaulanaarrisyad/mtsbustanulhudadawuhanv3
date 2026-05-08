<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Schema::disableForeignKeyConstraints();
Schema::dropIfExists('performance_assessment_details');
Schema::dropIfExists('performance_assessments');
Schema::dropIfExists('performance_indicators');
Schema::enableForeignKeyConstraints();
echo "Tables dropped.\n";

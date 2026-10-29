<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$u = App\Models\User::find(8);
Illuminate\Support\Facades\Auth::login($u);
echo "Logged in as " . $u->email . PHP_EOL;

$ctrl = app(App\Http\Controllers\ProfileController::class);
$v = $ctrl->index();
echo "Rendered view: " . $v->name() . PHP_EOL;
echo "HTML length: " . strlen($v->render()) . PHP_EOL;

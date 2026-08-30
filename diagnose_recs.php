<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use App\Models\Recommendation;
use App\Models\University;
use App\Models\Program;
use App\Models\User;
use App\Services\RecommendationEngine;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'driver' => $_ENV['DB_CONNECTION'] ?? 'sqlite',
    'database' => __DIR__ . '/' . ($_ENV['DB_DATABASE'] ?? 'database/database.sqlite'),
    'prefix' => '',
];
$db = new DB();
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$user = User::where('role', 'student')->first();
if (! $user) {
    echo "no student\n";
    exit(1);
}

echo "student={$user->user_id} {$user->email} prefer={$user->preferred_field} cgpa={$user->cgpa} lang={$user->language_proficiency} degree={$user->degree_seeking}\n";
$recCount = Recommendation::where('user_id', $user->user_id)->count();
echo "existing_recs=$recCount\n";
$engine = new RecommendationEngine();
$results = $engine->generate($user, 5);
echo "generated=".count($results)."\n";
foreach ($results as $r) {
    echo sprintf("program=%s score=%s field=%s cgpa=%s lang=%s rank=%s\n",
        $r['program']->program_name,
        $r['score'],
        $r['breakdown']['field_score'],
        $r['breakdown']['cgpa_score'],
        $r['breakdown']['language_score'],
        $r['breakdown']['ranking_score']
    );
}
$recCount2 = Recommendation::where('user_id', $user->user_id)->count();
echo "persisted=$recCount2\n";
$top = Recommendation::with(['program','university'])->where('user_id', $user->user_id)->orderByDesc('match_score')->take(5)->get();
foreach ($top as $rec) {
    echo sprintf("REC %s %s %s %s %s\n", $rec->recommendation_id, $rec->program->program_name, $rec->university->university_name, $rec->match_score, json_encode($rec->score_breakdown));
}

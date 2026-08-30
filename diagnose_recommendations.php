<?php
require __DIR__ . '/vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;
use App\Models\User;
use App\Models\Recommendation;
use App\Models\Program;
use App\Models\University;
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

echo "DB configured: {$config['driver']} {$config['database']}\n";
$users = User::all();
foreach ($users as $user) {
    echo "USER {$user->user_id} {$user->email} prefer={$user->preferred_field} cgpa={$user->cgpa} lang={$user->language_proficiency} degree={$user->degree_seeking} complete=".($user->hasCompleteProfile() ? 'yes':'no')."\n";
}
$recs = Recommendation::all();
echo "RECS count=".$recs->count()."\n";
foreach ($recs as $rec) {
    echo "REC {$rec->recommendation_id} user={$rec->user_id} program={$rec->program_id} score={$rec->match_score}\n";
}
$programs = Program::with('university')->active()->get();
echo "PROGRAMS active count=".$programs->count()."\n";

$activeByDegree = Program::active()->selectRaw('degree_level, count(*) as cnt')->groupBy('degree_level')->get();
foreach ($activeByDegree as $row) {
    echo "PROGRAMS active degree {$row->degree_level} count={$row->cnt}\n";
}

$student = User::where('role','student')->first();
if ($student) {
    echo "STUDENT {$student->user_id} {$student->email} complete=".($student->hasCompleteProfile() ? 'yes' : 'no')."\n";
    $engine = new RecommendationEngine();
    $results = $engine->generate($student, 5);
    echo "generated=".count($results)."\n";
    foreach ($results as $r) {
        echo "SCORE {$r['program']->program_name} {$r['score']} field={$r['breakdown']['field_score']} cgpa={$r['breakdown']['cgpa_score']} lang={$r['breakdown']['language_score']} rank={$r['breakdown']['ranking_score']}\n";
    }
    $persisted = Recommendation::where('user_id', $student->user_id)->get();
    echo "persisted=".$persisted->count()."\n";
    foreach ($persisted as $rec) {
        echo "REC {$rec->recommendation_id} program={$rec->program_id} score={$rec->match_score}\n";
    }
} else {
    echo "STUDENT none found\n";
}

<?php
$zip = new ZipArchive();
$zipName = "LogiTrack_Project.zip";

if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("❌ Error : Impossible de créer le ZIP.");
}

$files = [
    // 1. web.php (المصلح والمربوط بالـ Controllers)
    "routes/web.php" => '<?php 
        use Illuminate\\Support\\Facades\\Route;
        use App\\Http\\Controllers\\ClientController;
        Route::get("/clients", [ClientController::class, "index"])->name("clients.index");
        Route::post("/clients", [ClientController::class, "store"])->name("clients.store");
        Route::delete("/clients/{id}", [ClientController::class, "destroy"])->name("clients.destroy");',

    // 2. ClientController.php (اللي كيسير البيانات)
    "app/Http/Controllers/ClientController.php" => '<?php 
        namespace App\\Http\\Controllers;
        use App\\Models\\Client;
        use Illuminate\\Http\\Request;
        class ClientController extends Controller {
            public function index() { $clients = Client::latest()->get(); return view("clients.index", compact("clients")); }
            public function store(Request $request) { Client::create($request->all()); return back(); }
            public function destroy($id) { Client::destroy($id); return back(); }
        }',

    // 3. index.blade.php (الواجهة المصلحة)
    "resources/views/clients/index.blade.php" => '@extends("layouts.app")
        @section("content")
        <button data-bs-toggle="modal" data-bs-target="#addClientModal">Ajouter</button>
        <div class="modal fade" id="addClientModal"><form action="{{route(\'clients.store\')}}" method="POST">@csrf <input name="nom"> <button type="submit">Enregistrer</button></form></div>
        @foreach($clients as $client) <tr><td>{{$client->nom}}</td>
        <td><form action="{{route(\'clients.destroy\', $client->id)}}" method="POST">@csrf @method("DELETE") <button type="submit">Supprimer</button></form></td></tr> @endforeach
        @endsection'
];

foreach ($files as $path => $content) {
    $zip->addFromString($path, $content);
}
$zip->close();
echo "✅ LogiTrack_Project.zip créé avec succès !";
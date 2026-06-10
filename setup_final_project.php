<?php
$zip = new ZipArchive();
$zipName = "LogiTrack_STAGE_FINAL.zip";

if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("❌ Error : Impossible de créer le ZIP.");
}

$files = [
    // 1. README.md (باش المشرف يعرف كيفاش يخدم المشروع)
    "README.md" => "# LogiTrack ERP\n\nProjet de stage - Gestion de Transport et Transit.\n\n## Installation:\n1. `composer install`\n2. `cp .env.example .env`\n3. `php artisan migrate`\n4. `npm run dev`",
    
    // 2. .gitignore (باش المشروع يبقى نقي واحترافي)
    ".gitignore" => "/vendor\n/node_modules\n.env\n/storage/*.key\n/public/build",
];

foreach ($files as $path => $content) { $zip->addFromString($path, $content); }

$zip->close();
echo "✅ [LogiTrack_STAGE_FINAL.zip] prêt ! C'est ton projet final et propre. Bonne chance pour ton stage !\n";
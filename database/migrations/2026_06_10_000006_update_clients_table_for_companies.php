<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('clients')) {
            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement(<<<'SQL'
            CREATE TABLE clients_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                nom VARCHAR NOT NULL,
                prenom VARCHAR NULL,
                email VARCHAR NULL,
                telephone VARCHAR NULL,
                adresse VARCHAR NULL,
                entreprise VARCHAR NULL,
                ice VARCHAR NULL,
                ville VARCHAR NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )
        SQL);

        DB::statement(<<<'SQL'
            INSERT INTO clients_new (
                id,
                nom,
                prenom,
                email,
                telephone,
                adresse,
                entreprise,
                ice,
                ville,
                created_at,
                updated_at
            )
            SELECT
                id,
                nom,
                prenom,
                email,
                telephone,
                adresse,
                entreprise,
                ice,
                NULL,
                created_at,
                updated_at
            FROM clients
        SQL);

        DB::statement('DROP TABLE clients');
        DB::statement('ALTER TABLE clients_new RENAME TO clients');
        DB::statement('CREATE UNIQUE INDEX clients_email_unique ON clients (email)');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        if (! Schema::hasTable('clients')) {
            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement(<<<'SQL'
            CREATE TABLE clients_old (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                nom VARCHAR NOT NULL,
                prenom VARCHAR NOT NULL,
                email VARCHAR NOT NULL,
                telephone VARCHAR NULL,
                adresse VARCHAR NULL,
                entreprise VARCHAR NULL,
                ice VARCHAR NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )
        SQL);

        DB::statement(<<<'SQL'
            INSERT INTO clients_old (
                id,
                nom,
                prenom,
                email,
                telephone,
                adresse,
                entreprise,
                ice,
                created_at,
                updated_at
            )
            SELECT
                id,
                nom,
                COALESCE(prenom, ''),
                COALESCE(email, ''),
                telephone,
                adresse,
                entreprise,
                ice,
                created_at,
                updated_at
            FROM clients
        SQL);

        DB::statement('DROP TABLE clients');
        DB::statement('ALTER TABLE clients_old RENAME TO clients');
        DB::statement('CREATE UNIQUE INDEX clients_email_unique ON clients (email)');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};

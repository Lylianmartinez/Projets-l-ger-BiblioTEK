<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tableau de bord base de données — style phpLiteAdmin.
 *
 * Contrairement à un phpLiteAdmin autonome posé dans public/, ce dashboard
 * est servi par Laravel et protégé par le middleware role:bibliothecaire
 * (voir routes/web.php). Aucun mot de passe statique : l'accès passe par le
 * compte bibliothécaire existant.
 */
class DatabaseController extends Controller
{
    /** Lignes affichées par page lors du parcours d'une table. */
    private const PER_PAGE = 50;

    /**
     * Vue principale : liste des tables + parcours d'une table sélectionnée.
     */
    public function index(Request $request)
    {
        $tables = $this->tables();

        $table   = $request->query('table');
        $columns = [];
        $rows    = null;

        if ($table !== null && in_array($table, $tables, true)) {
            $columns = Schema::getColumnListing($table);
            $rows    = DB::table($table)->paginate(self::PER_PAGE)->withQueryString();
        } else {
            $table = null;
        }

        return view('bo.database.index', [
            'tables'  => $tables,
            'counts'  => $this->counts($tables),
            'table'   => $table,
            'columns' => $columns,
            'rows'    => $rows,
        ]);
    }

    /**
     * Exécute une requête SQL arbitraire (lecture et écriture).
     *
     * SELECT/PRAGMA/EXPLAIN/WITH renvoient un jeu de résultats ; les autres
     * verbes (INSERT/UPDATE/DELETE/CREATE/…) renvoient le nombre de lignes
     * affectées. Toute erreur SQL est capturée et affichée.
     */
    public function query(Request $request)
    {
        $sql = trim((string) $request->input('sql', ''));

        $result     = null;
        $resultCols = [];
        $affected   = null;
        $error      = null;

        if ($sql !== '') {
            try {
                $verb = strtolower((string) strtok($sql, " \t\n\r"));

                if (in_array($verb, ['select', 'pragma', 'explain', 'with'], true)) {
                    $rows       = DB::select($sql);
                    $result     = array_map(static fn ($r) => (array) $r, $rows);
                    $resultCols = $result !== [] ? array_keys($result[0]) : [];
                } else {
                    $affected = DB::affectingStatement($sql);
                }
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }
        }

        $tables = $this->tables();

        return view('bo.database.index', [
            'tables'     => $tables,
            'counts'     => $this->counts($tables),
            'table'      => null,
            'columns'    => [],
            'rows'       => null,
            'sql'        => $sql,
            'result'     => $result,
            'resultCols' => $resultCols,
            'affected'   => $affected,
            'error'      => $error,
        ]);
    }

    /** Liste des tables applicatives (hors tables internes SQLite). */
    private function tables(): array
    {
        $rows = DB::select(
            "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name"
        );

        return array_map(static fn ($r) => $r->name, $rows);
    }

    /** Nombre de lignes par table, pour la barre latérale. */
    private function counts(array $tables): array
    {
        $counts = [];

        foreach ($tables as $t) {
            try {
                $counts[$t] = DB::table($t)->count();
            } catch (\Throwable) {
                $counts[$t] = null;
            }
        }

        return $counts;
    }
}

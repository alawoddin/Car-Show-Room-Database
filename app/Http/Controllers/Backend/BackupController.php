<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class BackupController extends Controller
{
    private function backupDir(): string
    {
        $path = storage_path('app/backups');

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        return $path;
    }

    public function index()
    {
        $files = collect(File::files($this->backupDir()))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['sql', 'zip'], true))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values()
            ->map(fn ($file) => [
                'name' => $file->getFilename(),
                'size' => $this->humanSize($file->getSize()),
                'date' => date('Y-m-d H:i:s', $file->getMTime()),
            ]);

        return view('admin.Backup.backup', compact('files'));
    }

    public function create(Request $request)
    {
        $includeUploads = $request->boolean('include_uploads');
        $stamp = now()->format('Y-m-d_His');
        $sqlName = "carshow_backup_{$stamp}.sql";
        $sqlPath = $this->backupDir().DIRECTORY_SEPARATOR.$sqlName;

        File::put($sqlPath, $this->buildSqlDump());

        $downloadName = $sqlName;

        if ($includeUploads) {
            $zipName = "carshow_backup_{$stamp}.zip";
            $zipPath = $this->backupDir().DIRECTORY_SEPARATOR.$zipName;

            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return back()->with([
                    'message' => 'Could not create zip backup.',
                    'alert-type' => 'error',
                ]);
            }

            $zip->addFile($sqlPath, $sqlName);

            $uploadPath = public_path('upload');
            if (File::isDirectory($uploadPath)) {
                foreach (File::allFiles($uploadPath) as $file) {
                    $relative = 'upload/'.str_replace('\\', '/', $file->getRelativePathname());
                    $zip->addFile($file->getPathname(), $relative);
                }
            }

            $zip->close();
            File::delete($sqlPath);
            $downloadName = $zipName;
        }

        return redirect()
            ->route('admin.backup')
            ->with([
                'message' => 'Backup created: '.$downloadName,
                'alert-type' => 'success',
            ]);
    }

    public function download(string $file): BinaryFileResponse
    {
        $safe = basename($file);
        $path = $this->backupDir().DIRECTORY_SEPARATOR.$safe;

        abort_unless(File::exists($path), 404);

        return response()->download($path);
    }

    public function delete(string $file)
    {
        $safe = basename($file);
        $path = $this->backupDir().DIRECTORY_SEPARATOR.$safe;

        if (File::exists($path)) {
            File::delete($path);
        }

        return redirect()
            ->route('admin.backup')
            ->with([
                'message' => 'Backup deleted successfully',
                'alert-type' => 'success',
            ]);
    }

    private function buildSqlDump(): string
    {
        $database = config('database.connections.'.config('database.default').'.database');
        $lines = [];
        $lines[] = '-- Car Showroom Database Backup';
        $lines[] = '-- Generated: '.now()->toDateTimeString();
        $lines[] = '-- Database: '.$database;
        $lines[] = 'SET FOREIGN_KEY_CHECKS=0;';
        $lines[] = '';

        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_'.$database;

        foreach ($tables as $tableObj) {
            $table = $tableObj->{$key};
            $create = DB::select("SHOW CREATE TABLE `{$table}`");
            $createSql = $create[0]->{'Create Table'} ?? null;

            $lines[] = '-- --------------------------------------------------------';
            $lines[] = "-- Table: `{$table}`";
            $lines[] = "DROP TABLE IF EXISTS `{$table}`;";
            if ($createSql) {
                $lines[] = $createSql.';';
            }
            $lines[] = '';

            $rows = DB::table($table)->get();
            if ($rows->isEmpty()) {
                continue;
            }

            foreach ($rows->chunk(50) as $chunk) {
                $values = [];
                foreach ($chunk as $row) {
                    $cols = [];
                    foreach ((array) $row as $value) {
                        if ($value === null) {
                            $cols[] = 'NULL';
                        } else {
                            $cols[] = "'".str_replace(
                                ["\\", "'", "\n", "\r"],
                                ["\\\\", "\\'", "\\n", "\\r"],
                                (string) $value
                            )."'";
                        }
                    }
                    $values[] = '('.implode(', ', $cols).')';
                }

                $columns = array_map(
                    fn ($col) => '`'.$col.'`',
                    array_keys((array) $chunk->first())
                );

                $lines[] = 'INSERT INTO `'.$table.'` ('.implode(', ', $columns).') VALUES';
                $lines[] = implode(",\n", $values).';';
                $lines[] = '';
            }
        }

        $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';

        return implode("\n", $lines);
    }

    private function humanSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $size = (float) $bytes;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return number_format($size, $i === 0 ? 0 : 2).' '.$units[$i];
    }
}

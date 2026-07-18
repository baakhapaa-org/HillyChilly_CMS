<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Resources\PackageResource;
use App\Models\Package;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodesPage extends Page
{
    protected static string $resource = PackageResource::class;
    protected static string $view     = 'filament.resources.package-resource.pages.qr-codes';
    protected static ?string $title   = 'QR Codes';

    public Package $record;

    /** QR tasks for this package: [ ['title'=>..., 'code'=>..., 'svg'=>...], ... ] */
    public array $qrTasks = [];

    public function mount(Package $record): void
    {
        $this->record = $record;

        $this->qrTasks = $record->tasks()
            ->where('type', 'qr_scan')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($task) {
                $code = data_get($task->config, 'qrCode', $task->id);
                return [
                    'id'    => $task->id,
                    'title' => $task->title,
                    'code'  => $code,
                    // Inline SVG — rendered directly in blade
                    'svg'   => QrCode::format('svg')->size(250)->margin(2)->generate($code),
                ];
            })
            ->toArray();
    }

    /**
     * Download a single QR code as PNG.
     * Called via: /admin/packages/{id}/qr-codes/download?task_id=X
     */
    public function downloadQr(int $taskId): \Symfony\Component\HttpFoundation\Response
    {
        $task = $this->record->tasks()->where('id', $taskId)->firstOrFail();
        $code = data_get($task->config, 'qrCode', $task->id);
        $png  = QrCode::format('png')->size(600)->margin(2)->generate($code);

        $filename = str($task->title)->slug() . '-' . $code . '.png';

        return Response::make($png, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Download all QR codes for this package as a ZIP.
     */
    public function downloadAll(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $tasks = $this->record->tasks()
            ->where('type', 'qr_scan')
            ->orderBy('sort_order')
            ->get();

        $slug = str($this->record->title)->slug();

        return Response::streamDownload(function () use ($tasks) {
            $zip = new \ZipArchive();
            $tmpFile = tempnam(sys_get_temp_dir(), 'qr_');
            $zip->open($tmpFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            foreach ($tasks as $task) {
                $code     = data_get($task->config, 'qrCode', $task->id);
                $png      = QrCode::format('png')->size(600)->margin(2)->generate($code);
                $filename = str($task->title)->slug() . '-' . $code . '.png';
                $zip->addFromString($filename, $png);
            }

            $zip->close();
            readfile($tmpFile);
            unlink($tmpFile);
        }, "{$slug}-qr-codes.zip");
    }
}

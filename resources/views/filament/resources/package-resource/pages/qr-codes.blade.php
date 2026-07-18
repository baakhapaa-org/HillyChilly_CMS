<x-filament-panels::page>

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                QR Codes — {{ $this->record->title }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ count($this->qrTasks) }} QR scan checkpoint(s) found.
                Print each code and place it at its physical checkpoint.
            </p>
        </div>

        @if(count($this->qrTasks) > 0)
        <div class="flex gap-3">
            {{-- Download all as ZIP --}}
            <x-filament::button
                wire:click="downloadAll"
                icon="heroicon-o-arrow-down-tray"
                color="gray"
                size="sm"
            >
                Download All (ZIP)
            </x-filament::button>

            {{-- Print all --}}
            <x-filament::button
                onclick="window.print()"
                icon="heroicon-o-printer"
                color="primary"
                size="sm"
            >
                Print All
            </x-filament::button>
        </div>
        @endif
    </div>

    @if(count($this->qrTasks) === 0)
        <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-12 text-center text-gray-400">
            <x-heroicon-o-qr-code class="mx-auto mb-3 h-10 w-10" />
            <p class="font-medium">No QR scan tasks found for this package.</p>
            <p class="text-sm mt-1">Add tasks of type <code class="bg-gray-100 dark:bg-gray-800 px-1 rounded">qr_scan</code> to generate QR codes.</p>
        </div>
    @else
        {{-- QR grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 print:grid-cols-2 print:gap-4">
            @foreach($this->qrTasks as $task)
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 flex flex-col items-center gap-4 shadow-sm print:border print:border-gray-300 print:shadow-none print:break-inside-avoid">

                {{-- QR SVG --}}
                <div class="w-48 h-48 flex items-center justify-center">
                    {!! $task['svg'] !!}
                </div>

                {{-- Label --}}
                <div class="text-center">
                    <p class="font-semibold text-gray-900 dark:text-white text-sm leading-tight">
                        {{ $task['title'] }}
                    </p>
                    <p class="mt-1 font-mono text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded inline-block">
                        {{ $task['code'] }}
                    </p>
                </div>

                {{-- Per-card actions (hidden on print) --}}
                <div class="flex gap-2 print:hidden">
                    <x-filament::button
                        wire:click="downloadQr({{ $task['id'] }})"
                        icon="heroicon-o-arrow-down-tray"
                        color="gray"
                        size="xs"
                    >
                        Download PNG
                    </x-filament::button>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- Print styles --}}
    <style>
        @media print {
            [data-filament-sidebar], nav, footer, .print\:hidden { display: none !important; }
            body { background: white !important; }
        }
    </style>

</x-filament-panels::page>

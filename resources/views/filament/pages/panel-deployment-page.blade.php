<x-filament-panels::page>

    <div class="">
        @if(!$deployment)
            <div class="">
                <x-filament::button wire:click="startGeneration" class="">Start Generation</x-filament::button>
            </div>
        @else
            <div class="">
                <x-filament::button wire:click="startGeneration" class="">Start Generation</x-filament::button>
            </div>

            @if($deployment->status == 'pending')
                <div class="">
                    Generation is pending
                </div>
            @elseif($deployment->status == 'failed')
                <div class="">
                    Generation failed
                </div>
            @elseif($deployment->status == 'success')
                <div class="">

                    <div class="p-4">
                        Generation successful

                        <x-filament::link target="_blank" href="{{ $deployment->file_path }}" class="">
                            Download
                        </x-filament::link>
                    </div>
                </div>
            @endif
        @endif
    </div>

    @if($deployment)
        <div class="text-white p-4"
             style="background-color: #1f2937; max-height: 50vh; overflow: auto;"
             @if($deployment->status !== 'success')
                 wire:poll.1000ms
             @endif
             id="deployment-log"
        >
            {!! nl2br($deployment->deployment_log) !!}
        </div>

        @script
        <script>
            let element = document.getElementById('deployment-log');
            element.scrollTop = element.scrollHeight;
        </script>
        @endscript
    @endif
</x-filament-panels::page>

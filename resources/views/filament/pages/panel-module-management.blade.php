<x-filament-panels::page>
    <div class="flex flex-row gap-4">
        @foreach($modules as $slug => $title)
            @php($installed = $panel->modules->contains('slug', $slug))
            <div class="">
                <x-filament::button
                        :color="$installed ? 'danger' : 'info'"
                        wire:click="{{ $installed ? 'uninstall(\''.$slug.'\')' : 'install(\''.$slug.'\')' }}">
                    {{ $title }}
                    @if(!$installed)
                        (Install)
                    @else
                        (Uninstall)
                    @endif
                </x-filament::button>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
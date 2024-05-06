<x-filament-panels::page>

    <div class="">
        @foreach($panels as $panel)
            <div class="">
                <a href="#">
                    <div class="bg-white shadow-md p-4 mb-4">
                        <h3 class="text-xl">{{ $panel->name }}</h3>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

</x-filament-panels::page>

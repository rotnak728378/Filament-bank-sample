<x-filament-panels::page>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold tracking-tight">Our Banking Services</h2>
            <p class="mt-2 text-gray-600">Choose from our range of financial services</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center mb-4">
                            @switch($service->name)
                                @case('Money Transfer')
                                    <x-heroicon-o-arrow-path-rounded-square class="w-6 h-6 text-primary-500"/>
                                    @break
                                @case('Bill Payment')
                                    <x-heroicon-o-document-text class="w-6 h-6 text-primary-500"/>
                                    @break
                                @case('Check Processing')
                                    <x-heroicon-o-document-check class="w-6 h-6 text-primary-500"/>
                                    @break
                                @default
                                    <x-heroicon-o-banknotes class="w-6 h-6 text-primary-500"/>
                            @endswitch
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $service->name }}
                        </h3>

                        <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                            {{ $service->description }}
                        </p>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xl font-semibold text-primary-600">
                                ${{ number_format($service->fee, 2) }}
                            </span>

                            <x-filament::button size="sm">
                                Learn More
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center" style="margin-top: 150px;">
            <p class="text-gray-600 mb-4">Need assistance choosing a service?</p>
            <x-filament::button size="lg">
                Contact Support
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    <form wire:submit="save">
        {{ $form }}

        <x-filament::button
            type="submit"
            class="mt-6"
        >
            Save changes
        </x-filament::button>
    </form>
</x-filament-panels::page>

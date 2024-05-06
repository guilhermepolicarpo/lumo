<div class="mx-auto mt-20 md:w-[450px]">
    <x-card class="p-10">
        <div class="mb-10">
            <div class="flex items-center justify-center gap-2">
                <x-icon name="o-square-3-stack-3d" class="w-6 -mb-1 text-purple-500" />
                <span class="mr-3 text-3xl font-bold text-transparent bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text ">
                    Lumo
                </span>
            </div>
            <p class="mt-2 text-center">Gestão de Centro Espírita</p>
        </div>

        <x-form wire:submit="login">
            <x-input label="E-mail" wire:model="email" icon="o-envelope" inline />
            <x-input label="Password" wire:model="password" type="password" icon="o-key" inline />

            <x-slot:actions>
                <x-button label="Login" type="submit" icon="o-paper-airplane" class="text-base btn-primary" spinner="login" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>

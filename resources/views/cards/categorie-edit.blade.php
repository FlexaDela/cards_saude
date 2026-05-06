<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Categoria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
                        @csrf

                        <!-- Nome -->
                        <div>
                            <x-input-label for="nome" :value="__('Nome da Categoria')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <!-- Descrição -->
                        <div>
                            <x-input-label for="descricao" :value="__('Descrição')" />
                            <textarea id="descricao" name="descricao" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('descricao') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Salvar Categoria') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

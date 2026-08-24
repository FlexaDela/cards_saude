<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoria: ') . $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             @if ($messageSuccess)
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center mb-6">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm text-green-700 font-medium">{{ $messageSuccess }}</p>
                </div>
            @endif

            @if ($messageError)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex items-center mb-6">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-red-700 font-medium">{{ $messageError }}</p>
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- FORMULÁRIO DE EDIÇÃO --}}
                    <form method="POST" action="{{ route('categories.update', $category->id) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Nome -->
                        <div>
                            <x-input-label for="name" :value="__('Nome da Categoria')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Descrição -->
                        <div>
                            <x-input-label for="description" :value="__('Descrição')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description', $category->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t pt-6">
                            {{-- Botão Salvar --}}
                            <x-primary-button class="w-full sm:w-auto justify-center">
                                {{ __('Atualizar Categoria') }}
                            </x-primary-button>
                    </form>

                    {{-- Botão Voltar --}}
                    <div class="mt-8">
                        <a href="{{ route('cards.index') }}" class="text-sm text-gray-600 hover:text-indigo-600 underline decoration-indigo-200 underline-offset-4">
                            &larr; Voltar para a listagem
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoria: ') . $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

                    {{-- FORMULÁRIO DE DELETAR (Separado por segurança) --}}
                    <form method="POST" action="{{ route('categories.destroy', $category->id) }}" onsubmit="return confirm('ATENÇÃO: Deletar esta categoria apagará TODOS os cards vinculados a ela. Confirmar exclusão?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Excluir Categoria') }}
                        </button>
                    </form>
                    </div>

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

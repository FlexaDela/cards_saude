<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Card: ') }} {{ $card->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6">

                <form method="POST" action="{{ route('cards.update', $card->id) }}">
                    @csrf
                    @method('PATCH')

                    <!-- Nome -->
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nome do Card')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $card->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Descrição -->
                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Descrição')" />
                        <textarea id="description" name="description" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $card->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Grid Responsivo para Categoria e Preço -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Categoria -->
                        <div class="mt-4">
                            <x-input-label for="categories" :value="__('Categorias (Selecione uma ou mais)')" />

                            <select id="categories" name="categories[]" multiple
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-40">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @if(isset($card) && $card->categories->contains($category->id)) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <p class="text-xs text-gray-500 mt-1 italic">Segure Ctrl (Windows) ou Command (Mac) para selecionar mais de uma.</p>
                            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                        </div>

                        <!-- Preço -->
                        <div>
                            <x-input-label for="price" :value="__('Preço (R$)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $card->price)" required />
                        </div>
                    </div>

                    <!-- Checkboxes Responsivos (lado a lado ou coluna no mobile) -->
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 mb-6 mt-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="available" value="1" {{ old('available', $card->available) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                            <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Disponível para venda') }}</span>
                        </label>

                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show" value="1" {{ old('show', $card->show) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                            <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Mostrar na vitrine pública') }}</span>
                        </label>
                    </div>

                    <hr class="my-6 border-gray-100">

                    <!-- Ações: Botão Voltar e Atualizar -->
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 mt-6">
                        <a href="{{ route('cards.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Cancelar e Voltar') }}
                        </a>

                        <x-primary-button class="w-full sm:w-auto justify-center">
                            {{ __('Salvar Alterações') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

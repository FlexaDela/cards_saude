<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Novo Card') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

               <form method="POST" action="{{ route('cards.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nome do Card')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Descrição')" />
                        <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <x-input-label for="categories" :value="__('Selecione as Categorias')" />
                            <select id="categories" name="categories[]" multiple class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-40">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1 italic">Segure Ctrl (Windows) ou Cmd (Mac) para marcar várias.</p>
                            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Preço (Ex: 10.50)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <x-input-label for="images" :value="__('Imagens do Card')" />

                        <input id="images" name="images[]" type="file" multiple accept="image/*"
                            class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />

                        <p class="text-xs text-gray-500 mt-1 italic">Você pode selecionar múltiplos arquivos de uma vez. O primeiro arquivo da lista será definido como a capa por padrão.</p>

                        {{-- Erros gerais do array de imagens --}}
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                        {{-- Erros individuais de cada imagem específica (ex: formato ou tamanho inválido) --}}
                        <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                    </div>

                   <div class="mt-4 flex flex-col sm:flex-row gap-4 sm:gap-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="available" value="1" @checked(old('available', $card->available)) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                            <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Disponível') }}</span>
                        </label>

                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show" value="1" @checked(old('show', $card->show)) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                            <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Mostrar na vitrine') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t border-gray-100">
                        <a href="{{ route('cards.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancelar e Voltar') }}
                        </a>
                        <x-primary-button class="w-full sm:w-auto justify-center">
                            {{ __('Salvar Card') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

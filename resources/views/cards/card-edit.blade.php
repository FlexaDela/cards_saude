<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('cards.index') }}" class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-300 rounded-full text-gray-600 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-sm" title="Voltar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Card: ') }} <span class="text-indigo-600">{{ $card->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('message.success') || !empty($messageSuccess))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm text-green-700 font-medium">{{ session('message.success') ?? $messageSuccess }}</p>
                </div>
            @endif

            @if (session('message.error') || !empty($messageError))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-red-700 font-medium">{{ session('message.error') ?? $messageError }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-4 sm:p-6 border border-gray-100">
                <form method="POST" action="{{ route('cards.update', $card->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nome do Card')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $card->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Descrição')" />
                        <textarea id="description" name="description" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $card->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="mt-4">
                            <x-input-label for="categories" :value="__('Categorias (Selecione uma ou mais)')" />
                            <select id="categories" name="categories[]" multiple class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-40">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @if(isset($card) && $card->categories->contains($category->id)) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1 italic">Segure Ctrl (Windows) ou Cmd (Mac) para selecionar mais de uma.</p>
                            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Preço (R$)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $card->price)" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('Adicionar Novas Imagens') }}</h3>
                        <x-input-label for="images" :value="__('Selecione arquivos do seu dispositivo')" />
                        <input id="images" name="images[]" type="file" multiple accept="image/*"
                               class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                        <p class="text-xs text-gray-500 mt-1 italic">Ao salvar as alterações, essas imagens serão acrescentadas à galeria do card.</p>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                        <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 mb-6 mt-8">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="hidden" name="available" value="0">
                            <input type="checkbox" name="available" value="1" {{ old('available', $card->available) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                            <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Disponível para venda') }}</span>
                        </label>

                        <label class="inline-flex items-center cursor-pointer">
                            <input type="hidden" name="show" value="0">
                            <input type="checkbox" name="show" value="1" {{ old('show', $card->show) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                            <span class="ms-2 text-sm text-gray-600 font-medium">{{ __('Mostrar na vitrine pública') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 mt-8 pt-6 border-t border-gray-100">
                        <x-primary-button class="w-full sm:w-auto justify-center">
                            {{ __('Salvar Alterações') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-4 sm:p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('Gerenciar Imagens Atuais') }}</h3>
                <p class="text-xs text-gray-500 mb-4">As alterações feitas nas fotos aqui (excluir ou tornar capa) são aplicadas imediatamente.</p>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    @if(isset($card->cardImages) && $card->cardImages->count() > 0)
                        <div class="flex flex-wrap gap-4">
                            @foreach($card->cardImages as $image)
                                <div class="relative w-36 bg-white rounded-lg overflow-hidden border {{ $image->principal ? 'border-indigo-500 shadow-md' : 'border-gray-200 shadow-sm' }} flex flex-col">

                                    <div class="h-28 w-full relative bg-gray-100">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover" alt="Imagem do card">

                                        @if($image->principal)
                                            <div class="absolute top-0 left-0 w-full bg-indigo-600 text-white text-[10px] text-center font-bold py-1 uppercase tracking-widest shadow-sm">
                                                Capa Principal
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex border-t border-gray-200 bg-gray-50">

                                        @if(!$image->principal)
                                            <form action="{{ route('cards.makeCoverImage', $image->id) }}" method="POST" class="flex-1 border-r border-gray-200 hover:bg-blue-50 transition m-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full h-full py-2 text-[9px] font-bold text-blue-600 uppercase tracking-wider">
                                                    Tornar Capa
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex-1 flex items-center justify-center border-r border-gray-200 bg-gray-100 m-0">
                                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider cursor-not-allowed">Atual</span>
                                            </div>
                                        @endif

                                        <form action="{{ route('cards.destroyImage', $image->id) }}" method="POST" class="flex-1 hover:bg-red-50 transition m-0" onsubmit="return confirm('Tem certeza que deseja apagar esta foto em definitivo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full h-full py-2 text-[9px] font-bold uppercase tracking-wider {{ $image->principal ? 'text-red-300 cursor-not-allowed' : 'text-red-600' }}" {{ $image->principal ? 'disabled' : '' }}>
                                                Excluir
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic text-center py-2">Este card ainda não possui imagens na galeria.</p>
                    @endif
                </div>
            </div>

            <div class="bg-red-50 shadow-sm sm:rounded-lg p-4 sm:p-6 border border-red-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-red-800">{{ __('Excluir Card') }}</h3>
                    <p class="text-sm text-red-600 mt-1">Uma vez excluído, todos os dados e imagens deste card serão apagados permanentemente.</p>
                </div>

                <form method="POST" action="{{ route('cards.destroy', $card->id) }}" onsubmit="return confirm('ATENÇÃO: Tem certeza que deseja excluir este card? Esta ação não pode ser desfeita!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 whitespace-nowrap">
                        {{ __('Excluir Permanentemente') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>

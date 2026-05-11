<x-app-layout>
    <x-slot name="header">
        {{-- Header Responsivo: Empilha no mobile, alinha no desktop --}}
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciamento de Cards') }}
            </h2>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('categories.create') }}" class="flex-1 text-center sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition duration-150">
                    + Categoria
                </a>
                <a href="{{ route('cards.create') }}" class="flex-1 text-center sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition duration-150 shadow-sm">
                    + Novo Card
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @forelse($categories as $category)
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 border-b-2 border-indigo-100 pb-2 flex items-center">
                        <span class="bg-indigo-500 w-2 h-6 mr-2 rounded"></span>
                        {{ $category->name }}
                        <span class="ml-2 text-sm font-normal text-gray-400">({{ $category->cards->count() }})</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

                        @forelse($category->cards as $card)
                            <div class="bg-white shadow-sm rounded-xl p-5 border border-gray-100 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-gray-800 text-lg leading-tight">{{ $card->name }}</h4>
                                        <span class="text-indigo-600 font-mono font-bold whitespace-nowrap ml-2">
                                            {{ $card->formatted_price }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-500 line-clamp-3 italic mb-4">
                                        {{ $card->description ?? 'Sem descrição informada.' }}
                                    </p>

                                    <!-- Badges de Status -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $card->available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $card->available ? 'Disponível' : 'Esgotado' }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $card->show ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $card->show ? 'Visível' : 'Oculto' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Ações: Editar e Deletar -->
                                <div class="mt-4 pt-4 border-t flex items-center justify-between">
                                    <a href="{{ route('cards.edit', $card->id) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-900 transition">
                                        {{ __('Editar') }}
                                    </a>

                                    <form action="{{ route('cards.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este card?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition">
                                            {{ __('Excluir') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg p-6 text-center">
                                <p class="text-gray-500 text-sm">Nenhum card cadastrado em {{ $category->name }}.</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Seu catálogo está vazio!</h3>
                    <p class="text-gray-500 mb-8">Crie uma categoria para começar a organizar seus cards.</p>
                    <a href="{{ route('categories.create') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Começar Agora
                    </a>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>

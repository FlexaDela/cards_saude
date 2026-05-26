<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Topo: Botões de Criar -->
            <div class="flex gap-4 mb-8">
                <a href="{{ route('categories.create') }}" class="bg-gray-800 text-white px-6 py-3 rounded-lg font-bold uppercase text-xs tracking-widest hover:bg-gray-700 transition">Criar Categoria</a>
                <a href="{{ route('cards.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold uppercase text-xs tracking-widest hover:bg-indigo-700 transition">Criar Card</a>
            </div>

            <div class="flex flex-col md:flex-row gap-8">

                <!-- Esquerda: Grid de Cards (Lista Geral) -->
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($cards as $card)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                            <div>
                                @if ($card->coverImage)
                                    <div class="w-full h-32 bg-gray-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden shadow-sm border border-gray-100">
                                        <img src="{{ asset('storage/' . $card->coverImage->path) }}" alt="Capa: {{ $card->name }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-full h-32 bg-gray-50 border border-dashed border-gray-200 rounded-xl mb-4 flex items-center justify-center text-gray-400 font-bold uppercase tracking-tighter text-xs shadow-sm">
                                        Sem Imagem
                                    </div>
                                @endif
                                <h4 class="font-bold text-lg text-gray-800">{{ $card->name }}</h4>

                                <!-- Loop de Categorias do Card -->
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($card->categories as $cat)
                                        <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full font-bold uppercase">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-50">
                                <span class="font-bold text-indigo-600">{{ $card->formatted_price }}</span>
                                <a href="{{ route('cards.edit', $card->id) }}" class="text-xs font-bold text-gray-400 hover:text-indigo-600 transition uppercase">Editar</a>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500 py-10">Nenhum card encontrado com esses filtros.</p>
                    @endforelse
                </div>

                <div class="w-full md:w-64">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-4">
                        <h3 class="font-bold text-gray-800 uppercase tracking-widest text-sm mb-6">Filtros</h3>

                        <form action="{{ route('cards.index') }}" method="GET" class="space-y-6">

                            <!-- Checkbox Disponível -->
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="disponivel" value="1" {{ request('disponivel') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                                <span class="ml-3 text-sm font-medium text-gray-600 group-hover:text-indigo-600 transition">Disponível</span>
                            </label>

                            <!-- Checkbox Visível -->
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="visivel" value="1" {{ request('visivel') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                                <span class="ml-3 text-sm font-medium text-gray-600 group-hover:text-indigo-600 transition">Visível</span>
                            </label>

                            <!-- Select de Categoria -->
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block tracking-widest">Categoria</label>
                                <select name="category_id" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Todos</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">
                                Aplicar Filtros
                            </button>

                            @if(request()->anyFilled(['disponivel', 'visivel', 'category_id']))
                                <a href="{{ route('cards.index') }}" class="block text-center text-[10px] font-bold text-red-400 uppercase mt-4 hover:text-red-600 transition">Limpar Filtros</a>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

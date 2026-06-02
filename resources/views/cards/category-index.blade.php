<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-300 rounded-full text-gray-600 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-sm" title="Voltar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Categorias') }}
                </h2>
            </div>

            <a href="{{ route('categories.create') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                Nova Categoria
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">{{ __('Lista de Categorias Cadastradas') }}</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold tracking-widest border-b border-gray-200">
                                <th class="px-4 sm:px-6 py-4">Nome</th>
                                <th class="px-4 sm:px-6 py-4 text-center">Quantidade de Cards</th>
                                <th class="px-4 sm:px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-800">{{ $category->name }}</div>
                                    </td>

                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        @if($category->cards->count() > 0)
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">
                                                {{ $category->cards->count() }} Cards
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold">
                                                0 Cards
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-2 sm:gap-3">
                                            <a href="{{ route('categories.edit', $category->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-[10px] font-bold text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                                                Editar
                                            </a>

                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Tem certeza que deseja apagar a categoria \'{{ $category->name }}\'? Esta ação é irreversível.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-200 rounded-md text-[10px] font-bold text-red-600 uppercase tracking-widest shadow-sm hover:bg-red-100 transition">
                                                    Deletar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-500 text-sm italic">
                                        Nenhuma categoria cadastrada ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

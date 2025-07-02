<x-app-layout>
    <div class="w-full xl:w-6/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="block w-full overflow-x-auto py-5 px-5">
                <form action="{{route('documentos.categorias.store')}}" method="POST">
                    {{ csrf_field() }}
                    <div class="flex items-center space-x-5">
                        <div class="block pl-2 font-semibold text-xl self-start text-gray-700">
                          <h2 class="leading-relaxed">Crear Nueva Categoría</h2>
                        </div>
                      </div>
                      <div class="divide-y divide-gray-200">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                          <div class="flex flex-col">
                            <label class="leading-loose">Nombre de Categoría</label>
                            <input type="text" name="nombre" class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600" placeholder="Nombre del Categoría">
                          </div>
                          <div class="flex flex-col">
                            <label class="leading-loose">Categoría Padre</label>
                            <select name="categoria_id" class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600">
                              <option>Es Padre</option>
                              @foreach ($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="pt-4 flex items-center space-x-4">
                            <a class="flex justify-center items-center w-full text-gray-900 px-4 py-3 rounded-md focus:outline-none" href="{{route('documentos.categorias.index')}}">Cancelar</a>
                            <button class="bg-blue-500 flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none">Crear Nuevo</button>
                        </div>
                      </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="w-full xl:w-6/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="block w-full overflow-x-auto py-5 px-5">
                <form action="{{route('usuarios.sedes.update', $sede)}}" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="flex items-center space-x-5">
                        <div class="block pl-2 font-semibold text-xl self-start text-gray-700">
                          <h2 class="leading-relaxed">Editar Sede - ID: {{$sede->id}}</h2>
                        </div>
                      </div>
                      <div class="divide-y divide-gray-200">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                          <div class="flex flex-col">
                            <label class="leading-loose">Nombre de la Sede</label>
                            <input type="text" name="nombre" value="{{$sede->nombre}}" class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600" placeholder="Nombre de la Sede">
                          </div>
                        </div>
                        <div class="pt-4 flex items-center space-x-4">
                            <a class="flex justify-center items-center w-full text-gray-900 px-4 py-3 rounded-md focus:outline-none" href="{{route('usuarios.sedes.index')}}">Cancelar</a>
                            <button class="bg-blue-500 flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none" type="submit">Actualizar</button>
                        </div>
                      </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
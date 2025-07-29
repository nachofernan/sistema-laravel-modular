<x-app-layout>
    <div class="w-full max-w-7xl mx-auto">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="block w-full overflow-x-auto py-5 px-5">
                <form action="{{route('usuarios.roles.update', $role)}}" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="flex items-center space-x-5">
                        <div class="block pl-2 font-semibold text-xl self-start text-gray-700">
                          <h2 class="leading-relaxed">Editar Rol</h2>
                        </div>
                      </div>
                      <div class="divide-y divide-gray-200">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                          <div class="flex flex-col">
                            <label class="leading-loose">Nombre del Rol</label>
                            <input type="text" name="name" value="{{$role->name}}" placeholder="Nombre de Rol" class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600">
                          </div>
                          <div class="flex flex-col">
                            <label class="text-sm">El Rol debe tener la siguiente práctica: <span class="font-bold">(Nombre-de-sistema)/(Rol-en-sistema)</span></label>
                            <label class="text-sm">Ejemplo: <span class="font-bold">Proveedores/Documentación</span> (con permisos crud en documentación)</label>
                            <label class="text-sm">Ejemplo: <span class="font-bold">Plataforma/Sistemas</span> (con acceso full de sistemas)</label>
                            <label class="text-sm">El Rol Admin es único y contempla a todos los sistemas y permisos.</label>
                          </div>
                        </div>
                        <div class="pt-4 flex items-center space-x-4">
                            <a class="flex justify-center items-center w-full text-gray-900 px-4 py-3 rounded-md focus:outline-none" href="{{route('usuarios.roles.index')}}">Cancelar</a>
                            <button class="bg-blue-500 flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none">Actualizar</button>
                        </div>
                      </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
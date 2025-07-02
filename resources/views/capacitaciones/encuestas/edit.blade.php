<x-app-layout>
    <div class="border-b border-gray-200 rounded-lg bg-gray-200 mx-auto w-3/4 px-0 py-5 mt-10 text-sm">
        <h1 class="h1 border-b border-gray-300 mb-3">
            Editar Encuesta
            {!! link_to_route('capacitaciones.encuestas.show', 'Volver a la Encuesta', $encuesta, ['class' => 'float-right rounded bg-blue-600 text-gray-100 text-sm py-1 px-5']) !!}
        </h1>

        
        {!! Form::model($encuesta, ['route' => ['capacitaciones.encuestas.update', $encuesta], 'method' => 'put']) !!}

        <div class="form-group grid grid-cols-12 py-1 mx-auto">
            {!! Form::label('nombre', 'Nombre *', ['class' => 'label col-span-4']) !!}
            {!! Form::text('nombre', null, ['class' => 'form-control col-span-6', 'placeholder' => 'Ingrese el Nombre de la Encuesta', 'required']) !!}

            @error('nombre')
            <span class="text-danger text-sm">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group grid grid-cols-12 py-1 mx-auto">
            {!! Form::label('descripcion', 'Descripción', ['class' => 'label col-span-4']) !!}
            {!! Form::textarea('descripcion', null, ['class' => 'form-control col-span-6', 'rows' => '5']) !!}

            @error('descripcion')
            <span class="text-danger text-sm">{{$message}}</span>
            @enderror
        </div>
        

        <div class="text-right grid grid-cols-12">
            {!! Form::submit('Editar Encuesta', ['class' => 'float-right rounded bg-blue-600 text-gray-100 text-sm py-1 px-5 mt-1 col-span-3 col-start-5']) !!}
        </div>

        {!! Form::close() !!}
    </div>
</x-app-layout>
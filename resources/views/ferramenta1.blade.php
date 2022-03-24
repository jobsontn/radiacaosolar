@extends('master')

@section('content')
    <form action="{{ route('ferramenta1_action') }}" method='GET'>
        <div class="relative w-full mb-3 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="latitude">Latitude</label>
            <input type="text" name="latitude" value="{{old('latitude')}}" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Latitude" required>
            @error('latitude')
                <span class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="relative w-full mb-3 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="longitude">Longitude</label>
            <input type="text" name="longitude" value="{{old('longitude')}}" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Longitude" required>
            @error('longitude')
                <span class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="relative w-full mb-3 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="inclinacao">Inclinação</label>
            <input type="text" name="inclinacao" value="{{old('inclinacao')}}" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Inclinação" required>
            <!-- <input type="range" min="0" max="90" value="0" name="inclinacao" id="inclinacao" value="{{old('inclinacao')}}" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Inclinação" required> -->
             @error('inclinacao')
                <span class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="relative w-full mb-3 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="orientacao">Orientação</label>
            <input type="text" name="orientacao" value="{{old('orientacao')}}" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Orientação" required>
            <!-- <input type="range" min="0" max="360" value="0" name="orientacao" value="{{old('orientacao')}}" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Orientação" required> -->
             @error('orientacao')
                <span class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="text-center mt-6">
            <button class="bg-blue-800 text-white active:bg-blue-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="submit">
            Calcular
            </button>
        </div>
    </form>
@endsection

@section('js')

@endsection
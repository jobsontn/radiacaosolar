@extends('master')

@section('content')
    <form action="">
        <div class="relative w-full mb-3 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="latitude">Latitude</label><input type="text" name="latitude" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Latitude">
        </div>
        <div class="relative w-full mb-3 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="longitude">Longitude</label><input type="text" name="longitude" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Longitude">
        </div>
        <div class="relative w-full mb-3 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="inclinacao">Inclinação</label><input type="text" name="inclinacao" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Inclinação">
        </div>
        <div class="relative w-full mb-3 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="orientacao">Orientação</label><input type="text" name="orientacao" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Orientação">
        </div>
        {{-- <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="email">Email</label><input type="email" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Email">
        </div>
        <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="message">Message</label><textarea rows="4" cols="80" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" placeholder="Type a message..."></textarea>
        </div> --}}
        <div class="text-center mt-6">
            <button class="bg-blue-800 text-white active:bg-blue-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
            Calcular
            </button>
        </div>
    </form>
@endsection
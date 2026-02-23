<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Adat Módosítása</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8 flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Település módosítása</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('zipcodes.update', $zipCode->id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Irányítószám</label>
                <input type="text" name="zip_code" value="{{ $zipCode->zip_code }}" class="border border-gray-300 p-2 rounded w-full focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Település</label>
                <input type="text" name="city" value="{{ $zipCode->city }}" class="border border-gray-300 p-2 rounded w-full focus:outline-none focus:border-blue-500">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Mentés</button>
                <a href="{{ route('zipcodes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded">Mégse</a>
            </div>
        </form>
    </div>
</body>
</html>
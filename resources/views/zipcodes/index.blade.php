<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Irányítószámok</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800">Irányítószám Kereső</h1>
            <div>
                @auth
                    <span class="mr-4 text-gray-600">Szia, <b>{{ auth()->user()->name }}</b>!</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:underline">Kijelentkezés</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline mr-4">Bejelentkezés</a>
                    <a href="{{ route('register') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Regisztráció</a>
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('zipcodes.index') }}" class="mb-6 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Keresés településre vagy irányítószámra..." class="border border-gray-300 p-2 rounded w-full focus:outline-none focus:border-blue-500">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Keresés</button>
            <a href="{{ route('zipcodes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Törlés</a>
        </form>

        @auth
        <div class="mb-6 bg-gray-50 p-4 rounded border flex flex-wrap gap-4 items-center">
            <a href="{{ route('zipcodes.csv') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow">.CSV Export</a>
            <a href="{{ route('zipcodes.pdf') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">.PDF Export</a>
            
            <form method="POST" action="{{ route('zipcodes.email') }}" class="flex gap-2 ml-auto">
                @csrf
                <input type="email" name="email" placeholder="E-mail cím..." required class="border border-gray-300 p-2 rounded">
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">Küldés E-mailben</button>
            </form>
        </div>
        @endauth

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border p-3 text-left">ID</th>
                        <th class="border p-3 text-left">Irányítószám</th>
                        <th class="border p-3 text-left">Település</th>
                        @auth <th class="border p-3 text-center">Művelet</th> @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse($zipCodes as $zip)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-3">{{ $zip->id }}</td>
                        <td class="border p-3">{{ $zip->zip_code }}</td>
                        <td class="border p-3 font-semibold">{{ $zip->city }}</td>
                        @auth
                        <td class="border p-3 text-center">
                            <a href="{{ route('zipcodes.edit', $zip->id) }}" class="text-blue-500 hover:underline">Módosítás</a>
                        </td>
                        @endauth
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="border p-4 text-center text-gray-500">Nincs találat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $zipCodes->links() }}
        </div>
        
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pengaturan->nama_website ?? 'SIPERPUS' }} - Login</title>
    
    @if($pengaturan && $pengaturan->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $pengaturan->favicon) }}">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset($pengaturan->logo) }}" alt="Logo" class="h-16 w-auto mx-auto mb-4">
        
            <h1 class="text-2xl font-bold text-gray-800">
                {{ $pengaturan->nama_website ?? 'SIPERPUS' }}
            </h1>
           
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Masukkan email Anda" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Masukkan password Anda" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                Masuk
            </button>
        </form>
        
        <div class="text-center mt-6 pt-4 border-t border-gray-200">
            <p class="text-gray-500 text-sm uppercase mb-3">
            &copy; {{ $pengaturan->deskripsi_website ?? 'SIPERPUS' }} {{ date('Y') }}
                <!-- &copy; {{ date('Y') }} {{ $pengaturan->nama_website ?? 'SIPERPUS' }} -->
            </p>
            <p class="text-gray-500 text-xs ">
                by <a href="https://www.instagram.com/rinomakin" class="text-blue-500">@rinomakin</a>
            </p>
        </div>
    </div>
</body>
</html> 
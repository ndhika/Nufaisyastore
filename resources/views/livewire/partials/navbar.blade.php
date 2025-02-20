<div id="navbar" class="navbar fixed top-4 right-0 left-0 z-50 mx-auto rounded-full p-3 md:w-3/4 lg:w-90% transition-all duration-700 ease-in-out">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden text-white">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h8m-8 6h16" />
        </svg>
      </div>
      <ul
        tabindex="0"
        class="menu menu-sm dropdown-content bg-black rounded-box z-[1] mt-3 w-40 p-2 shadow">
        <li><a href="{{ route('home') }}" class="text-white hover:rounded-full {{ request()->routeIs('home') ? 'bg-black rounded-full text-yellow-500 font-bold' : '' }}">Home</a></li>
        <li><a href="{{ route('product') }}" class="text-white hover:rounded-full {{ request()->routeIs('product') ? 'bg-black rounded-full text-yellow-500 font-bold' : '' }}">Product</a></li>
        <li><a href="{{ route('about') }}" class="text-white hover:rounded-full {{ request()->routeIs('about') ? 'bg-black rounded-full text-yellow-500 font-bold' : '' }}">About</a></li>
      </ul>
    </div>
    <a class="btn btn-ghost text-xl text-white">Nufaisyastore</a> 
  </div>
  
  <div class="navbar-center hidden lg:flex">
    <ul class="menu menu-horizontal px-1">
      <li><a href="{{ route('home') }}" class="text-white hover:rounded-full {{ request()->routeIs('home') ? 'bg-black rounded-full text-yellow-500 font-bold' : '' }}">Home</a></li>
      <li><a href="{{ route('product') }}" class="text-white hover:rounded-full {{ request()->routeIs('product') ? 'bg-black rounded-full text-yellow-500 font-bold' : '' }}">Product</a></li>
      <li><a href="{{ route('about') }}" class="text-white hover:rounded-full {{ request()->routeIs('about') ? 'bg-black rounded-full text-yellow-500 font-bold' : '' }}">About</a></li>
    </ul>
  </div>

  <div class="navbar-end px-5">
    <a href="{{ route('cart') }}" class="btn btn-ghost btn-sm flex items-center gap-2 text-white">
      <i class="fas fa-shopping-cart text-lg relative">
        @if($cartCount > 0)
          <span class="absolute -top-4 -right-5 bg-red-600 text-white text-xs rounded-full px-2 py-1">
            {{ $cartCount }}
          </span>
        @endif
      </i>
    </a>
    @auth
      <div class="relative">
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-sm flex items-center gap-2 text-white">
                <i class="fas fa-user-circle text-lg"></i> 
                <span class="font-medium">{{ Auth::user()->name }}</span>
            </label>
            <ul tabindex="0" class="menu dropdown-content bg-gray-900 rounded-lg z-10 mt-2 w-48 p-2 shadow-lg border border-gray-700">
                <li>
                    <a href="{{ route('order.history') }}" class="flex items-center gap-2 px-3 py-2 rounded-md text-white hover:bg-gray-800 transition">
                        <i class="fas fa-history text-gray-400"></i> 
                        <span>History Pemesanan</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-3 py-2 rounded-md text-red-400 hover:bg-gray-800 w-full transition">
                            <i class="fas fa-sign-out-alt"></i> 
                            <span>Logout</span>
                        </button>
                    </form>
                </li> 
            </ul>
        </div>
    </div>  
    @else     
      <a href="{{ route('login') }}" class="btn btn-sm btn-primary text-white rounded-full">Login</a>
    @endauth
  </div>
  <script>
    window.addEventListener('scroll', function() {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.remove('bg-transparent');
        navbar.classList.add('bg-gradient-to-r', 'from-black', 'via-gray-900', 'to-black');
      } else {
        navbar.classList.remove('bg-gradient-to-r', 'from-black', 'via-gray-900', 'to-black');
        navbar.classList.add('bg-transparent');
      }
    });
  </script>
</div>

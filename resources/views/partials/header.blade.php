<style>
    /* Default style untuk icon */
</style>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container px-lg-5">
        <div class="col-lg-9">
            <a class="navbar-brand nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                <i class="fi fi-sr-house-blank"></i>
                <span class="nav-text">Home</span>
            </a>

            @auth
            <a class="navbar-brand nav-link {{ Request::is('newpost') ? 'active' : '' }}" href="{{ url('newpost') }}">
                <i class="fi fi-sr-file-edit"></i>
                <span class="nav-text">New Post</span>
            </a>
            @endauth

            <a class="navbar-brand nav-link {{ Request::is('allposts') ? 'active' : '' }}"
                href="{{ url('allposts?slug=allposts') }}">
                <i class="fi fi-sr-poll-h"></i>
                <span class="nav-text">All Post</span>
            </a>
            @auth
            <a class="navbar-brand nav-link {{ Request::is('ask') ? 'active' : '' }}" href="{{ url('ask') }}">
                <i class="fi fi-sr-search-alt"></i>
                <span class="nav-text">Quick Search</span>
            </a>
            @endauth
        </div>
        @auth
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none" id="avatarDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                @php
                $user = Auth::user();
                $initials = collect(explode(' ', $user->name))
                ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                ->join('');

                // Warna background berdasarkan hash nama
                $hash = md5($user->name);
                $bgColor = '#' . substr($hash, 0, 6);

                // Warna teks berdasarkan luminance
                $r = hexdec(substr($bgColor, 1, 2));
                $g = hexdec(substr($bgColor, 3, 2));
                $b = hexdec(substr($bgColor, 5, 2));
                $textColor = (($r * 0.299 + $g * 0.587 + $b * 0.114) > 186) ? '#000000' : '#FFFFFF';
                @endphp

                @if ($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                    title="{{ $user->name }}" class="avatar img-fluid rounded-circle avatar-hover"
                    style="width: 40px; height: 40px; object-fit: cover;">
                @else
                <div class="avatar d-flex align-items-center justify-content-center rounded-circle avatar-hover"
                    title="{{ $user->name }}"
                    style="width: 40px; height: 40px; font-weight: bold; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                    {{ $initials }}
                </div>
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="avatarDropdown">
                @role('super_admin')
                <li>
                    <a href="{{ url('/admin/dashboard') }}" class="dropdown-item text-dark">
                        <i class="bi bi-speedometer2"></i>
                        MyPanel
                    </a>
                </li>
                @else
                <li>
                    <a href="{{ url('/admin/reports') }}" class="dropdown-item text-dark">
                        <i class="bi bi-speedometer2"></i>
                        MyPanel
                    </a>
                </li>
                @endrole
                <li>
                    <form id="logout-form" method="POST" action="{{ url('/admin/logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger" id="logout-btn">
                            <i class="bi bi-box-arrow-right"></i>
                            Log out
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="dropdown-menu dropdown-menu-end">
            <form id="logout-form" method="POST" action="{{ url('/admin/logout') }}">
                @csrf
                <button type="button" class="dropdown-item" id="logout-btn">
                    <svg class="icon-xl-heavy" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        height="24" width="24">
                        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor"
                            d="M16 17L21 12L16 7"></path>
                        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor"
                            d="M21 12H9">
                        </path>
                        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor"
                            d="M12 5H7C5.89543 5 5 5.89543 5 7V17C5 18.1046 5.89543 19 7 19H12"></path>
                    </svg>
                    Log out
                </button>
            </form>
        </div>
        @endauth

        @guest
        <div class="mt-lg-0 d-flex align-items-center">
            <a href="{{ url('admin/login') }}" class="btn btn-primary mx-2">Sign In</a>
        </div>
        @endguest
    </div>
</nav>
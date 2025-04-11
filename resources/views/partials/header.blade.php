<nav id="myTab" class="navbar navbar-expand nav nav-tabs px-4 py-2 rounded shadow" role="tablist">
    <div class="navTop">
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                type="button" role="tab" aria-controls="home" aria-selected="true">
                &#xF63E;
                Search Post</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="profile-tab" data-bs-toggle="modal"
                data-bs-target="#reportModal" type="button" role="tab" aria-controls="profile" aria-selected="false">
                @auth
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-pencil-fill mx-2" viewBox="0 0 16 16">
                    <path
                        d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                </svg>
                @endauth
                Write Post</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center" id="contact-tab" data-bs-toggle="tab"
                data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-file-earmark-text-fill mx-2" viewBox="0 0 16 16">
                    <path
                        d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z" />
                </svg>
                All Post</button>
        </li>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li><span id="theme-btn" class="material-symbols-rounded mx-3" data-animation="true"
                        data-toggle="tooltip" data-placement="top" title="Ganti Tema">
                        light_mode
                    </span>
                </li>
                <li class="nav-item dropdown">
                    @auth
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none" id="avatarDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                            $user = Auth::user();
                            $initials = collect(explode(' ', $user->name))->map(fn($word) => strtoupper(substr($word, 0,
                            1)))->join('');
                            @endphp

                            @if ($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                                title="{{ $user->name }}" class="avatar img-fluid rounded-circle"
                                style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                            <div class="avatar d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white"
                                style="width: 40px; height: 40px; font-weight: bold;">
                                {{ $initials }}
                            </div>
                            @endif
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="avatarDropdown">
                            <li class="">
                                <form id="logout-form" method="POST" action="{{ url('/admin/logout') }}">
                                    @csrf
                                    <button type="button" class="dropdown-item text-danger" id="logout-btn">
                                        <svg class="icon-xl-heavy" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" height="24" width="24">
                                            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                                stroke="currentColor" d="M16 17L21 12L16 7"></path>
                                            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                                stroke="currentColor" d="M21 12H9">
                                            </path>
                                            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                                stroke="currentColor"
                                                d="M12 5H7C5.89543 5 5 5.89543 5 7V17C5 18.1046 5.89543 19 7 19H12">
                                            </path>
                                        </svg>
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
                                <svg class="icon-xl-heavy" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" height="24" width="24">
                                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                        stroke="currentColor" d="M16 17L21 12L16 7"></path>
                                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                        stroke="currentColor" d="M21 12H9">
                                    </path>
                                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2"
                                        stroke="currentColor"
                                        d="M12 5H7C5.89543 5 5 5.89543 5 7V17C5 18.1046 5.89543 19 7 19H12"></path>
                                </svg>
                                Log out
                            </button>
                        </form>
                    </div>
                    @endauth

                    @guest
                    <a href="{{ url('admin/login') }}" class="btn btn-secondary text-light rounded-pill">
                        Login
                    </a>
                    <a href="{{ url('admin/register') }}" class="btn btn-primary text-light rounded-pill">
                        Register
                    </a>
                    @endguest
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="text-center my-4">
    <div class="relative">
        <hr class="border-gray-300">
        <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-white px-2 text-sm text-gray-500">Or log in
            via</span>
    </div>
</div>

<div class="flex justify-center flex-cols-2 gap-3">
    <a href="{{ route('google.login') }}"
        class="flex items-center justify-center border rounded-md px-4 py-2 text-gray-700 hover:bg-gray-100 transition font-medium w-1/2">
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="20" viewBox="0 0 48 48">
            <path fill="#FFC107"
                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
            </path>
            <path fill="#FF3D00"
                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
            </path>
            <path fill="#4CAF50"
                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
            </path>
            <path fill="#1976D2"
                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
            </path>
        </svg>
        Google
    </a>

    <a href="{{ route('login.microsoft') }}"
        class="flex items-center justify-center border rounded-md px-4 py-2 text-gray-700 hover:bg-gray-100 transition font-medium w-1/2">
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="20" viewBox="0 0 48 48">
            <path fill="#ff5722" d="M6 6H22V22H6z" transform="rotate(-180 14 14)"></path>
            <path fill="#4caf50" d="M26 6H42V22H26z" transform="rotate(-180 34 14)"></path>
            <path fill="#ffc107" d="M26 26H42V42H26z" transform="rotate(-180 34 34)"></path>
            <path fill="#03a9f4" d="M6 26H22V42H6z" transform="rotate(-180 14 34)"></path>
        </svg>
        Microsoft
    </a>
</div>

<div class="text-center my-4">
    <div class="relative">
        <a href="{{ url('/') }}" class="text-blue-600/100 flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="15" height="15" viewBox="0 0 48 48">
                <path
                    d="M39.5,43h-9c-1.381,0-2.5-1.119-2.5-2.5v-9c0-1.105-0.895-2-2-2h-4c-1.105,0-2,0.895-2,2v9c0,1.381-1.119,2.5-2.5,2.5h-9	C7.119,43,6,41.881,6,40.5V21.413c0-2.299,1.054-4.471,2.859-5.893L23.071,4.321c0.545-0.428,1.313-0.428,1.857,0L39.142,15.52	C40.947,16.942,42,19.113,42,21.411V40.5C42,41.881,40.881,43,39.5,43z">
                </path>
            </svg>
            Back to Home
        </a>
    </div>
</div>
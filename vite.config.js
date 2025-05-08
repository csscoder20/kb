import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/pages/allposts.css',
                'resources/js/app.js',
                'resources/js/pages/allposts.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});


// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: [
//                 'resources/css/app.css',
//                 'resources/css/pages/allposts.css',
//                 'resources/js/app.js',
//                 'resources/js/pages/allposts.js'
//             ],
//             refresh: true,
//         }),
//     ],
// });

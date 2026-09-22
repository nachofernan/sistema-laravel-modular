@php
    $mensajeEspecifico = null;

    if (isset($exception) && trim((string) $exception->getMessage()) !== '') {
        $mensajeEspecifico = $exception->getMessage();
    } elseif (config('alerta.activo') && config('alerta.mensaje')) {
        $mensajeEspecifico = config('alerta.mensaje');
    }

    $retryAfter = isset($exception) ? ($exception->getHeaders()['Retry-After'] ?? null) : null;
    $retryMinutos = $retryAfter ? (int) ceil($retryAfter / 60) : null;
@endphp
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mantenimiento — {{ config('app.name', 'BAESA') }}</title>

        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}
            body{margin:0}
            a{background-color:transparent}
            code{font-family:monospace,monospace;font-size:1em}
            [hidden]{display:none}
            html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}
            *,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}
            a{color:inherit;text-decoration:inherit}
            code{font-family:Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace}
            svg,video{display:block;vertical-align:middle}
            video{max-width:100%;height:auto}
            .bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}
            .bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}
            .border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}
            .border-gray-400{--border-opacity:1;border-color:#cbd5e0;border-color:rgba(203,213,224,var(--border-opacity))}
            .border-t{border-top-width:1px}
            .border-r{border-right-width:1px}
            .flex{display:flex}
            .grid{display:grid}
            .hidden{display:none}
            .items-center{align-items:center}
            .justify-center{justify-content:center}
            .font-semibold{font-weight:700}
            .h-5{height:1.25rem}
            .h-8{height:2rem}
            .h-16{height:4rem}
            .text-sm{font-size:.875rem}
            .text-lg{font-size:1.125rem}
            .text-xl{font-size:2.5rem}
            .leading-7{line-height:1.75rem}
            .mx-auto{margin-left:auto;margin-right:auto}
            .ml-1{margin-left:.25rem}
            .mt-2{margin-top:.5rem}
            .mr-2{margin-right:.5rem}
            .ml-2{margin-left:.5rem}
            .mt-4{margin-top:1rem}
            .ml-4{margin-left:1rem}
            .mt-8{margin-top:2rem}
            .ml-12{margin-left:3rem}
            .-mt-px{margin-top:-1px}
            .max-w-xl{max-width:36rem}
            .max-w-6xl{max-width:72rem}
            .min-h-screen{min-height:100vh}
            .overflow-hidden{overflow:hidden}
            .p-6{padding:1.5rem}
            .py-4{padding-top:1rem;padding-bottom:1rem}
            .px-4{padding-left:1rem;padding-right:1rem}
            .px-6{padding-left:1.5rem;padding-right:1.5rem}
            .pt-8{padding-top:2rem}
            .fixed{position:fixed}
            .relative{position:relative}
            .top-0{top:0}
            .right-0{right:0}
            .shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}
            .text-center{text-align:center}
            .v-align-text-bottom{vertical-align:text-bottom}
            .text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}
            .text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}
            .text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}
            .text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}
            .text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}
            .text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}
            .text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}
            .uppercase{text-transform:uppercase}
            .underline{text-decoration:underline}
            .underline-h:hover{text-decoration:underline}
            .antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
            .tracking-wider{letter-spacing:.05em}
            .w-5{width:1.25rem}
            .w-8{width:2rem}
            .w-auto{width:auto}
            .grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}
            @-webkit-keyframes spin{0%{transform:rotate(0deg)}
            to{transform:rotate(1turn)}
            }
            @keyframes spin{0%{transform:rotate(0deg)}
            to{transform:rotate(1turn)}
            }
            @-webkit-keyframes ping{0%{transform:scale(1);opacity:1}
            75%,to{transform:scale(2);opacity:0}
            }
            @keyframes ping{0%{transform:scale(1);opacity:1}
            75%,to{transform:scale(2);opacity:0}
            }
            @-webkit-keyframes pulse{0%,to{opacity:1}
            50%{opacity:.5}
            }
            @keyframes pulse{0%,to{opacity:1}
            50%{opacity:.5}
            }
            @-webkit-keyframes bounce{0%,to{transform:translateY(-25%);-webkit-animation-timing-function:cubic-bezier(.8,0,1,1);animation-timing-function:cubic-bezier(.8,0,1,1)}
            50%{transform:translateY(0);-webkit-animation-timing-function:cubic-bezier(0,0,.2,1);animation-timing-function:cubic-bezier(0,0,.2,1)}
            }
            @keyframes bounce{0%,to{transform:translateY(-25%);-webkit-animation-timing-function:cubic-bezier(.8,0,1,1);animation-timing-function:cubic-bezier(.8,0,1,1)}
            50%{transform:translateY(0);-webkit-animation-timing-function:cubic-bezier(0,0,.2,1);animation-timing-function:cubic-bezier(0,0,.2,1)}
            }
            @media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}
            .sm\:block{display:block}
            .sm\:items-center{align-items:center}
            .sm\:justify-start{justify-content:flex-start}
            .sm\:justify-between{justify-content:space-between}
            .sm\:h-20{height:5rem}
            .sm\:ml-0{margin-left:0}
            .sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}
            .sm\:pt-0{padding-top:0}
            .sm\:text-left{text-align:left}
            .sm\:text-right{text-align:right}
            }
            @media (min-width:768px){.md\:border-t-0{border-top-width:0}
            .md\:border-l{border-left-width:1px}
            .md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}
            }
            @media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}
            }
            @media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}
            .dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}
            .dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}
            .dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}
            .dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}
            }

        </style>

        <style>
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8" style="width: 100%;">
                <div class="flex items-center pt-8 sm:justify-start sm:pt-0" style="justify-content: center;">
                    <div class="px-4 text-xl text-gray-500 border-r border-gray-400 tracking-wider font-semibold">
                        <img src="https://buenosairesenergia.com.ar/img/logo_50g.png" class="v-align-text-bottom">
                    </div>

                    <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                        En mantenimiento
                    </div>
                </div>

                <div class="mt-8" style="text-align: center; padding: 0 1rem;">
                    <svg class="h-8 w-8 mx-auto" style="color: #dc2626;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                    </svg>

                    <p class="mt-4 text-gray-700" style="font-size: 1.125rem; line-height: 1.75rem; font-weight: 600;">
                        Estamos realizando tareas de mantenimiento programado.
                    </p>
                    <p class="mt-2 text-gray-600" style="font-size: 1rem;">
                        La plataforma vuelve a estar disponible en breve. Disculpá las molestias.
                    </p>

                    @if ($mensajeEspecifico)
                        <div class="mt-4" style="display: inline-block; max-width: 100%; background: #fef2f2; border: 1px solid #fecaca; border-radius: .5rem; padding: .75rem 1rem;">
                            <p class="text-gray-700" style="margin: 0; font-size: .95rem;">{{ $mensajeEspecifico }}</p>
                        </div>
                    @endif

                    @if ($retryMinutos)
                        <p class="mt-4 text-gray-500 text-sm">
                            Podés volver a intentar en aproximadamente {{ $retryMinutos }} {{ Str::plural('minuto', $retryMinutos) }}.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'FaturaJá')</title>

    <!-- Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Rolagem suave nativa */
        html {
            scroll-behavior: smooth;
        }

        /* Gradiente animado do footer */
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animated-gradient-section {
            background: linear-gradient(-30deg, #0F2D44, #1A476F, #0A1F30, #0F2D44);
            background-size: 400% 400%;
            animation: gradientShift 25s ease infinite;
        }

        /* Entrada suave em elementos */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stagger-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .stagger-1 {
            animation-delay: 0.2s;
        }

        .stagger-2 {
            animation-delay: 0.4s;
        }

        .stagger-3 {
            animation-delay: 0.6s;
        }

        .stagger-4 {
            animation-delay: 0.8s;
        }

        .stagger-5 {
            animation-delay: 1.0s;
        }

        .stagger-6 {
            animation-delay: 1.2s;
        }

        /* Destaque leve da seção alvo ao chegar */
        .section-highlight {
            box-shadow: 0 0 0 3px rgba(249, 148, 31, 0.35) inset;
            transition: box-shadow 600ms ease;
        }
    </style>
</head>

<body class="font-sans bg-[#F2F2F2] text-[#123859]">

    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#hero" class="flex items-center space-x-2 cursor-pointer stagger-fadeInUp stagger-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#F9941F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h1 class="text-2xl font-extrabold">Fatura <span class="text-[#F9941F]">Já</span></h1>
            </a>

            <!-- Menu desktop -->
            <nav class="hidden lg:flex items-center space-x-4">
                <a href="#funcionalidades" class="nav-link text-sm font-medium hover:text-[#F9941F] transition stagger-fadeInUp stagger-2">Funcionalidades</a>
                <a href="#processo" class="nav-link text-sm font-medium hover:text-[#F9941F] transition stagger-fadeInUp stagger-3">Processo</a>
                <a href="#planos" class="nav-link text-sm font-medium hover:text-[#F9941F] transition stagger-fadeInUp stagger-4">Planos</a>
                <a href="#faq" class="nav-link text-sm font-medium hover:text-[#F9941F] transition stagger-fadeInUp stagger-5">FAQ</a>
                <a href="#contacto" class="nav-link text-sm font-medium hover:text-[#F9941F] transition stagger-fadeInUp stagger-6">Contacto</a>
                <a href="/login" class="px-4 py-2 rounded-full font-semibold bg-[#123859] text-white hover:brightness-90 transition stagger-fadeInUp stagger-6">Login</a>
            </nav>

            <!-- Mobile toggle -->
            <div class="lg:hidden flex items-center stagger-fadeInUp stagger-2">
                <button @click="open = !open" class="p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#123859]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#123859]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="open" x-transition class="lg:hidden px-2 pt-2 pb-3 space-y-1">
            <a href="#funcionalidades" @click="open=false" class="nav-link block px-3 py-2 rounded-md text-base font-medium stagger-fadeInUp stagger-1">Funcionalidades</a>
            <a href="#processo" @click="open=false" class="nav-link block px-3 py-2 rounded-md text-base font-medium stagger-fadeInUp stagger-2">Processo</a>
            <a href="#planos" @click="open=false" class="nav-link block px-3 py-2 rounded-md text-base font-medium stagger-fadeInUp stagger-3">Planos</a>
            <a href="#faq" @click="open=false" class="nav-link block px-3 py-2 rounded-md text-base font-medium stagger-fadeInUp stagger-4">FAQ</a>
            <a href="#contacto" @click="open=false" class="nav-link block px-3 py-2 rounded-md text-base font-medium stagger-fadeInUp stagger-5">Contacto</a>
            <a href="/login" class="block w-full text-center px-3 py-2 mt-2 rounded-full font-semibold bg-[#123859] text-white hover:brightness-90 transition stagger-fadeInUp stagger-6">Começar Grátis</a>
        </div>
    </header>

    <!-- Main -->
    <main class="mt-16 stagger-fadeInUp stagger-2">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="animated-gradient-section text-white py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 text-sm">
            <!-- FaturaJá -->
            <div>
                <h3 class="text-lg font-bold mb-4">FaturaJá</h3>
                <p class="mb-2">A sua solução definitiva para gestão e faturação simplificada. Rápido, seguro e compatível com as normas fiscais.</p>
                <p class="mb-1">Luanda, Angola</p>
                <p class="mb-1">geral@sdoca.it.ao</p>
                <p class="mb-1">+244 923678529</p>
                <p>+244 927800505</p>
            </div>

            <!-- Navegação -->
            <div>
                <h3 class="text-lg font-bold mb-4">Navegação</h3>
                <ul class="space-y-2">
                    <li><a href="#funcionalidades" class="nav-link hover:text-[#F9941F] transition">Funcionalidades</a></li>
                    <li><a href="#processo" class="nav-link hover:text-[#F9941F] transition">Processo</a></li>
                    <li><a href="#planos" class="nav-link hover:text-[#F9941F] transition">Planos</a></li>
                    <li><a href="#faq" class="nav-link hover:text-[#F9941F] transition">FAQ</a></li>
                    <li><a href="#contacto" class="nav-link hover:text-[#F9941F] transition">Contacto</a></li>
                </ul>
            </div>

            <!-- Apoio e Legal -->
            <div>
                <h3 class="text-lg font-bold mb-4">Apoio e Legal</h3>
                <ul class="space-y-2">
                    <li><a href="#faq" class="nav-link hover:text-[#F9941F] transition">FAQ</a></li>
                    <li><a href="#" class="nav-link hover:text-[#F9941F] transition">Suporte Técnico</a></li>
                    <li><a href="#" class="nav-link hover:text-[#F9941F] transition">Termos de Serviço</a></li>
                    <li><a href="#" class="nav-link hover:text-[#F9941F] transition">Política de Privacidade</a></li>
                    <li><a href="#" class="nav-link hover:text-[#F9941F] transition">Livro de Reclamações</a></li>
                </ul>
            </div>

            <!-- Siga-nos -->
            <div>
                <h3 class="text-lg font-bold mb-4">Siga-nos</h3>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-[#F9941F] transition"><i data-lucide="facebook" class="w-6 h-6"></i></a>
                    <a href="#" class="hover:text-[#F9941F] transition"><i data-lucide="instagram" class="w-6 h-6"></i></a>
                    <a href="#" class="hover:text-[#F9941F] transition"><i data-lucide="linkedin" class="w-6 h-6"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-8 border-[#F9941F]">
        <div class="mt-12 text-center text-xs text-white/80">
            &copy; 2025 FaturaJá. Todos os direitos reservados. | Desenvolvido em Angola por SDOCA.
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Inicializa ícones
        lucide.createIcons();

        // Captura cliques dos links de navegação para animar a âncora alvo
        const navLinks = document.querySelectorAll('a.nav-link[href^="#"]');
        const header = document.querySelector('header');

        function animateTargetSection(target) {
            if (!target) return;
            // Aplica um highlight leve ao chegar
            target.classList.add('section-highlight');
            setTimeout(() => target.classList.remove('section-highlight'), 800);
        }

        function getOffsetTopWithHeader(el) {
            const headerHeight = header?.offsetHeight || 0;
            const rectTop = el.getBoundingClientRect().top + window.pageYOffset;
            // Ajuste para sticky header
            return rectTop - (headerHeight + 8);
        }

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                const id = href && href.startsWith('#') ? href.slice(1) : null;
                const target = id ? document.getElementById(id) : null;
                if (!target) return; // deixa o comportamento padrão se não achar

                e.preventDefault();

                // Rolagem suave manual (para maior controle do offset)
                window.scrollTo({
                    top: getOffsetTopWithHeader(target),
                    behavior: 'smooth'
                });

                // Anima brevemente a seção alvo
                animateTargetSection(target);
            });
        });

        // Marca link ativo com base na seção visível (IntersectionObserver)
        const sections = ['funcionalidades', 'processo', 'planos', 'faq', 'contacto']
            .map(id => document.getElementById(id))
            .filter(Boolean);

        const linkMap = {};
        navLinks.forEach(l => {
            const id = l.getAttribute('href').replace('#', '');
            linkMap[id] = l;
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const id = entry.target.id;
                if (entry.isIntersecting && linkMap[id]) {
                    // Remove active dos outros
                    Object.values(linkMap).forEach(a => a.classList.remove('text-[#F9941F]'));
                    // Destaca o atual com uma cor sutil
                    linkMap[id].classList.add('text-[#F9941F]');
                }
            });
        }, {
            root: null,
            rootMargin: '0px 0px -60% 0px',
            threshold: 0.25
        });

        sections.forEach(sec => observer.observe(sec));
    </script>
</body>

</html>
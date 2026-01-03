@extends('layouts.app')

@section('title', 'FaturaJá - Sistema de Faturação')

@section('content')

<style>
    @keyframes fadeInSoft {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeInSoft {
        animation: fadeInSoft 0.7s ease-out forwards;
        opacity: 0;
    }
</style>

<!-- Hero Section -->
<section id="hero" class="bg-[#F2F2F2] flex items-center">
    <div class="max-w-7xl mx-auto px-6 lg:px-8  min-h-screen flex flex-col-reverse lg:flex-row items-center gap-10">
        <div class="lg:w-1/2 space-y-6 animate-fadeInLeft">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-[#123859] leading-tight">
                Fature rápido e seguro com <span class="text-[#F9941F]">FaturaJá</span>
            </h1>
            <p class="text-gray-700 text-lg">
                O sistema de faturação moderno que simplifica a gestão do seu negócio, com relatórios claros e emissão de faturas em segundos.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#planos" class="px-6 py-3 rounded-full bg-[#123859] text-white font-semibold hover:scale-105 transition shadow-lg transform">Começar Grátis</a>
                <a href="#funcionalidades" class="px-6 py-3 rounded-full border-2 border-[#123859] text-[#123859] font-semibold hover:bg-[#123859] hover:text-white transition shadow-lg transform">Saber Mais</a>
            </div>
        </div>
        <div class="lg:w-1/2 flex justify-center animate-fadeInRight">
            <img src="{{ asset('images/hero-fatura.png') }}" alt="FaturaJá App" class="w-full max-w-md shadow-xl rounded-lg">
        </div>

    </div>
</section>

<section id="video" class="animated-gradient-section py-24 text-white">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-6 animate-fadeInUp">
            Veja o FaturaJá em Ação
        </h2>
        <p class="text-white/80 mb-8 animate-fadeInUp">
            Uma breve apresentação do sistema e da nossa missão para simplificar a sua faturação.
        </p>
        <div class="relative w-70 h-10 pb-[56.25%] rounded-xl shadow-2xl overflow-hidden animate-fadeInUp">
            <iframe class="absolute top-0 left-0 w-full h-full rounded-xl"
                src=""
                title="Vídeo Institucional FaturaJá"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>


<!-- Funcionalidades -->
<section id="funcionalidades" class="py-24 min-h-screen bg-[#F2F2F2]">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[#123859] mb-12 animate-fadeInUp">
            Ferramentas Essenciais para o seu Negócio
        </h2>
        <p class="text-center text-gray-700 mb-12 animate-fadeInUp">
            Tudo o que precisa para começar a faturar de forma simples, segura e eficiente.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach([
            ['icon'=>'file-text','title'=>'Emissão de Faturas','desc'=>'Crie e envie faturas rapidamente, com templates claros e profissionais.'],
            ['icon'=>'users','title'=>'Gestão de Clientes','desc'=>'Cadastre e administre os seus clientes, consulte o histórico de faturas e contactos de forma organizada e segura.'],
            ['icon'=>'package','title'=>'Controle de Produtos','desc'=>'Gerencie produtos e serviços, preços e stock em tempo real, directamente ligados às faturas.'],
            ['icon'=>'bar-chart-2','title'=>'Relatórios Financeiros','desc'=>'Visualize relatórios detalhados sobre vendas, faturamento e desempenho financeiro da sua empresa.'],
            ['icon'=>'send','title'=>'Envio Automático','desc'=>'Envie faturas automaticamente por email aos seus clientes, economizando tempo e esforço.'],
            ['icon'=>'credit-card','title'=>'Integração com Pagamentos','desc'=>'Aceite pagamentos por transferência bancária, cartão de débito/crédito ou Multicaixa, e acompanhe o estado das faturas (pendente, pago, cancelado).']
            ] as $feature)
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 animate-fadeInUp">
                <div class="text-[#F9941F] mb-6 text-center transition-all duration-500 hover:text-[#123859] hover:scale-125">
                    <i data-lucide="{{ $feature['icon'] }}" class="w-12 h-12 mx-auto"></i>
                </div>
                <h3 class="font-bold text-xl mb-2 text-center">{{ $feature['title'] }}</h3>
                <p class="text-gray-600 text-sm text-center">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Como Funciona -->
<section id="processo" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[#123859] mb-12 animate-fadeInUp">
            Fácil de Usar, Resultados Rápidos
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
            ['number'=>'1','title'=>'Registo Rápido','desc'=>'Comece em minutos! Crie sua conta sem papelada e esteja pronto para faturar hoje mesmo.'],
            ['number'=>'2','title'=>'Personalização','desc'=>'Sua empresa, do seu jeito! Personalize dados fiscais, logótipo e templates de faturas em segundos.'],
            ['number'=>'3','title'=>'Fature Já','desc'=>'Fature em um clique! Crie e envie sua primeira fatura sem complicações.']
            ] as $step)
            <div class="bg-[#F2F2F2] p-6 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 text-center animate-fadeInUp border-t-4 border-[#F9941F]">
                <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center rounded-full bg-[#F9941F] text-white text-xl font-bold">
                    {{ $step['number'] }}
                </div>
                <h3 class="font-bold text-xl mb-2">{{ $step['title'] }}</h3>
                <p class="text-gray-600 text-sm">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Planos -->
<section id="planos" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[#123859] mb-12 animate-fadeInSoft">
            Escolha o Plano Certo para Si
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
            [
            'title'=>'Grátis',
            'price'=>'0 KZ/mês',
            'features'=>[
            'Até 5 faturas/mês',
            '1 utilizador',
            'Suporte comunitário',
            'Armazenamento de 100MB',
            'Design padrão',
            'Sem relatórios'
            ],
            'btn'=>'Experimente Agora',
            'btnColor'=>'bg-[#123859]',
            'btnLink'=>'/register',
            'highlight'=>false
            ],
            [
            'title'=>'Essencial',
            'price'=>'19.000 KZ/mês',
            'features'=>[
            'Faturação ilimitada',
            'Até 3 utilizadores',
            'Suporte prioritário',
            'Armazenamento ilimitado',
            'Gestão de clientes avançada',
            'Relatórios detalhados (trimestrais)'
            ],
            'btn'=>'Assinar',
            'btnColor'=>'bg-[#F9941F]',
            'btnLink'=>'/register',
            'highlight'=>false
            ],
            [
            'title'=>'Essencial',
            'price'=>'19.000 KZ/mês',
            'features'=>[
            'Faturação ilimitada',
            'Até 3 utilizadores',
            'Suporte prioritário',
            'Armazenamento ilimitado',
            'Gestão de clientes avançada',
            'Relatórios detalhados (trimestrais)'
            ],
            'btn'=>'Assinar',
            'btnColor'=>'bg-[#F9941F]',
            'btnLink'=>'/register',
            'highlight'=>false
            ],
            [
            'title'=>'Essencial',
            'price'=>'19.000 KZ/mês',
            'features'=>[
            'Faturação ilimitada',
            'Até 3 utilizadores',
            'Suporte prioritário',
            'Armazenamento ilimitado',
            'Gestão de clientes avançada',
            'Relatórios detalhados (trimestrais)'
            ],
            'btn'=>'Assinar',
            'btnColor'=>'bg-[#F9941F]',
            'btnLink'=>'/register',
            'highlight'=>false
            ],
            [
            'title'=>'Essencial',
            'price'=>'19.000 KZ/mês',
            'features'=>[
            'Faturação ilimitada',
            'Até 3 utilizadores',
            'Suporte prioritário',
            'Armazenamento ilimitado',
            'Gestão de clientes avançada',
            'Relatórios detalhados (trimestrais)'
            ],
            'btn'=>'Assinar',
            'btnColor'=>'bg-[#F9941F]',
            'btnLink'=>'/register',
            'highlight'=>false
            ],
            [
            'title'=>'Pro',
            'price'=>'39.000 KZ/mês',
            'features'=>[
            'Tudo no Essencial',
            'Até 5 utilizadores',
            'API de integração',
            'Automação simples de pagamentos',
            'Monitorização de pagamentos',
            'Relatórios avançados (personalizáveis)'
            ],
            'btn'=>'Assinar',
            'btnColor'=>'bg-[#123859]',
            'btnLink'=>'/register',
            'highlight'=>false
            ]
            ] as $plan)
            <div class="p-6 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 text-center animate-fadeInSoft {{ $plan['highlight'] ? 'border-4 border-[#F9941F] bg-white' : 'bg-white border-t-4 border-[#F9941F]' }}">
                <h3 class="font-bold text-xl mb-2 text-[#123859]">{{ $plan['title'] }}</h3>
                <p class="text-3xl font-extrabold text-[#123859] mb-4">{{ $plan['price'] }}</p>
                <ul class="mb-6 text-sm text-gray-600 space-y-2 text-left max-w-xs mx-auto">
                    @foreach($plan['features'] as $feature)
                    <li class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-1 text-[#F9941F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ $plan['btnLink'] }}" class="px-6 py-3 rounded-full {{ $plan['btnColor'] }} text-white font-semibold hover:brightness-95 transition">
                    {{ $plan['btn'] }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="py-24 bg-[#F2F2F2]">
    <div class="max-w-3xl mx-auto px-6 lg:px-8" x-data="{ active: null }">
        <h2 class="text-3xl font-bold text-center text-[#123859] mb-12 animate-fadeInUp">
            Perguntas Frequentes
        </h2>
        <div class="space-y-4">
            @foreach([
            ['q'=>'O que torna o Fatura Já diferente de outras plataformas?','a'=>'O FacturaJá foca-se na simplicidade e rapidez. Pode criar uma fatura profissional em menos de 60 segundos, com ênfase na conformidade legal angolana e num design limpo e moderno.'],
            ['q'=>'Quais são os requisitos legais para emitir faturas em Angola?','a'=>'O FaturaJá garante que todas as faturas emitidas estão em conformidade com as normas fiscais angolanas, incluindo a inclusão de NIF, detalhes do cliente e requisitos específicos do IVA.'],
            ['q'=>'Posso emitir faturas ilimitadas?','a'=>'Sim, no plano Pro e Enterprise você pode emitir faturas sem limites.'],
            ['q'=>'Posso gerar relatórios das minhas vendas e faturamento?','a'=>'Sim, os planos Essencial, Pro, Premium e Empresa permitem gerar relatórios detalhados de faturamento e vendas, ajudando a monitorizar o desempenho financeiro da sua empresa.'],
            ['q'=>'Como funciona o suporte?','a'=>'O suporte é via e-mail no plano gratuito e prioritário nos planos pagos.']
            ] as $index => $faq)
            <div class="border-b animate-fadeInUp">
                <!-- Pergunta -->
                <button @click="active === {{ $index }} ? active = null : active = {{ $index }}"
                    class="w-full text-left flex justify-between items-center py-4 font-semibold text-[#123859] transition-colors duration-300 hover:text-[#F9941F]">
                    {{ $faq['q'] }}
                    <!-- Ícone rotativo -->
                    <svg :class="{'rotate-180': active === {{ $index }}}"
                        class="w-5 h-5 transform transition-transform duration-300 text-[#F9941F]"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Resposta com animação -->
                <div x-show="active === {{ $index }}"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 transform -translate-y-2 max-h-0"
                    x-transition:enter-end="opacity-100 transform translate-y-0 max-h-screen"
                    x-transition:leave="transition ease-in duration-400"
                    x-transition:leave-start="opacity-100 transform translate-y-0 max-h-screen"
                    x-transition:leave-end="opacity-0 transform -translate-y-2 max-h-0"
                    class="overflow-hidden">
                    <p class="text-gray-600 py-2">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Contacto -->
<section id="contacto" class="py-24 bg-[#F2F2F2]">
    <div class="max-w-2xl mx-auto px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-xl p-8 animate-fadeInUp">
            <h2 class="text-3xl font-bold text-center text-[#123859] mb-8">
                Deixe Seu <span class="text-[#F9941F]">Comentário</span>
            </h2>
            <form action="#" method="POST" class="space-y-6">
                @csrf
                <input type="text" name="nome" placeholder="Nome" required
                    class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#123859] transition duration-300 hover:brightness-95 shadow-sm">
                <input type="email" name="email" placeholder="Email" required
                    class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#123859] transition duration-300 hover:brightness-95 shadow-sm">
                <textarea name="mensagem" rows="5" placeholder="Mensagem" required
                    class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#123859] transition duration-300 hover:brightness-95 shadow-sm"></textarea>
                <div class="text-center">
                    <button type="submit"
                        class="px-6 py-3 rounded-full bg-[#123859] text-white font-semibold shadow-md transition duration-300 hover:brightness-90">
                        Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>


@endsection

@section('scripts')
<script>
    lucide.createIcons();
    // Fade-in progressivo
    document.addEventListener('DOMContentLoaded', () => {
        const elements = document.querySelectorAll('.animate-fadeInUp, .animate-fadeInLeft, .animate-fadeInRight');
        elements.forEach((el, i) => {
            el.style.opacity = 0;
            el.style.transform = 'translateY(20px)';
            setTimeout(() => {
                el.style.transition = 'all 0.8s ease-out';
                el.style.opacity = 1;
                el.style.transform = 'translateY(0)';
            }, i * 150);
        });
    });
</script>
@endsection

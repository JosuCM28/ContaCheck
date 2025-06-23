<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despacho Contable BM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .nav-link { transition: color 0.3s ease; }
        .nav-link:hover { color: #000; }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); }
        .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .accordion-content.open { max-height: 500px; }
    </style>
</head>
<body class="bg-white">
    <!-- Navbar -->
    <nav x-data="{ open: false }" class="bg-white shadow-sm fixed w-full z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="#inicio" class="text-xl font-semibold text-black">Despacho Contable BM</a>
            <div class="hidden lg:flex space-x-6 items-center text-sm">
                <a href="#inicio" class="nav-link text-gray-700 hover:text-black">Inicio</a>
                <a href="#nosotros" class="nav-link text-gray-700 hover:text-black">Acerca de</a>
                <a href="#servicios" class="nav-link text-gray-700 hover:text-black">Servicios</a>
                <a href="#testimonios" class="nav-link text-gray-700 hover:text-black">Testimonios</a>
                <a href="#contacto" class="nav-link text-gray-700 hover:text-black">Contacto</a>
            </div>
            <div class="hidden lg:flex space-x-4 items-center text-sm">
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-black">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800">Registrarse</a>
            </div>
            <button @click="open = !open" class="lg:hidden text-gray-700 focus:outline-none">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div x-show="open" class="lg:hidden bg-white shadow-sm">
            <div class="container mx-auto px-4 py-4 flex flex-col space-y-4">
                <a href="#inicio" class="nav-link text-gray-700">Inicio</a>
                <a href="#nosotros" class="nav-link text-gray-700">Acerca de</a>
                <a href="#servicios" class="nav-link text-gray-700">Servicios</a>
                <a href="#testimonios" class="nav-link text-gray-700">Testimonios</a>
                <a href="#contacto" class="nav-link text-gray-700">Contacto</a>
                <a href="{{ route('login') }}" class="text-gray-700">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded-md w-fit hover:bg-gray-800">Registrarse</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="pt-20 pb-16 bg-black text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-10 flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight">30+ Años de Tranquilidad Financiera</h1>
                <p class="mt-4 text-lg sm:text-xl">Soluciones contables y fiscales en Altotonga, Veracruz.</p>
                <a href="#contacto" class="mt-6 inline-block bg-white text-black px-6 py-3 rounded-md font-medium hover:bg-gray-200">Contáctanos</a>
            </div>
            <div class="lg:w-1/2 mt-8 lg:mt-0">
                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40" alt="Hero Image" class="w-full h-auto rounded-lg">
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="nosotros" class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-black">Sobre Nosotros</h2>
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg text-center">
                    <img src="{{ asset('img/learn.svg') }}" alt="Contadores Actualizados" class="w-16 h-16 mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-black">Contadores Actualizados</h3>
                    <p class="mt-2 text-gray-700">Modelos contables innovadores para mantener tu empresa al día.</p>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg text-center">
                    <img src="{{ asset('img/grafica.svg') }}" alt="Compromiso" class="w-16 h-16 mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-black">Compromiso Empresarial</h3>
                    <p class="mt-2 text-gray-700">30 años de experiencia como aliados de tu negocio.</p>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg text-center">
                    <img src="{{ asset('img/transparencia.svg') }}" alt="Transparencia" class="w-16 h-16 mx-auto mb-4">
                    <h3 class="text-xl font-semibold text-black">Transparencia</h3>
                    <p class="mt-2 text-gray-700">Gestión clara y confiable de tus finanzas.</p>
                </div>
            </div>
            <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="card-hover bg-white border border-gray-200 p-8 rounded-lg">
                    <h3 class="text-2xl font-semibold text-black">¿Quiénes Somos?</h3>
                    <p class="mt-4 text-gray-700">Despacho Contable BM en Altotonga, Veracruz, ofrece soluciones contables y fiscales personalizadas con más de 30 años de experiencia, garantizando calidad, honestidad y transparencia.</p>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-8 rounded-lg">
                    <h3 class="text-2xl font-semibold text-black">¿Cómo lo Hacemos?</h3>
                    <p class="mt-4 text-gray-700">Aplicamos contabilidad precisa y estrategias fiscales efectivas, con un enfoque analítico y disciplinado para cumplir tus obligaciones financieras.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicios" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-black">Nuestros Servicios</h2>
            <div x-data="{ openModal: null }" class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg cursor-pointer" @click="openModal = 'modal1'">
                    <h3 class="text-xl font-semibold text-black text-center">Contabilidad General</h3>
                    <ul class="mt-4 list-disc pl-5 text-gray-700">
                        <li>Registro de ingresos y egresos</li>
                        <li>Balanzas de comprobación</li>
                        <li>Conciliaciones bancarias</li>
                        <li>Reportes financieros</li>
                    </ul>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg cursor-pointer" @click="openModal = 'modal2'">
                    <h3 class="text-xl font-semibold text-black text-center">Declaraciones Fiscales</h3>
                    <ul class="mt-4 list-disc pl-5 text-gray-700">
                        <li>Declaraciones anuales y provisionales</li>
                        <li>Cálculo de impuestos</li>
                        <li>Regularización fiscal</li>
                        <li>Optimización tributaria</li>
                    </ul>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg cursor-pointer" @click="openModal = 'modal3'">
                    <h3 class="text-xl font-semibold text-black text-center">Nómina y Recursos Humanos</h3>
                    <ul class="mt-4 list-disc pl-5 text-gray-700">
                        <li>Elaboración de nómina</li>
                        <li>Cálculo de prestaciones</li>
                        <li>Cuotas IMSS y SAT</li>
                        <li>Reportes de nómina</li>
                    </ul>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg cursor-pointer" @click="openModal = 'modal4'">
                    <h3 class="text-xl font-semibold text-black text-center">Auditorías Contables</h3>
                    <ul class="mt-4 list-disc pl-5 text-gray-700">
                        <li>Auditorías internas y externas</li>
                        <li>Identificación de irregularidades</li>
                        <li>Optimización de procesos</li>
                        <li>Preparación para fiscalización</li>
                    </ul>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg cursor-pointer" @click="openModal = 'modal5'">
                    <h3 class="text-xl font-semibold text-black text-center">Asesoría Financiera</h3>
                    <ul class="mt-4 list-disc pl-5 text-gray-700">
                        <li>Planeación fiscal</li>
                        <li>Análisis de riesgos</li>
                        <li>Proyección de flujos</li>
                        <li>Reducción de costos</li>
                    </ul>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg cursor-pointer" @click="openModal = 'modal6'">
                    <h3 class="text-xl font-semibold text-black text-center">Trámites Gubernamentales</h3>
                    <ul class="mt-4 list-disc pl-5 text-gray-700">
                        <li>Gestión de RFC</li>
                        <li>Trámites SAT e IMSS</li>
                        <li>Altas y bajas</li>
                        <li>Devoluciones fiscales</li>
                    </ul>
                </div>
            </div>
            <!-- Modals -->
            <template x-if="openModal === 'modal1'">
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="openModal = null">
                    <div class="bg-white p-8 rounded-lg max-w-md w-full" @click.stop>
                        <h3 class="text-2xl font-semibold text-black">Contabilidad General</h3>
                        <p class="mt-4 text-gray-700">Gestión integral de registros contables, conciliaciones y reportes financieros.</p>
                        <button @click="openModal = null" class="mt-6 bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800">Cerrar</button>
                    </div>
                </div>
            </template>
            <template x-if="openModal === 'modal2'">
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="openModal = null">
                    <div class="bg-white p-8 rounded-lg max-w-md w-full" @click.stop>
                        <h3 class="text-2xl font-semibold text-black">Declaraciones Fiscales</h3>
                        <p class="mt-4 text-gray-700">Cumplimiento de obligaciones fiscales con cálculos precisos y optimización tributaria.</p>
                        <button @click="openModal = null" class="mt-6 bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800">Cerrar</button>
                    </div>
                </div>
            </template>
            <template x-if="openModal === 'modal3'">
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="openModal = null">
                    <div class="bg-white p-8 rounded-lg max-w-md w-full" @click.stop>
                        <h3 class="text-2xl font-semibold text-black">Nómina y Recursos Humanos</h3>
                        <p class="mt-4 text-gray-700">Gestión completa de nóminas y cumplimiento de obligaciones laborales.</p>
                        <button @click="openModal = null" class="mt-6 bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800">Cerrar</button>
                    </div>
                </div>
            </template>
            <template x-if="openModal === 'modal4'">
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="openModal = null">
                    <div class="bg-white p-8 rounded-lg max-w-md w-full" @click.stop>
                        <h3 class="text-2xl font-semibold text-black">Auditorías Contables</h3>
                        <p class="mt-4 text-gray-700">Revisión detallada para garantizar transparencia y cumplimiento normativo.</p>
                        <button @click="openModal = null" class="mt-6 bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800">Cerrar</button>
                    </div>
                </div>
            </template>
            <template x-if="openModal === 'modal5'">
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="openModal = null">
                    <div class="bg-white p-8 rounded-lg max-w-md w-full" @click.stop>
                        <h3 class="text-2xl font-semibold text-black">Asesoría Financiera</h3>
                        <p class="mt-4 text-gray-700">Estrategias personalizadas para optimizar la rentabilidad de tu negocio.</p>
                        <button @click="openModal = null" class="mt-6 bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800">Cerrar</button>
                    </div>
                </div>
            </template>
            <template x-if="openModal === 'modal6'">
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="openModal = null">
                    <div class="bg-white p-8 rounded-lg max-w-md w-full" @click.stop>
                        <h3 class="text-2xl font-semibold text-black">Trámites Gubernamentales</h3>
                        <p class="mt-4 text-gray-700">Gestión eficiente de trámites ante entidades gubernamentales.</p>
                        <button @click="openModal = null" class="mt-6 bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800">Cerrar</button>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonios" class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-black">Lo Que Dicen Nuestros Clientes</h2>
            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg text-center">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d" alt="Testimonial 1" class="w-20 h-20 rounded-full mx-auto mb-4 object-cover">
                    <p class="text-gray-700 italic">"El equipo de Despacho Contable BM ha transformado la gestión financiera de mi negocio con profesionalismo y claridad."</p>
                    <p class="mt-4 text-black font-semibold">Juan Pérez</p>
                    <p class="text-gray-500 text-sm">Empresario Local</p>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg text-center">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330" alt="Testimonial 2" class="w-20 h-20 rounded-full mx-auto mb-4 object-cover">
                    <p class="text-gray-700 italic">"Su atención personalizada y experiencia fiscal nos han ahorrado tiempo y recursos valiosos."</p>
                    <p class="mt-4 text-black font-semibold">María Gómez</p>
                    <p class="text-gray-500 text-sm">Propietaria de Negocio</p>
                </div>
                <div class="card-hover bg-white border border-gray-200 p-6 rounded-lg text-center">
                    <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7" alt="Testimonial 3" class="w-20 h-20 rounded-full mx-auto mb-4 object-cover">
                    <p class="text-gray-700 italic">"Confío plenamente en BM para manejar mis obligaciones contables con transparencia y eficiencia."</p>
                    <p class="mt-4 text-black font-semibold">Carlos Ramírez</p>
                    <p class="text-gray-500 text-sm">Contribuyente Individual</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" class="py-16 bg-black text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-4">
                <h2 class="text-3xl font-bold mb-10">Contáctanos</h2>
                <p class="mt-4 text-md"><span class="font-bold">Calle:</span> Av. Mariano Abasolo No. 37, Colonia Centro, Altotonga, Veracruz, México, 93700</p>
                <p class="mt-2 text-md"><span class="font-bold">Email:</span> baltazarmontes77@prodigy.net.mx</p>
                <p class="mt-2 text-md"><span class="font-bold">Tel:</span> +52 226 316 1354 / +52 226 316 0629</p>
            </div>
            <div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d234.6763431503151!2d-97.24427016139883!3d19.76273746513662!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85dadd220dad0f15%3A0xa557afc86a4751a0!2sAv.%20Gral.%20Mariano%20Abasolo%20Sur%2037a%2C%20La%20Loma%2C%2093700%20Altotonga%2C%20Ver.!5e0!3m2!1ses!2smx!4v1733877724906!5m2!1ses!2smx" class="w-full h-64 rounded-lg border border-gray-200" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="query" class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-black">Preguntas Frecuentes</h2>
            <div x-data="{ open: 'faq1' }" class="mt-12 max-w-2xl mx-auto">
                <div class="border-b border-gray-200">
                    <button @click="open = open === 'faq1' ? null : 'faq1'" class="w-full text-left py-4 flex justify-between items-center">
                        <span class="text-lg font-medium text-black">¿Cómo agendar una consulta?</span>
                        <svg :class="{ 'rotate-180': open === 'faq1' }" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open === 'faq1'" class="accordion-content open pb-4 text-gray-700">A través de nuestro formulario, teléfono o correo electrónico.</div>
                </div>
                <div class="border-b border-gray-200">
                    <button @click="open = open === 'faq2' ? null : 'faq2'" class="w-full text-left py-4 flex justify-between items-center">
                        <span class="text-lg font-medium text-black">¿Qué información necesito?</span>
                        <svg :class="{ 'rotate-180': open === 'faq2' }" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open === 'faq2'" class="accordion-content open pb-4 text-gray-700">Facturas, estados de cuenta, registros y declaraciones previas.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                {{-- <img src="{{ asset('img/DESPACHO.png') }}" alt="Logo" class="w-32 h-auto"> --}}
            </div>
            <div>
                <h3 class="text-lg font-semibold">Navegación</h3>
                <ul class="mt-4 space-y-2">
                    <li><a href="#inicio" class="hover:text-gray-300">Inicio</a></li>
                    <li><a href="#nosotros" class="hover:text-gray-300">Acerca de</a></li>
                    <li><a href="#servicios" class="hover:text-gray-300">Servicios</a></li>
                    <li><a href="#testimonios" class="hover:text-gray-300">Testimonios</a></li>
                    <li><a href="#contacto" class="hover:text-gray-300">Contacto</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Páginas de Interés</h3>
                <ul class="mt-4 space-y-2">
                    <li><a href="https://www.sat.gob.mx/home" target="_blank" class="hover:text-gray-300">SAT</a></li>
                    <li><a href="https://www.imss.gob.mx/" target="_blank" class="hover:text-gray-300">IMSS</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Contacto</h3>
                <ul class="mt-4 space-y-2">
                    <li><a href="mailto:baltazarmontes77@prodigy.net.mx" class="hover:text-gray-300">baltazarmontes77@prodigy.net.mx</a></li>
                    <li><a href="tel:+522263161354" class="hover:text-gray-300">+52 226 316 1354</a></li>
                    <li><a href="tel:+522263160629" class="hover:text-gray-300">+52 226 316 0629</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
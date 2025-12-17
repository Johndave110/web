<?php
// Simple landing page (public) for Scholarship Portal.
// Now located at project root.
?>
<!DOCTYPE html>
<?php
// Scholarship Portal Landing Page using Tailwind (CDN)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Scholarship Portal</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Extend Tailwind theme for brand colors
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#1e40af', // primary
                            green: '#16a34a', // success
                            dark: '#0f172a', // footer background
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
    </style>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%231e40af'/><text x='50' y='60' font-size='50' text-anchor='middle' fill='white'>SP</text></svg>">
    <meta name="description" content="Find, apply, and track scholarships with ease.">
</head>
<body class="bg-white text-slate-800">
    <!-- Nav -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur shadow">
        <div class="max-w-7xl mx-auto px-5">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="h-9 w-9 rounded bg-brand-blue/10 flex items-center justify-center text-brand-blue font-bold">SP</div>
                    <span class="text-lg font-semibold text-brand-blue">Scholarship Portal</span>
                </div>
                <!-- Desktop links -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="index.php" class="text-slate-700 hover:text-brand-blue transition">Home</a>
                    <a href="browsescholarships.php" class="text-slate-700 hover:text-brand-blue transition">Browse</a>
                    <a href="login.php" class="text-slate-700 hover:text-brand-blue transition">Login</a>
                    <a href="Register.php" class="inline-flex items-center px-4 py-2 rounded-md bg-brand-blue text-white hover:bg-blue-700 transition">Sign Up</a>
                </div>
                <!-- Mobile menu toggle -->
                <div class="md:hidden">
                    <button id="mobileMenuBtn" class="p-2 rounded hover:bg-slate-100" aria-label="Open Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Mobile menu -->
            <div id="mobileMenu" class="md:hidden hidden border-t">
                <div class="py-3 space-y-2">
                    <a href="index.php" class="block px-2 py-2 rounded hover:bg-slate-100">Home</a>
                    <a href="browsescholarships.php" class="block px-2 py-2 rounded hover:bg-slate-100">Browse</a>
                    <a href="login.php" class="block px-2 py-2 rounded hover:bg-slate-100">Login</a>
                    <a href="Register.php" class="block px-2 py-2 rounded bg-brand-blue text-white hover:bg-blue-700">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <header class="pt-24 bg-gradient-to-br from-brand-blue to-blue-600 text-white">
        <div class="max-w-4xl mx-auto px-6 text-center py-24">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4">Find, Apply & Track Scholarships Easily</h1>
            <p class="text-lg md:text-xl opacity-90 mb-8">Unified platform for students and administrators. Browse opportunities, submit applications, and manage awards with a consistent experience.</p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="browsescholarships.php" class="inline-flex items-center px-5 py-3 rounded-md bg-brand-green text-white font-semibold shadow hover:bg-green-600 transition">Browse Scholarships</a>
                <a href="login.php" class="inline-flex items-center px-5 py-3 rounded-md border border-white/70 text-white hover:bg-white/10 transition">Login</a>
            </div>
        </div>
    </header>

    <!-- Features -->
    <main class="max-w-7xl mx-auto px-6 py-12">
        <section id="features" class="py-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center rounded-lg bg-slate-50 p-6 shadow-sm">
                    <h3 class="text-brand-blue font-semibold mb-2">Discover Opportunities</h3>
                    <p class="text-slate-600">Filter scholarships by criteria and deadlines.</p>
                </div>
                <div class="text-center rounded-lg bg-slate-50 p-6 shadow-sm">
                    <h3 class="text-brand-blue font-semibold mb-2">Easy Applications</h3>
                    <p class="text-slate-600">Submit and track applications in one place.</p>
                </div>
                <div class="text-center rounded-lg bg-slate-50 p-6 shadow-sm">
                    <h3 class="text-brand-blue font-semibold mb-2">Admin Tools</h3>
                    <p class="text-slate-600">Review, approve, and report with streamlined workflows.</p>
                </div>
                <div class="text-center rounded-lg bg-slate-50 p-6 shadow-sm">
                    <h3 class="text-brand-blue font-semibold mb-2">Notifications</h3>
                    <p class="text-slate-600">Get timely updates and reminders.</p>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how" class="text-center bg-slate-100 rounded-xl py-16">
            <div class="max-w-3xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-slate-800 mb-4">How It Works</h2>
                <p class="text-slate-700 mb-8">Create a profile, browse scholarships, submit applications, and track your progress. Administrators manage listings, review applications, and award scholarships seamlessly.</p>
                <a href="Register.php" class="inline-flex items-center px-6 py-3 rounded-md bg-brand-blue text-white hover:bg-blue-700 transition text-lg">Get Started</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-brand-dark text-white text-center py-6">
        <p class="text-sm">&copy; <?php echo date('Y'); ?> Scholarship Portal. All rights reserved.</p>
        <p class="text-sm"><a class="text-slate-300 hover:text-white" href="#top">Back to top</a></p>
    </footer>

    <!-- Simple mobile menu script -->
    <script>
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>
    <!-- Optional: mark top of page for #top anchor -->
    <div id="top" class="hidden"></div>
    
</body>
</html>

<meta name="color-scheme" content="light dark">
<script>
    (function () {
        try {
            var saved = localStorage.getItem('color-scheme');
            var meta = document.querySelector('meta[name="color-scheme"]');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (meta && (saved === 'light' || saved === 'dark')) {
                meta.content = saved;
                document.documentElement.setAttribute('data-theme', saved);
                document.documentElement.setAttribute('data-bs-theme', saved);
            } else {
                document.documentElement.setAttribute('data-bs-theme', prefersDark ? 'dark' : 'light');
            }
        } catch (e) {}
    })();
</script>

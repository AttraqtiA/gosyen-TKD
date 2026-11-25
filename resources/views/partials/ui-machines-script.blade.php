<script>
    (function () {
        // 1. Explicit Color Scheme State Machine (states: 'system' | 'light' | 'dark')
        var toggleBtn = document.getElementById('{{ $toggleId ?? "themeToggleBtn" }}');
        var meta = document.querySelector('meta[name="color-scheme"]');
        var mql = window.matchMedia('(prefers-color-scheme: dark)');

        var ThemeMachine = {
            getSystem: function () {
                return mql.matches ? 'dark' : 'light';
            },
            getStored: function () {
                try {
                    var val = localStorage.getItem('color-scheme');
                    return (val === 'light' || val === 'dark') ? val : null;
                } catch (e) {
                    return null;
                }
            },
            apply: function (scheme) {
                if (scheme === 'light' || scheme === 'dark') {
                    if (meta) meta.content = scheme;
                    document.documentElement.setAttribute('data-theme', scheme);
                    document.documentElement.setAttribute('data-bs-theme', scheme);
                } else {
                    if (meta) meta.content = 'light dark';
                    document.documentElement.removeAttribute('data-theme');
                    document.documentElement.setAttribute('data-bs-theme', this.getSystem());
                }
            },
            toggle: function () {
                var current = this.getStored() || this.getSystem();
                var target = current === 'dark' ? 'light' : 'dark';
                var nextStored = target === this.getSystem() ? null : target;
                try {
                    if (nextStored === null) {
                        localStorage.removeItem('color-scheme');
                    } else {
                        localStorage.setItem('color-scheme', nextStored);
                    }
                } catch (e) {}
                this.apply(nextStored);
            }
        };

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                ThemeMachine.toggle();
            });
        }

        window.addEventListener('storage', function (e) {
            if (e.key === 'color-scheme') {
                ThemeMachine.apply(e.newValue === 'light' || e.newValue === 'dark' ? e.newValue : null);
            }
        });

        mql.addEventListener('change', function () {
            if (!ThemeMachine.getStored()) {
                ThemeMachine.apply(null);
            }
        });

        // 2. Explicit Page Navigation & Form Submit Progress State Machine
        // States: 'idle' -> 'navigating' | 'submitting' -> 'idle' (on pageshow)
        var progressBar = document.getElementById('top-progress-bar');
        var loadingOverlay = document.getElementById('app-loading-overlay');
        var ignoreDownloads = {{ !empty($ignoreDownloads) ? 'true' : 'false' }};

        var NavMachine = {
            state: 'idle',
            transition: function (nextState) {
                this.state = nextState;
                if (nextState === 'navigating') {
                    if (progressBar) {
                        progressBar.classList.add('active');
                        progressBar.style.width = '75%';
                    }
                } else if (nextState === 'submitting') {
                    if (progressBar) {
                        progressBar.classList.add('active');
                        progressBar.style.width = '75%';
                    }
                    if (loadingOverlay) loadingOverlay.classList.add('show');
                } else {
                    if (progressBar) {
                        progressBar.style.width = '100%';
                        setTimeout(function () {
                            progressBar.classList.remove('active');
                            progressBar.style.width = '0%';
                        }, 200);
                    }
                    if (loadingOverlay) loadingOverlay.classList.remove('show');
                }
            }
        };

        document.addEventListener('click', function (e) {
            var link = e.target.closest('a[href]');
            if (!link) return;
            var href = link.getAttribute('href');
            if (!href || href.startsWith('#') || link.target === '_blank' || link.hasAttribute('download')) return;
            if (ignoreDownloads && (href.includes('export') || href.includes('/pdf/') || href.includes('/word/'))) return;
            NavMachine.transition('navigating');
        });

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (form && form.method && form.method.toLowerCase() === 'post') {
                NavMachine.transition('submitting');
            } else {
                NavMachine.transition('navigating');
            }
        });

        window.addEventListener('pageshow', function () {
            NavMachine.transition('idle');
        });

        window.AppThemeMachine = ThemeMachine;
        window.AppNavMachine = NavMachine;
    })();
</script>

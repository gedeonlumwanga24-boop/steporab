<footer class="footer">
    <div class="footer-top">
        <h3 class="footer-top-title">Sélection Stepora</h3>

        <div class="footer-categories">
            @foreach($footerData['selection'] ?? [] as $column)
                <ul>
                    @foreach($column as $link)
                        <li>
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>

        <p class="footer-description">
            Découvrez les sneakers et chaussures tendance sur <strong>Stepora</strong>.
            Du streetwear au sport, une sélection pensée pour le style, le confort et le quotidien en RDC.
        </p>
    </div>

    <div class="footer-socials">
        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
        </a>
        <a href="https://www.instagram.com/ged8806/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
            <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="https://x.com/GedeonLumw77743" target="_blank" rel="noopener noreferrer" aria-label="X">
            <i class="fa-brands fa-x-twitter"></i>
        </a>
        <a href="https://wa.me/243970297987" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        <a href="https://t.me/gedeonlumwanga" target="_blank" rel="noopener noreferrer" aria-label="Telegram">
            <i class="fa-brands fa-telegram"></i>
        </a>
    </div>

    {{-- Desktop : colonnes --}}
    <div class="footer-bottom footer-bottom--desktop">
        @foreach($footerData['columns'] ?? [] as $column)
            <div class="footer-column">
                <h4>{{ $column['title'] }}</h4>
                <ul>
                    @foreach($column['links'] as $link)
                        <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <div class="footer-column">
            <h4>Pays</h4>
            <p class="footer-country">Vous êtes en 🇨🇩 RDC</p>
            <a href="{{ route('contact.index') }}" class="change-country">Nous contacter</a>
        </div>
    </div>

    {{-- Mobile : accordéon --}}
    <div class="footer-bottom footer-bottom--mobile">
        @foreach($footerData['columns'] ?? [] as $column)
            <div class="footer-accordion" data-footer-accordion>
                <button type="button" class="footer-accordion-trigger" aria-expanded="false">
                    <span>{{ $column['title'] }}</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="footer-accordion-panel" hidden>
                    <ul>
                        @foreach($column['links'] as $link)
                            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach

        <div class="footer-accordion" data-footer-accordion>
            <button type="button" class="footer-accordion-trigger" aria-expanded="false">
                <span>Pays</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
            <div class="footer-accordion-panel" hidden>
                <p class="footer-country">Vous êtes en 🇨🇩 RDC</p>
                <a href="{{ route('contact.index') }}" class="change-country">Nous contacter</a>
            </div>
        </div>
    </div>

    <div class="footer-copy-container">
        <div class="footer-copy">© {{ date('Y') }} Stepora — Tous droits réservés.</div>
        <div class="footer-legal">
            @foreach($footerData['legal'] ?? [] as $link)
                <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
            @endforeach
        </div>
    </div>
</footer>

<script>
(function () {
    document.querySelectorAll('[data-footer-accordion]').forEach(function (item) {
        var trigger = item.querySelector('.footer-accordion-trigger');
        var panel = item.querySelector('.footer-accordion-panel');
        if (!trigger || !panel) return;

        trigger.addEventListener('click', function () {
            var isOpen = item.classList.contains('is-open');

            document.querySelectorAll('[data-footer-accordion].is-open').forEach(function (other) {
                if (other === item) return;
                other.classList.remove('is-open');
                other.querySelector('.footer-accordion-trigger')?.setAttribute('aria-expanded', 'false');
                var otherPanel = other.querySelector('.footer-accordion-panel');
                if (otherPanel) otherPanel.hidden = true;
            });

            item.classList.toggle('is-open', !isOpen);
            trigger.setAttribute('aria-expanded', String(!isOpen));
            panel.hidden = isOpen;
        });
    });
})();
</script>

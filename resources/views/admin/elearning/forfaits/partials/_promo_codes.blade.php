<div class="mb-8 border border-gray-200 rounded-lg overflow-hidden">
    <div class="bg-gradient-to-r from-purple-50 to-white px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-md font-medium text-gray-900">
                    <i class="fas fa-ticket-alt text-purple-600 mr-2"></i>
                    Codes promo d'accès gratuit
                </h3>
                <p class="text-sm text-gray-500 mt-1">Créez des codes qui permettent d'accéder gratuitement à ce forfait</p>
            </div>
            <button type="button" id="addPromoCodeBtn"
                class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                <i class="fas fa-plus mr-1"></i> Ajouter un code
            </button>
        </div>
    </div>

    <div class="p-6 bg-white">
        <div id="promoCodesContainer" class="space-y-3">
            @php
                $promoCodes = isset($forfait) ? ($forfait->promo_codes ?? []) : [];
                if (is_string($promoCodes)) $promoCodes = json_decode($promoCodes, true) ?? [];
            @endphp

            @if(count($promoCodes) > 0)
                @foreach($promoCodes as $index => $promo)
                <div class="promo-code-item flex flex-wrap items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Code promo</label>
                        <input type="text" name="promo_code_items[{{ $index }}][code]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                            placeholder="EX: PROMO2024"
                            value="{{ $promo['code'] ?? '' }}">
                    </div>
                    <div class="w-32">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Utilisations max</label>
                        <input type="number" name="promo_code_items[{{ $index }}][max_uses]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                            placeholder="Illimité"
                            value="{{ $promo['max_uses'] ?? '' }}">
                    </div>
                    <div class="w-32">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Déjà utilisé</label>
                        <input type="text" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                            value="{{ $promo['used_count'] ?? 0 }}" readonly disabled>
                    </div>
                    <div class="w-24">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Actif</label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="promo_code_items[{{ $index }}][is_active]"
                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                value="1" {{ ($promo['is_active'] ?? true) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Oui</span>
                        </label>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-promo-code px-3 py-2 text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <div id="noPromoCodesMsg" class="text-center py-6 text-gray-500 {{ count($promoCodes) > 0 ? 'hidden' : '' }}">
            <i class="fas fa-ticket-alt text-3xl mb-2 opacity-50"></i>
            <p>Aucun code promo. Cliquez sur "Ajouter un code" pour en créer.</p>
        </div>

        <p class="text-xs text-gray-500 mt-3 pt-2 border-t border-gray-100">
            <i class="fas fa-info-circle mr-1"></i>
            Les utilisateurs pourront saisir ces codes sur la page d'achat pour accéder gratuitement au forfait.
            Laissez "Utilisations max" vide pour un nombre illimité.
        </p>
    </div>
</div>

<input type="hidden" name="promo_codes" id="promoCodesJson" value="{{ json_encode($promoCodes) }}">

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const promoCodesContainer = document.getElementById('promoCodesContainer');
    const addPromoCodeBtn = document.getElementById('addPromoCodeBtn');
    const promoCodesJson = document.getElementById('promoCodesJson');
    const noPromoCodesMsg = document.getElementById('noPromoCodesMsg');
    let promoCodeIndex = {{ count($promoCodes) }};

    function updatePromoCodesJson() {
        const items = document.querySelectorAll('.promo-code-item');
        const promoCodes = [];

        items.forEach(item => {
            const code = item.querySelector('input[name*="[code]"]')?.value.trim().toUpperCase();
            if (code) {
                promoCodes.push({
                    code: code,
                    max_uses: item.querySelector('input[name*="[max_uses]"]')?.value || null,
                    used_count: parseInt(item.querySelector('input[readonly][disabled]')?.value) || 0,
                    is_active: item.querySelector('input[name*="[is_active]"]')?.checked || false,
                });
            }
        });

        promoCodesJson.value = JSON.stringify(promoCodes);
        if (noPromoCodesMsg) {
            noPromoCodesMsg.classList.toggle('hidden', promoCodes.length > 0);
        }
    }

    function addPromoCode(code = '', maxUses = '', isActive = true) {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'promo-code-item flex flex-wrap items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200';
        itemDiv.innerHTML = `
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-gray-700 mb-1">Code promo</label>
                <input type="text" name="promo_code_items[${promoCodeIndex}][code]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                    placeholder="EX: PROMO2024"
                    value="${escapeHtml(code)}">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-gray-700 mb-1">Utilisations max</label>
                <input type="number" name="promo_code_items[${promoCodeIndex}][max_uses]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                    placeholder="Illimité"
                    value="${escapeHtml(maxUses)}">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-gray-700 mb-1">Déjà utilisé</label>
                <input type="text" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"
                    value="0" readonly disabled>
            </div>
            <div class="w-24">
                <label class="block text-xs font-medium text-gray-700 mb-1">Actif</label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="promo_code_items[${promoCodeIndex}][is_active]"
                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                        value="1" ${isActive ? 'checked' : ''}>
                    <span class="ml-2 text-sm text-gray-600">Oui</span>
                </label>
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-promo-code px-3 py-2 text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        promoCodesContainer.appendChild(itemDiv);

        itemDiv.querySelector('.remove-promo-code').addEventListener('click', function() {
            itemDiv.remove();
            updatePromoCodesJson();
        });

        itemDiv.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('change', updatePromoCodesJson);
            input.addEventListener('input', updatePromoCodesJson);
        });

        promoCodeIndex++;
        updatePromoCodesJson();
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    if (addPromoCodeBtn) {
        addPromoCodeBtn.addEventListener('click', function() {
            addPromoCode();
        });
    }

    document.querySelectorAll('.remove-promo-code').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.promo-code-item').remove();
            updatePromoCodesJson();
        });
    });

    document.querySelectorAll('.promo-code-item input, .promo-code-item select').forEach(input => {
        input.addEventListener('change', updatePromoCodesJson);
        input.addEventListener('input', updatePromoCodesJson);
    });

    updatePromoCodesJson();
});
</script>
@endpush

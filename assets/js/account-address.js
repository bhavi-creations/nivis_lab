(() => {
 const country = document.getElementById('country');
 const province = document.getElementById('province');
 const address = document.getElementById('address');
 const postcode = document.getElementById('postcode');
 if (!country || !province || !address || !postcode) return;
 const status = document.createElement('p');
 status.className = 'hint'; status.id = 'addressAutoStatus'; status.setAttribute('role', 'status');
 status.textContent = 'Enter your state name or Indian PIN code in the address to auto-fill. You can also enter the PIN below.';
 address.after(status);
 let countries = [], sequence = 0, timer, controller, autoState = null;
 const normalize = value => String(value).toLowerCase().replace(/[^a-z0-9]+/g, ' ').trim();
 const contains = (text, name) => (` ${text} `).includes(` ${normalize(name)} `);
 const ready = fetch('assets/customer-countries.json').then(r => { if (!r.ok) throw new Error(); return r.json(); }).then(data => { countries = data.data.countries; }).catch(() => { status.textContent = 'Automatic lookup is unavailable. Please select country and state.'; });
 function setStates(code, selected = '') {
   const entry = countries.find(c => c.code === code);
   province.replaceChildren(new Option('Select state', ''));
   for (const state of entry?.provinces || []) province.add(new Option(state.name, state.code));
   if (!entry?.provinces?.length) province.add(new Option('Not applicable', code));
   province.value = selected;
 }
 function cancelLookup() { ++sequence; clearTimeout(timer); controller?.abort(); }
 function applyLocation(code, stateCode, source) {
   country.value = code; setStates(code, stateCode); autoState = stateCode;
   status.textContent = `${source}: ${province.selectedOptions[0]?.text || ''}, ${country.selectedOptions[0]?.text || ''}. Please confirm these details.`;
 }
 async function inferAddress() {
   const version = ++sequence;
   controller?.abort();
   await ready;
   if (version !== sequence || !countries.length) return;
   const text = normalize(address.value);
   // Full names only: short codes such as IN/AP can also be ordinary address words.
   const namedCountries = countries.filter(c => contains(text, c.name));
   const candidates = [];
   for (const entry of countries) {
     if (namedCountries.length === 1 && entry.code !== namedCountries[0].code) continue;
     for (const state of entry.provinces || []) if (contains(text, state.name)) candidates.push({country:entry.code, province:state.code});
   }
   let namedState = candidates.length === 1 ? candidates[0] : null;
   if (namedState) applyLocation(namedState.country, namedState.province, 'Matched from address');
   else if (namedCountries.length === 1 && country.value !== namedCountries[0].code) {
     country.value = namedCountries[0].code; setStates(country.value);
   }
   const embeddedPins = address.value.match(/\b[1-9]\d{5}\b/g) || [];
   if (postcode.dataset.autoPin === postcode.value && embeddedPins.length !== 1) { postcode.value = ''; delete postcode.dataset.autoPin; }
   if (embeddedPins.length === 1 && (!postcode.value || postcode.dataset.autoPin === postcode.value)) {
     postcode.value = embeddedPins[0]; postcode.dataset.autoPin = embeddedPins[0];
   }
   const pin = postcode.value.trim();
   // Indian PIN lookup must not reinterpret postal codes of an explicitly foreign address.
   if (!/^[1-9]\d{5}$/.test(pin) || country.value !== 'IN') return;
   controller = new AbortController();
   status.textContent = 'Checking PIN code�';
   try {
     const response = await fetch(`address_lookup.php?pin=${encodeURIComponent(pin)}`, {signal:controller.signal});
     const result = await response.json();
     if (version !== sequence || postcode.value.trim() !== pin) return;
     if (!response.ok) throw new Error(result.error);
     if (namedState && namedState.province !== result.province) {
       province.value = '';
       status.textContent = `Address state and PIN code do not match (${result.state}). Please check your address/PIN and select the correct state.`;
       return;
     }
     applyLocation(result.country, result.province, 'Matched from PIN code');
   } catch (error) {
     if (version !== sequence || error.name === 'AbortError') return;
     status.textContent = namedState ? 'State matched from address. PIN lookup unavailable; please confirm your details.' : 'Automatic lookup unavailable or PIN not found. Please select country and state.';
   }
 }
 function clearAutomaticState() { if (autoState && province.value === autoState) province.value = ''; autoState = null; }
 address.addEventListener('input', () => { clearAutomaticState(); cancelLookup(); timer = setTimeout(inferAddress, 550); });
 postcode.addEventListener('input', () => { clearAutomaticState(); delete postcode.dataset.autoPin; cancelLookup(); timer = setTimeout(inferAddress, 550); });
 country.addEventListener('change', async () => {
   cancelLookup(); const version = sequence; const selected = country.value;
   await ready;
   if (version !== sequence || !countries.length) return;
   setStates(selected); status.textContent = 'Please select your state.';
 });
 province.addEventListener('change', () => { cancelLookup(); status.textContent = 'Using your selected country and state.'; });
})();

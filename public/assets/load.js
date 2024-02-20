const main = async () => {
    const req = await fetch('/fronters');
    const data = await req.json();

    const fixed = {};

    data.forEach(fronter => {
        for (const [key, value] of Object.entries(fronter)) {
            if (fixed[key]) {
                fixed[key].push(value);
            } else {
                fixed[key] = [value];
            }
        }
    });

    const to_load = document.getElementsByClassName('quick');

    for (const el of to_load) {
        const field = el.getAttribute('data-quick');

        if (!field) continue;

        el.innerText = (fixed[field.trim()] || []).join(', ');
    }
}

window.addEventListener('DOMContentLoaded', main);

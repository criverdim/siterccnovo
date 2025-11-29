import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';

function EventsApp() {
    const [data, setData] = React.useState({ data: [], meta: null });
    const [loading, setLoading] = React.useState(false);
    const [q, setQ] = React.useState('');
    const [paid, setPaid] = React.useState('');
    const [month, setMonth] = React.useState('');

    const fetchEvents = async (params = {}) => {
        setLoading(true);
        const usp = new URLSearchParams({ q, paid, month, ...params });
        const res = await fetch(`/api/events?${usp.toString()}`);
        const json = await res.json();
        setData(json);
        setLoading(false);
    };

    React.useEffect(() => { fetchEvents(); }, []);

    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input className="input" placeholder="Buscar por nome, local..." value={q} onChange={(e)=>setQ(e.target.value)} />
                <select className="input" value={paid} onChange={(e)=>setPaid(e.target.value)}>
                    <option value="">Todos</option>
                    <option value="free">Gratuitos</option>
                    <option value="paid">Pagos</option>
                </select>
                <select className="input" value={month} onChange={(e)=>setMonth(e.target.value)}>
                    <option value="">Mês</option>
                    {Array.from({length:12},(_,i)=>i+1).map(m=> (
                        <option key={m} value={m}>{String(m).padStart(2,'0')}</option>
                    ))}
                </select>
                <button className="btn btn-primary" onClick={()=>fetchEvents()}>Filtrar</button>
            </div>

            {loading ? (
                <div>Carregando…</div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {(data?.data ?? []).map((event)=> (
                        <div key={event.id} className="card card-hover">
                            <img src={(event.photos?.[0]) ?? 'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20event%20celebration%2C%20warm%20lighting%2C%20joyful%20atmosphere&image_size=landscape_4_3'} alt={event.name} className="w-full h-44 object-cover rounded-t-xl" />
                            <div className="card-section">
                                <h3 className="text-xl font-semibold text-gray-900 mb-1">{event.name}</h3>
                                <div className="text-sm text-gray-600 mb-3">{event.start_date ?? 'Data a definir'} • {event.location ?? 'Local a definir'}</div>
                                <p className="text-gray-700 mb-4">{(event.description ?? 'Evento da RCC.').slice(0,120)}</p>
                                <div className="flex items-center justify-between">
                                    {event.is_paid ? (
                                        <span className="inline-flex items-center gap-1 text-sm text-blue-700"><i className="fas fa-ticket-alt"></i> Pago</span>
                                    ) : (
                                        <span className="inline-flex items-center gap-1 text-sm text-emerald-700"><i className="fas fa-calendar-check"></i> Gratuito</span>
                                    )}
                                    <a href={`/events/${event.id}`} className="text-emerald-700 hover:text-emerald-800 font-medium">Detalhes</a>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const mount = document.getElementById('react-events-app');
    if (mount) {
        const root = createRoot(mount);
        root.render(<EventsApp />);
    }
});

// Reduce motion for users who prefer reduced motion
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (prefersReducedMotion) {
    document.documentElement.style.setProperty('scroll-behavior', 'auto');
}

// Focus-visible polyfill-like behavior for improved accessibility
document.addEventListener('keydown', (e) => {
    if (e.key === 'Tab') document.body.classList.add('using-keyboard');
});
document.addEventListener('mousedown', () => {
    document.body.classList.remove('using-keyboard');
});

// Utility: animate elements with [data-animate]
const animateEls = () => {
    document.querySelectorAll('[data-animate]')?.forEach((el) => el.classList.add('fade-in'));
};
document.addEventListener('DOMContentLoaded', animateEls);
function HomeApp() {
    React.useEffect(() => {
        const next = () => setCurrent((c) => (c + 1) % 3);
        let timer = setInterval(next, 7000);
        return () => clearInterval(timer);
    }, []);

    const [current, setCurrent] = React.useState(0);
    const slides = [
        'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Renova%C3%A7%C3%A3o%20Carism%C3%A1tica%20Cat%C3%B3lica%20event%20gathering%2C%20people%20worshipping%20together%2C%20warm%20lighting%2C%20church%20setting%2C%20spiritual%20atmosphere%2C%20professional%20photography&image_size=landscape_16_9',
        'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20charismatic%20prayer%20group%2C%20people%20holding%20hands%20in%20prayer%2C%20candles%20and%20cross%2C%20peaceful%20spiritual%20environment%2C%20warm%20golden%20lighting%2C%20professional%20photography&image_size=landscape_16_9',
        'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20church%20interior%20with%20people%20celebrating%2C%20stained%20glass%20windows%2C%20spiritual%20ceremony%2C%20warm%20atmospheric%20lighting%2C%20professional%20photography&image_size=landscape_16_9',
    ];

    return (
        <div className="relative">
            <section className="relative h-[500px] overflow-hidden">
                <div className="absolute inset-0 z-10 bg-gradient-to-r from-emerald-900/70 to-emerald-800/50" />
                <img src={slides[current]} alt="Slide RCC" className="absolute inset-0 w-full h-full object-cover" />
                <div className="relative z-20 h-full flex items-center justify-center text-center text-white">
                    <div className="max-w-4xl mx-auto px-4">
                        <h1 className="text-4xl md:text-6xl font-bold mb-4">Renovação Carismática Católica</h1>
                        <p className="text-xl md:text-2xl mb-8">Vivendo a fé com alegria, amor e poder do Espírito Santo</p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="/events" className="btn btn-primary btn-lg rounded-lg font-semibold">Próximos Eventos</a>
                            <a href="/register" className="btn btn-outline btn-lg rounded-lg font-semibold bg-white">Junte-se a Nós</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const homeMount = document.getElementById('react-home-app');
    if (homeMount) {
        const root = createRoot(homeMount);
        root.render(<HomeApp />);
    }
});
function GroupsApp() {
    const [q, setQ] = React.useState('');
    const [weekday, setWeekday] = React.useState('');
    const [groups, setGroups] = React.useState([]);
    const [loading, setLoading] = React.useState(false);

    const fetchGroups = async () => {
        setLoading(true);
        const usp = new URLSearchParams({ q, weekday });
        const res = await fetch(`/groups?${usp.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await res.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const items = Array.from(doc.querySelectorAll('[data-group-item]')).map((el) => ({
            id: el.getAttribute('data-id'),
            name: el.getAttribute('data-name'),
            weekday: el.getAttribute('data-weekday'),
            time: el.getAttribute('data-time'),
            address: el.getAttribute('data-address'),
        }));
        setGroups(items);
        setLoading(false);
    };

    React.useEffect(() => { fetchGroups(); }, []);

    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input className="input" placeholder="Buscar por nome, endereço..." value={q} onChange={(e)=>setQ(e.target.value)} />
                <select className="input" value={weekday} onChange={(e)=>setWeekday(e.target.value)}>
                    <option value="">Todos</option>
                    {['Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'].map((d)=> (
                        <option key={d} value={d}>{d}</option>
                    ))}
                </select>
                <button className="btn btn-primary" onClick={fetchGroups}>Filtrar</button>
            </div>
            {loading ? (
                <div>Carregando…</div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {groups.map((g)=> (
                        <a key={g.id} href={`/groups/${g.id}`} className="card card-hover block">
                            <div className="card-section">
                                <div className="font-semibold text-gray-900">{g.name}</div>
                                <div className="mt-1 text-sm text-gray-600">{g.weekday} • {g.time}</div>
                                <div className="mt-2 text-sm text-gray-600">{g.address}</div>
                                <div className="mt-3 text-emerald-700 font-medium">Ver detalhes</div>
                            </div>
                        </a>
                    ))}
                </div>
            )}
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const groupsMount = document.getElementById('react-groups-app');
    if (groupsMount) {
        const root = createRoot(groupsMount);
        root.render(<GroupsApp />);
    }
});
function CalendarApp() {
    const [month, setMonth] = React.useState(new Date().getMonth() + 1);
    const [days, setDays] = React.useState(Array.from({ length: 28 }, (_, i) => i + 1));
    const [eventsDays, setEventsDays] = React.useState(new Set());

    const fetchMonth = async (m) => {
        const usp = new URLSearchParams({ month: m });
        const res = await fetch(`/api/events?${usp.toString()}`);
        const json = await res.json();
        const ds = new Set((json?.data ?? []).map((e) => {
            const d = new Date(e.start_date);
            return d.getDate();
        }));
        setEventsDays(ds);
    };

    React.useEffect(() => { fetchMonth(month); }, [month]);

    return (
        <div className="space-y-4">
            <div>
                <select className="input" value={month} onChange={(e)=>setMonth(parseInt(e.target.value,10))}>
                    {Array.from({length:12},(_,i)=>i+1).map(m=> (
                        <option key={m} value={m}>{String(m).padStart(2,'0')}</option>
                    ))}
                </select>
            </div>
            <div className="card">
                <div className="grid grid-cols-7 gap-2 text-sm p-4">
                    {['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'].map((d)=>(
                        <div key={d} className="font-semibold text-gray-600">{d}</div>
                    ))}
                </div>
                <div className="grid grid-cols-7 gap-2 p-4">
                    {days.map((i)=> {
                        const hasEvent = eventsDays.has(i);
                        return (
                            <div key={i} className={`p-3 rounded border text-center ${hasEvent ? 'bg-emerald-50 border-emerald-300' : ''}`}>{i}</div>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const calendarMount = document.getElementById('react-calendar-app');
    if (calendarMount) {
        const root = createRoot(calendarMount);
        root.render(<CalendarApp />);
    }
});
function PastoreioApp() {
    const [query, setQuery] = React.useState('');
    const [groupId, setGroupId] = React.useState('');
    const [date, setDate] = React.useState('');
    const [name, setName] = React.useState('');
    const [cpf, setCpf] = React.useState('');
    const [phone, setPhone] = React.useState('');

    const submit = async (url, body) => {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content ?? '') },
            body: JSON.stringify(body)
        });
        return await res.json().catch(()=>({ ok: true }));
    };

    return (
        <div className="space-y-6">
            <div className="card p-6">
                <h2 className="font-semibold mb-4">Buscar fiel</h2>
                <div className="flex gap-2">
                    <input className="input w-full" placeholder="Buscar por nome, CPF, telefone" value={query} onChange={(e)=>setQuery(e.target.value)} />
                    <button className="btn btn-primary" onClick={()=>submit('/pastoreio/search',{ query })}>Buscar</button>
                </div>
            </div>
            <div className="card p-6">
                <h2 className="font-semibold mb-4">Registrar presença</h2>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <select className="input" value={groupId} onChange={(e)=>setGroupId(e.target.value)}>
                        {Array.from(document.querySelectorAll('[data-group-option]')).map((o)=> (
                            <option key={o.getAttribute('data-id')} value={o.getAttribute('data-id')}>{o.getAttribute('data-name')}</option>
                        ))}
                    </select>
                    <input type="date" className="input" value={date} onChange={(e)=>setDate(e.target.value)} />
                    <input className="input" placeholder="Nome (se novo)" value={name} onChange={(e)=>setName(e.target.value)} />
                    <input className="input" placeholder="CPF" value={cpf} onChange={(e)=>setCpf(e.target.value)} />
                    <input className="input" placeholder="Telefone" value={phone} onChange={(e)=>setPhone(e.target.value)} />
                    <button className="btn btn-primary" onClick={()=>submit('/pastoreio/attendance',{ group_id: groupId, date, name, cpf, phone })}>Registrar</button>
                </div>
            </div>
            <div className="card p-6">
                <h2 className="font-semibold mb-4">Sorteio</h2>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <select className="input" value={groupId} onChange={(e)=>setGroupId(e.target.value)}>
                        {Array.from(document.querySelectorAll('[data-group-option]')).map((o)=> (
                            <option key={o.getAttribute('data-id')} value={o.getAttribute('data-id')}>{o.getAttribute('data-name')}</option>
                        ))}
                    </select>
                    <input type="date" className="input" value={date} onChange={(e)=>setDate(e.target.value)} />
                    <input className="input" placeholder="Prêmio (opcional)" />
                    <button className="btn btn-primary" onClick={()=>submit('/pastoreio/draw',{ group_id: groupId, date })}>Sortear</button>
                </div>
            </div>
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const pastoreioMount = document.getElementById('react-pastoreio-app');
    if (pastoreioMount) {
        const root = createRoot(pastoreioMount);
        root.render(<PastoreioApp />);
    }
});
